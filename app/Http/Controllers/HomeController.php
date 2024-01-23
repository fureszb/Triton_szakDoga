<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function elsoKep()
    {
        $files = File::files(public_path('upload'));
        $imagePath = 'upload/' . $files[0]->getFilename();

        return view('elso_kep', compact('imagePath'));
    }
}
