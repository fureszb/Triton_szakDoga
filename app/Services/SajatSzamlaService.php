<?php

namespace App\Services;

use App\Models\Szamla;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SajatSzamlaService
{
    /**
     * Az összes nem-ASCII karaktert numerikus HTML entitássá alakítja.
     * A HTML5 parser (html5-php) ezeket megbízhatóan dekódolja, míg a raw
     * UTF-8 byteokat néha félreértelmezi a dompdf encoding-lánca.
     */
    private function encodeForDompdf(string $html): string
    {
        return mb_encode_numericentity($html, [0x80, 0xFFFF, 0, 0xFFFF], 'UTF-8');
    }

    /**
     * PDF generálása a Szamla adataiból, fájlba mentés.
     *
     * @return string  Storage path (storage/app relatív)
     */
    public function generate(Szamla $szamla): string
    {
        $szamla->load(['tetelek', 'megrendeles.ugyfel', 'megrendeles.varos']);

        $html = view('szamlak.pdf', ['szamla' => $szamla])->render();
        $pdf = Pdf::loadHtml($this->encodeForDompdf($html))
            ->setPaper('a4', 'portrait');

        $dir = 'szamlak';
        $file = "szamla-{$szamla->szamla_id}.pdf";
        $path = "{$dir}/{$file}";

        Storage::put($path, $pdf->output());

        return $path;
    }

    /**
     * PDF stream visszaadása letöltéshez (mentés nélkül).
     */
    public function stream(Szamla $szamla, string $filename = null): \Illuminate\Http\Response
    {
        $szamla->load(['tetelek', 'megrendeles.ugyfel', 'megrendeles.varos']);

        $filename ??= "szamla-{$szamla->szamla_id}.pdf";

        $html = view('szamlak.pdf', ['szamla' => $szamla])->render();
        $pdf = Pdf::loadHtml($this->encodeForDompdf($html))
            ->setPaper('a4', 'portrait');

        return $pdf->stream($filename);
    }

    /**
     * Teszt: vízjeles PDF stream (mentés nélkül).
     */
    public function tesztStream(Szamla $szamla): \Illuminate\Http\Response
    {
        $szamla->load(['tetelek', 'megrendeles.ugyfel', 'megrendeles.varos']);

        $html = view('szamlak.pdf', ['szamla' => $szamla, 'teszt' => true])->render();
        $pdf = Pdf::loadHtml($this->encodeForDompdf($html))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("TESZT-szamla-{$szamla->szamla_id}.pdf");
    }
}
