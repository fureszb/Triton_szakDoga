<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UgyfelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\SignaturePadController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\MegrendelesController;
use App\Http\Controllers\AnyagController;

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
    return redirect()->route('ugyfel.index');
})->middleware(['auth', 'verified'])->name('dashboard');

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

Route::get('/szolgaltatas-szerelok/{szolgaltatasId}', [MegrendelesController::class, 'getSzerelokBySzolgaltatas']);


Route::middleware('auth')->group(function () {


    // routes/web.php



    // routes/web.php



    Route::get('/anyagok/create', [AnyagController::class, 'create'])->name('anyagok.create');
    Route::post('/anyagok', [AnyagController::class, 'store'])->name('anyagok.store');



    Route::view('/ugyfel/create_modal', 'ugyfel.create_modal')->name('ugyfel.create_modal');



    Route::get('/megrendeles', [MegrendelesController::class, 'index'])->name('megrendeles.index');

    Route::get('/megrendeles/create', [MegrendelesController::class, 'create'])->name('megrendeles.create');

    Route::get('/megrendeles/{id}', [MegrendelesController::class, 'show'])->name('megrendeles.show');

    Route::post('/megrendeles', [MegrendelesController::class, 'store'])->name('megrendeles.store');

    Route::put('/megrendeles/{megrendeles}', [MegrendelesController::class, 'update'])->name('megrendeles.update');

    Route::delete('/megrendeles/{megrendeles}', [MegrendelesController::class, 'destroy'])->name('megrendeles.destroy');

    Route::get('/megrendeles/{megrendeles}/edit', [MegrendelesController::class, 'edit'])->name('megrendeles.edit');
    Route::get('/megrendeles/{munkaId}/pdf', [MegrendelesController::class, 'generatePdf'])->name('megrendeles.pdf');

    Route::get('/send-mail', [TestController::class, 'sendMailWithPdf']);

    // routes/web.php

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
});
Route::get('/elso-kep', 'App\Http\Controllers\HomeController@elsoKep');

require __DIR__ . '/auth.php';



// Útvonalak a MegrendelesController számára
