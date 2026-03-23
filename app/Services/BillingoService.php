<?php

namespace App\Services;

use App\Models\Megrendeles;
use App\Models\Szamla;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BillingoService
{
    protected string $apiKey;

    protected int|string $blockId;

    protected string $baseUrl = 'https://api.billingo.hu/v3';

    public function __construct()
    {
        $this->apiKey = config('services.billingo.api_key', '');
        $this->blockId = config('services.billingo.block_id', '');
    }

    /** Ellenőrzi, hogy a Billingo be van-e konfigurálva */
    public function isConfigured(): bool
    {
        return ! empty($this->apiKey)
            && ! str_starts_with($this->apiKey, 'REPLACE_');
    }

    /**
     * Számla létrehozása Billingóban egy megrendelés alapján.
     * Visszaad egy tömböt: ['id', 'invoice_number', 'pdf_url'] vagy dob kivételt.
     */
    public function createInvoice(Megrendeles $megrendeles): array
    {
        $ugyfel = $megrendeles->ugyfel;
        $varos = $megrendeles->varos;
        $osszeg = (float) $megrendeles->Vegosszeg;

        // Fizetési határidő: ha be van állítva, különben ma + 8 nap
        $hatarido = $megrendeles->FizetesiHatarido
            ? $megrendeles->FizetesiHatarido->format('Y-m-d')
            : now()->addDays(8)->format('Y-m-d');

        $payload = [
            'vendor_id' => (int) $this->blockId,
            'partner' => [
                'name' => $ugyfel?->szamlazasi_nev ?? $megrendeles->megrendeles_nev,
                'address' => [
                    'country_code' => 'HU',
                    'post_code' => (string) ($varos?->Irny_szam ?? ''),
                    'city' => $varos?->nev ?? '',
                    'address' => $ugyfel?->szamlazasi_cim ?? $megrendeles->utca_hazszam ?? '',
                ],
                'taxcode' => $ugyfel?->adoszam ?? null,
                'emails' => $ugyfel?->email ? [$ugyfel->email] : [],
            ],
            'block_id' => (int) $this->blockId,
            'type' => 'invoice',
            'fulfillment_date' => now()->format('Y-m-d'),
            'due_date' => $hatarido,
            'payment_method' => $megrendeles->FizetesiMod === 'stripe' ? 'online_bankcard' : 'transfer',
            'language' => 'hu',
            'currency' => 'HUF',
            'items' => [
                [
                    'name' => 'Megrendelés #'.str_pad($megrendeles->id, 5, '0', STR_PAD_LEFT)
                                       .' – '.$megrendeles->megrendeles_nev,
                    'unit_price' => $osszeg,
                    'unit_price_type' => 'gross',
                    'quantity' => 1,
                    'unit' => 'db',
                    'vat' => '27%',
                ],
            ],
        ];

        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post("{$this->baseUrl}/documents", $payload);

        if (! $response->successful()) {
            Log::error('Billingo hiba', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);
            throw new \RuntimeException('Billingo számla kiállítás sikertelen: '.$response->body());
        }

        $data = $response->json();

        // PDF link lekérése
        $pdfUrl = $this->getInvoicePdfUrl($data['id'] ?? null);

        return [
            'id' => $data['id'] ?? null,
            'invoice_number' => $data['invoice_number'] ?? null,
            'pdf_url' => $pdfUrl,
        ];
    }

    /**
     * Számla kiállítása Billingóban egy Szamla modell alapján.
     * (A díjbekérőből generált végleges számlához használandó.)
     *
     * @throws \RuntimeException
     */
    public function createInvoiceFromSzamla(Szamla $szamla): array
    {
        $szamla->load(['megrendeles.ugyfel', 'megrendeles.varos', 'tetelek']);
        $megrendeles = $szamla->megrendeles;
        $ugyfel = $megrendeles?->ugyfel;
        $varos = $megrendeles?->varos;

        $fizetesiMod = match ($szamla->fizetesi_mod) {
            'stripe' => 'online_bankcard',
            default => 'transfer',
        };

        // Tételek összeállítása: ha vannak részletes tételek, azok; egyébként egyetlen összesítő sor
        $items = [];
        if ($szamla->tetelek->isNotEmpty()) {
            foreach ($szamla->tetelek as $tetel) {
                $items[] = [
                    'name' => $tetel->nev,
                    'unit_price' => (float) $tetel->brutto_osszeg,
                    'unit_price_type' => 'gross',
                    'quantity' => (float) $tetel->mennyiseg,
                    'unit' => $tetel->mertekegyseg,
                    'vat' => $tetel->afa_kulcs.'%',
                ];
            }
        } else {
            $items[] = [
                'name' => 'Megrendelés #'.str_pad($megrendeles->id, 5, '0', STR_PAD_LEFT)
                                     .' – '.$megrendeles->megrendeles_nev,
                'unit_price' => (float) $szamla->brutto_osszeg,
                'unit_price_type' => 'gross',
                'quantity' => 1,
                'unit' => 'db',
                'vat' => '27%',
            ];
        }

        $payload = [
            'vendor_id' => (int) $this->blockId,
            'partner' => [
                'name' => $ugyfel?->szamlazasi_nev ?? $megrendeles->megrendeles_nev,
                'address' => [
                    'country_code' => 'HU',
                    'post_code' => (string) ($varos?->Irny_szam ?? ''),
                    'city' => $varos?->nev ?? '',
                    'address' => $ugyfel?->szamlazasi_cim ?? $megrendeles->utca_hazszam ?? '',
                ],
                'taxcode' => $ugyfel?->adoszam ?? null,
                'emails' => $ugyfel?->email ? [$ugyfel->email] : [],
            ],
            'block_id' => (int) $this->blockId,
            'type' => 'invoice',
            'fulfillment_date' => ($szamla->teljesites_datum ?? now())->format('Y-m-d'),
            'due_date' => ($szamla->fizetesi_hatarido ?? now())->format('Y-m-d'),
            'payment_method' => $fizetesiMod,
            'language' => 'hu',
            'currency' => 'HUF',
            'items' => $items,
        ];

        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post("{$this->baseUrl}/documents", $payload);

        if (! $response->successful()) {
            Log::error('Billingo hiba (szamla alapú)', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);
            throw new \RuntimeException('Billingo számla kiállítás sikertelen: '.$response->body());
        }

        $data = $response->json();
        $pdfUrl = $this->getInvoicePdfUrl($data['id'] ?? null);

        return [
            'id' => $data['id'] ?? null,
            'invoice_number' => $data['invoice_number'] ?? null,
            'pdf_url' => $pdfUrl,
        ];
    }

    /** Billingo számla PDF URL lekérése */
    public function getInvoicePdfUrl(?int $invoiceId): ?string
    {
        if (! $invoiceId) {
            return null;
        }

        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/documents/{$invoiceId}/download");

        if ($response->successful()) {
            return $response->json('url') ?? $response->json('data.url');
        }

        return null;
    }

    /** Teszt: Billingo API kapcsolat ellenőrzése */
    public function testConnection(): bool
    {
        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/utils/time");

            return $response->successful();
        } catch (\Throwable $e) {
            return false;
        }
    }
}
