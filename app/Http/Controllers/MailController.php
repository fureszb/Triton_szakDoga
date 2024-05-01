<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Ugyfel;
use PDF;
use Mail;
use Illuminate\Support\Facades\Response;
use App\Models\Megrendeles;
use Illuminate\Support\Facades\Session;

class MailController extends Controller
{
    public function sendMailWithPdf()
    {
        $megrendeles = Megrendeles::with(['ugyfel', 'szolgaltatas', 'szerelo', 'felhasznaltAnyagok', 'felhasznaltAnyagok.anyag', 'munkak'])->latest()->first();
        $szereloData = Session::get('szereloData');
        $imgPathSzerelo =  $szereloData['Szerelo_ID'] . '_' . $szereloData['Nev'] . '.png';

        $imgPath =  $megrendeles->ugyfel->Ugyfel_ID . '_' . $megrendeles->ugyfel->Nev . '.png';


        $data["imgPathSzerelo"] = $imgPathSzerelo;
        $data["email"] = "frsz.bence@gmail.com";
        $data["title"] = "Szerződéskötés";
        $data["megrendeles"] = $megrendeles;


        $pdf = PDF::loadView('mail', $data)->setOptions(['defaultFont' => 'sans-serif', 'encoding' => 'UTF-8']);


        $pdfFileName = $megrendeles->ugyfel->Ugyfel_ID . '_' . $megrendeles->ugyfel->Nev . '_' . $szereloData['Szolgaltatas_ID'] . '_' . $megrendeles->Megrendeles_ID . '.pdf';
        $pdfFilePath = storage_path('app/public/' . $pdfFileName);
        $pdf->save($pdfFilePath);

        $megrendeles->Pdf_EleresiUt = $pdfFilePath;
        $megrendeles->save();


        Mail::send('mail', $data, function ($message) use ($data, $pdf, $megrendeles) {
            $pdfFileName = $megrendeles->ugyfel->Ugyfel_ID . '_' . $megrendeles->ugyfel->Nev . '.pdf';
            $message->to($data["email"])
                ->subject($data["title"])
                ->attachData($pdf->output(), $pdfFileName);
        });


        /*$folderPath = public_path('alaIrasokUgyfel');
        $files = File::files($folderPath);
        foreach ($files as $file) {
            File::delete($file);
        }*/

        $message = 'Az aláírás és az ügyfél sikeresen mentve lett és az email elküldve!';

        return redirect()->route('megrendeles.index')->with('success', $message);
    }
}
