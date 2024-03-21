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
        // $varos = Varos::where('Varos_ID', $ugyfel->Varos_ID)->latest()->first();
        $megrendeles = Megrendeles::with(['ugyfel', 'szolgaltatas', 'szerelo', 'felhasznaltAnyagok', 'felhasznaltAnyagok.anyag', 'munkak'])->latest()->first();
        $szereloData = Session::get('szereloData');
        $imgPathSzerelo = $szereloData['Szerelo_ID'] . '_' . $szereloData['Nev'] . '.png';

        //$imgPath =  $megrendeles->ugyfel->Ugyfel_ID . '_' . $megrendeles->ugyfel->Nev . '.png';


        $data["imgPathSzerelo"] = $imgPathSzerelo;
        $data["email"] = "frsz.bence@gmail.com";
        $data["title"] = "Szerződéskötés";
        $data["megrendeles"] = $megrendeles;


        $pdf = PDF::loadView('mail', $data)->setOptions(['defaultFont' => 'sans-serif', 'encoding' => 'UTF-8']);



         $pdfFileName = $megrendeles->ugyfel->Ugyfel_ID . '_' . $megrendeles->ugyfel->Nev . '_' . $szereloData['Szolgaltatas_ID'] . '_' . $megrendeles->Megrendeles_ID . '.pdf';
        //$pdfFileName = "teszt.pdf";
        $pdfFilePath = storage_path('app/public/' . $pdfFileName);
        $pdf->save($pdfFilePath);







        // E-mail küldése
        /*
        Mail::send('mail', $data, function ($message) use ($data, $pdf, $megrendeles) {
            $pdfFileName = $megrendeles->ugyfel->Ugyfel_ID . '_' . $megrendeles->ugyfel->Nev . '.pdf';
            $message->to($data["email"])
                ->subject($data["title"])
                ->attachData($pdf->output(), $pdfFileName);
        });*/


        // Képek törlése a public/images mappából
        /*$folderPath = public_path('kepek');
           $files = File::files($folderPath);
           foreach ($files as $file) {
               File::delete($file);
           }*/

        // Letöltés a böngészőbe

        $message = 'Az aláírás és az ügyfél sikeresen mentve lett és az email elküldve!';


        /*return Response::make($pdf->output(), 'application/pdf')
        ->setStatusCode(200)
        ->setStatus('OK')
        ->send();*/
        //return redirect('/ugyfel')->with('success', 'Az aláírás és az ügyfél sikeresen mentve lett és az email elküldve!');
        // return  $pdf->download('Triton-Security.pdf');
        //return  $pdf->save($pdfFilePath);
        return redirect()->route('ugyfel.index')->with('success', $message);
    }
}
