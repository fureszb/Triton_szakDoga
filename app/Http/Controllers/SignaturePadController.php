<?php

namespace App\Http\Controllers;

use App\Models\Megrendeles;
use App\Models\Ugyfel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class SignaturePadController extends Controller
{

    public function index()
    {
        return view('signaturePad');
    }

    public function upload(Request $request)
    {
        //return redirect('/megrendeles')->with('success', 'Az aláírás és az ügyfél sikeresen mentve lett!');
    }
    public function saveImage(Request $request)
    {
        $imageData = $request->input('dataURL');
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = base64_decode($imageData);

        $folderPath = public_path('kepek');
        $fileName = 'alairas.png';

        file_put_contents($folderPath . '/' . $fileName, $imageData);
        return response()->json(['success' => true]);
        //return redirect('/send-mail')->with('success', 'Az aláírás és az ügyfél sikeresen mentve lett!');
    }


}
