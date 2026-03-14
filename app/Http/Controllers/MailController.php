<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Ugyfel;
use App\Models\Munka;
use PDF;
use Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Models\Megrendeles;
use App\Models\Cegadat;
use Illuminate\Support\Facades\Session;

class MailController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // PDF PREVIEW
    // ─────────────────────────────────────────────────────────────────────────

    public function previewPdf()
    {
        $megrendeles = Megrendeles::with([
            'ugyfel', 'ugyfel.varos', 'munkak', 'munkak.szerelo',
            'munkak.szolgaltatas', 'felhasznaltAnyagok', 'felhasznaltAnyagok.anyag'
        ])->latest()->first();

        $szereloData    = Session::get('szereloData') ?? ['Szerelo_ID' => '', 'Nev' => 'Szerelo', 'Szolgaltatas_ID' => ''];
        $imgPathSzerelo = $szereloData['Szerelo_ID'] . '_' . $szereloData['Nev'] . '.png';
        $cegadat        = Cegadat::get();

        return view('mail', compact('megrendeles', 'imgPathSzerelo', 'cegadat'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SEND MAIL – Új megrendelés létrehozásakor (session alapján, latest mr.)
    // ─────────────────────────────────────────────────────────────────────────

    public function sendMailWithPdf()
    {
        $megrendeles = Megrendeles::with([
            'ugyfel', 'szolgaltatas', 'szerelo', 'felhasznaltAnyagok',
            'felhasznaltAnyagok.anyag', 'munkak'
        ])->latest()->first();

        if (! $megrendeles) {
            return redirect()->route('megrendeles.index')
                ->with('error', 'Nem található megrendelés az email küldéséhez.');
        }

        $szereloData    = Session::get('szereloData');
        $imgPathSzerelo = ($szereloData['Szerelo_ID'] ?? '') . '_' . ($szereloData['Nev'] ?? '') . '.png';
        $cegadat        = Cegadat::get();

        $email = $megrendeles->ugyfel?->Email ?? null;

        $data = [
            'imgPathSzerelo' => $imgPathSzerelo,
            'email'          => $email ?? config('mail.from.address', 'noreply@triton.hu'),
            'title'          => 'Szerződéskötés',
            'megrendeles'    => $megrendeles,
            'cegadat'        => $cegadat,
        ];

        // PDF generálás
        try {
            $pdf = PDF::loadView('mail', $data)->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isRemoteEnabled'      => true,
                'isHtml5ParserEnabled' => true,
                'chroot'               => public_path(),
            ]);

            $pdfFileName = ($megrendeles->ugyfel->Ugyfel_ID ?? 0)
                . '_' . ($megrendeles->ugyfel->Nev ?? 'ismeretlen')
                . '_' . ($szereloData['Szolgaltatas_ID'] ?? 0)
                . '_' . $megrendeles->Megrendeles_ID . '.pdf';

            $pdfFilePath = storage_path('app/public/' . $pdfFileName);
            $pdf->save($pdfFilePath);

            $megrendeles->Pdf_EleresiUt = $pdfFilePath;
            $megrendeles->save();
        } catch (\Throwable $e) {
            Log::error('PDF generálás hiba (sendMailWithPdf): ' . $e->getMessage());
            return redirect()->route('megrendeles.index')
                ->with('error', 'A PDF generálása sikertelen: ' . $e->getMessage());
        }

        // Email küldés – try/catch, nem dob Laravel hibát
        try {
            Mail::send('mail', $data, function ($message) use ($data, $pdf, $megrendeles) {
                $pdfFileName = ($megrendeles->ugyfel->Ugyfel_ID ?? 0)
                    . '_' . ($megrendeles->ugyfel->Nev ?? 'ismeretlen') . '.pdf';
                $message->to($data['email'])
                    ->subject($data['title'])
                    ->attachData($pdf->output(), $pdfFileName);
            });
        } catch (\Throwable $e) {
            Log::error('Email küldési hiba (sendMailWithPdf): ' . $e->getMessage());
            return redirect()->route('megrendeles.index')
                ->with('email_hiba', $megrendeles->Megrendeles_ID)
                ->with('error', 'A megrendelés elmentve, de az emailt nem sikerült elküldeni. Az email később újraküldhető a megrendelés oldalán.');
        }

        return redirect()->route('megrendeles.index')
            ->with('success', 'Az aláírás és az ügyfél sikeresen mentve lett és az email elküldve!');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // RESEND MAIL – Meglévő megrendeléshez email újraküldése
    // ─────────────────────────────────────────────────────────────────────────

    public function resendMail(Megrendeles $megrendeles)
    {
        $megrendeles->load([
            'ugyfel', 'munkak.szerelo', 'munkak.szolgaltatas',
            'felhasznaltAnyagok.anyag',
        ]);

        $email = $megrendeles->ugyfel?->Email ?? null;

        if (! $email) {
            return back()->with('error', 'Az ügyfélhez nincs email cím rögzítve.');
        }

        // Szerelő adatok adatbázisból (nem session-ből)
        $elsoMunka      = $megrendeles->munkak->first();
        $szerelo        = $elsoMunka?->szerelo;
        $imgPathSzerelo = ($szerelo?->Szerelo_ID ?? '') . '_' . ($szerelo?->Nev ?? '') . '.png';

        $cegadat = Cegadat::get();

        $data = [
            'imgPathSzerelo' => $imgPathSzerelo,
            'email'          => $email,
            'title'          => 'Szerződéskötés – ' . $megrendeles->Megrendeles_Nev,
            'megrendeles'    => $megrendeles,
            'cegadat'        => $cegadat,
        ];

        // PDF (újra)generálás
        try {
            $pdf = PDF::loadView('mail', $data)->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isRemoteEnabled'      => true,
                'isHtml5ParserEnabled' => true,
                'chroot'               => public_path(),
            ]);

            $szolgaltatasId = $elsoMunka?->Szolgaltatas_ID ?? 0;
            $pdfFileName    = ($megrendeles->ugyfel->Ugyfel_ID ?? 0)
                . '_' . ($megrendeles->ugyfel->Nev ?? 'ismeretlen')
                . '_' . $szolgaltatasId
                . '_' . $megrendeles->Megrendeles_ID . '.pdf';

            $pdfFilePath = storage_path('app/public/' . $pdfFileName);
            $pdf->save($pdfFilePath);

            $megrendeles->Pdf_EleresiUt = $pdfFilePath;
            $megrendeles->save();
        } catch (\Throwable $e) {
            Log::error('PDF generálás hiba (resendMail #' . $megrendeles->Megrendeles_ID . '): ' . $e->getMessage());
            return back()->with('error', 'A PDF generálása sikertelen: ' . $e->getMessage());
        }

        // Email küldés
        try {
            Mail::send('mail', $data, function ($message) use ($data, $pdf, $megrendeles) {
                $fname = ($megrendeles->ugyfel->Ugyfel_ID ?? 0)
                    . '_' . ($megrendeles->ugyfel->Nev ?? 'ismeretlen') . '.pdf';
                $message->to($data['email'])
                    ->subject($data['title'])
                    ->attachData($pdf->output(), $fname);
            });
        } catch (\Throwable $e) {
            Log::error('Email újraküldési hiba (resendMail #' . $megrendeles->Megrendeles_ID . '): ' . $e->getMessage());
            return back()->with('error', 'Az emailt nem sikerült elküldeni: ' . $e->getMessage());
        }

        return back()->with('success', 'Email sikeresen elküldve (' . $email . ')!');
    }
}
