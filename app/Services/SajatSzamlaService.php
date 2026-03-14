<?php

namespace App\Services;

use App\Models\Szamla;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SajatSzamlaService
{
    /**
     * PDF generálása a Szamla adataiból, fájlba mentés.
     * @return string  Storage path (storage/app relatív)
     */
    public function generate(Szamla $szamla): string
    {
        $szamla->load(['tetelek', 'megrendeles.ugyfel', 'megrendeles.varos']);

        $pdf = Pdf::loadView('szamlak.pdf', ['szamla' => $szamla])
                  ->setPaper('a4', 'portrait');

        $dir  = 'szamlak';
        $file = "szamla-{$szamla->szamla_id}.pdf";
        $path = "{$dir}/{$file}";

        Storage::put($path, $pdf->output());

        return $path;
    }

    /**
     * PDF stream visszaadása letöltéshez (mentés nélkül – teszt célra).
     */
    public function stream(Szamla $szamla, string $filename = null): \Illuminate\Http\Response
    {
        $szamla->load(['tetelek', 'megrendeles.ugyfel', 'megrendeles.varos']);

        $filename ??= "szamla-{$szamla->szamla_id}.pdf";

        $pdf = Pdf::loadView('szamlak.pdf', ['szamla' => $szamla, 'teszt' => true])
                  ->setPaper('a4', 'portrait');

        return $pdf->stream($filename);
    }

    /**
     * Teszt: vízjeles PDF stream (mentés nélkül).
     */
    public function tesztStream(Szamla $szamla): \Illuminate\Http\Response
    {
        $szamla->load(['tetelek', 'megrendeles.ugyfel', 'megrendeles.varos']);

        $pdf = Pdf::loadView('szamlak.pdf', ['szamla' => $szamla, 'teszt' => true])
                  ->setPaper('a4', 'portrait');

        return $pdf->stream("TESZT-szamla-{$szamla->szamla_id}.pdf");
    }
}
