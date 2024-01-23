<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Ugyfel;
use PDF;
use Mail;
use Illuminate\Support\Facades\Response;

class TestController extends Controller
{
    public function sendMailWithPdf()
    {
        $ugyfel = Ugyfel::latest()->first();

        $data["email"] = "frsz.bence@gmail.com";
        $data["title"] = "Szerződéskötés";
        $data["ugyfel"] = $ugyfel;

        $pdf = PDF::loadView('mail', $data)->setOptions(['defaultFont' => 'sans-serif', 'encoding' => 'UTF-8']);



        // E-mail küldése
        Mail::send('mail', $data, function ($message) use ($data, $pdf) {
            $message->to($data["email"])
                ->subject($data["title"])
                ->attachData($pdf->output(), "Triton-Security.pdf");
        });

        $pdfFilePath = storage_path('app/public/Triton-Security.pdf');
        $pdf->save($pdfFilePath);

        // Képek törlése a public/images mappából
        /*$folderPath = public_path('kepek');
           $files = File::files($folderPath);
           foreach ($files as $file) {
               File::delete($file);
           }*/

        // Letöltés a böngészőbe
        $pdf->download('Triton-Security.pdf');

        $message = 'Az aláírás és az ügyfél sikeresen mentve lett és az email elküldve!';


        /*return Response::make($pdf->output(), 'application/pdf')
        ->setStatusCode(200)
        ->setStatus('OK')
        ->send();*/
        //return redirect('/ugyfel')->with('success', 'Az aláírás és az ügyfél sikeresen mentve lett és az email elküldve!');
        return redirect()->route('ugyfel.index')->with('success', $message);

    }
}
