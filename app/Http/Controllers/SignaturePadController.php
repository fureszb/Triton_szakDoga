<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignaturePadController extends Controller
{
    public function index()
    {
        return view('signaturePad');
    }

    public function saveImage(Request $request)
    {
        $imageData = $request->input('dataURL');
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = base64_decode($imageData);

        $fileName = 'alairas.png';
        $folderPath = public_path('alaIrasokUgyfel');

        file_put_contents($folderPath.'/'.$fileName, $imageData);

        return response()->json(['success' => true]);
    }
}
