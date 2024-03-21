<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UgyfelController;
use App\Http\Controllers\SzolgaltatasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\SignaturePadController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\MegrendelesController;
use App\Http\Controllers\AnyagController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SzereloController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role == 'Ugyfel') {
            return redirect()->route('ugyfel.megrendelesek');
        }
        if (($user->role == 'Admin')) {
            return redirect()->route('ugyfel.index');
        }
        if (($user->role == 'Uzletkoto')) {
            return redirect()->route('megrendeles.index');
        }
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/szolgaltatas-szerelok/{szolgaltatasId}', [MegrendelesController::class, 'getSzerelokBySzolgaltatas']);


Route::middleware('auth')->group(function () {


    // routes/web.php



    // routes/web.php






    Route::view('/ugyfel/create_modal', 'ugyfel.create_modal')->name('ugyfel.create_modal');

    Route::get('/szolgaltatasok', [SzolgaltatasController::class, 'index']);

    Route::get('/megrendeles', [MegrendelesController::class, 'index'])->name('megrendeles.index');

    Route::get('/megrendeles/create', [MegrendelesController::class, 'create'])->name('megrendeles.create');

    Route::get('/megrendeles/{id}', [MegrendelesController::class, 'show'])->name('megrendeles.show');

    Route::post('/megrendeles', [MegrendelesController::class, 'store'])->name('megrendeles.store');

    Route::put('/megrendeles/{megrendeles}', [MegrendelesController::class, 'update'])->name('megrendeles.update');

    Route::delete('/megrendeles/{megrendeles}', [MegrendelesController::class, 'destroy'])->name('megrendeles.destroy');

    Route::get('/megrendeles/{megrendeles}/edit', [MegrendelesController::class, 'edit'])->name('megrendeles.edit');

    Route::get('/download-pdf/{ugyfelId}_{ugyfelNev}_{szolgaltatasId}_{Megrendeles_ID}', [MegrendelesController::class, 'downloadPdf']);

    Route::get('/send-mail', [MailController::class, 'sendMailWithPdf']);

    Route::get('/ugyfel/megrendelesek', [UgyfelController::class, 'megrendelesek'])->name('ugyfel.megrendelesek');


    Route::post('/save-image', [SignaturePadController::class, 'saveImage']);


    Route::get('/signaturepad', [SignaturePadController::class, 'index'])->name('signaturepad');
    Route::post('/signaturepad', [SignaturePadController::class, 'upload'])->name('signaturepad.upload');


    //Route::get('generate-pdf', [PDFController::class, 'generatePDF']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/ugyfel', [UgyfelController::class, 'store'])->name('ugyfel.store');
    Route::put('/ugyfel/{ugyfel}', [UgyfelController::class, 'update'])->name('ugyfel.update');
    Route::get('/ugyfel/create', [UgyfelController::class, 'create'])->name('ugyfel.create');
    Route::delete('/ugyfel/{ugyfel}', [UgyfelController::class, 'destroy'])->name('ugyfel.destroy');
    Route::get('/ugyfel/{ugyfel}/edit', [UgyfelController::class, 'edit'])->name('ugyfel.edit');

    Route::get('/ugyfel', [UgyfelController::class, 'index'])->name('ugyfel.index');
    Route::get('/ugyfel/{id}', [UgyfelController::class, 'show'])->name('ugyfel.show');


    Route::get('/ugyfel/search', 'UgyfelController@search')->name('ugyfel.search');

    Route::resource('users', UserController::class);
    Route::resource('home', HomeController::class);
    Route::resource('anyagok', AnyagController::class);
    Route::resource('szerelok', SzereloController::class);

    Route::post('/save-image2', [SzereloController::class, 'saveImage'])->name('save-image2');

    //Route::get('/anyagok/create', [AnyagController::class, 'create'])->name('anyagok.create');
    //Route::post('/anyagok', [AnyagController::class, 'store'])->name('anyagok.store');



});
Route::get('/elso-kep', 'App\Http\Controllers\HomeController@elsoKep');

require __DIR__ . '/auth.php';



// Útvonalak a MegrendelesController számára
