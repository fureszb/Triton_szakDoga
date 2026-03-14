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
use App\Http\Controllers\CegadatController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SzamlaController;
use App\Http\Controllers\FizetesController;
use App\Http\Controllers\EmlekeztetoController;
use App\Http\Controllers\BeallitasokController;
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
    return view('landing');
})->name('landing');

Route::get('/dashboard', function () {
    $user = Auth::user();
    switch ($user->role) {
        case 'Ugyfel':
            return redirect()->route('ugyfel.megrendelesek');
        case 'Admin':
            return redirect()->route('home.index');
        case 'Uzletkoto':
            return redirect()->route('home.index');
        default:
            return view('dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {

    Route::middleware('can:access-ugyfel')->group(function () {

        Route::get('/ugyfel/megrendelesek', [UgyfelController::class, 'megrendelesek'])->name('ugyfel.megrendelesek');
        Route::get('/ugyfel/szamlak', [UgyfelController::class, 'szamlak'])->name('ugyfel.szamlak');
        Route::get('/ugyfel/adataim', [UgyfelController::class, 'adataim'])->name('ugyfel.adataim');
        Route::put('/ugyfel/adataim', [UgyfelController::class, 'updateAdataim'])->name('ugyfel.adataim.update');
    });

    Route::middleware('can:access-admin-or-uzletkoto')->group(function () {

        Route::get('/megrendeles', [MegrendelesController::class, 'index'])->name('megrendeles.index');
        Route::post('/megrendeles', [MegrendelesController::class, 'store'])->name('megrendeles.store');
        Route::get('/megrendeles/create', [MegrendelesController::class, 'create'])->name('megrendeles.create');
        Route::put('/megrendeles/{megrendeles}', [MegrendelesController::class, 'update'])->name('megrendeles.update');
        Route::delete('/megrendeles/{megrendeles}', [MegrendelesController::class, 'destroy'])->name('megrendeles.destroy');
        Route::get('/megrendeles/{megrendeles}/edit', [MegrendelesController::class, 'edit'])->name('megrendeles.edit');
        Route::get('/megrendeles/{id}', [MegrendelesController::class, 'show'])->name('megrendeles.show');
        Route::post('/megrendeles/{megrendeles}/email-ujra', [MailController::class, 'resendMail'])->name('megrendeles.resend-email');

        Route::get('/send-mail', [MailController::class, 'sendMailWithPdf']);
        Route::get('/preview-pdf', [MailController::class, 'previewPdf']);
        Route::post('/save-image', [SignaturePadController::class, 'saveImage']);
        Route::get('/signaturepad', [SignaturePadController::class, 'index'])->name('signaturepad');

        Route::post('/ugyfel', [UgyfelController::class, 'store'])->name('ugyfel.store');
        Route::put('/ugyfel/{ugyfel}', [UgyfelController::class, 'update'])->name('ugyfel.update');
        Route::get('/ugyfel/create', [UgyfelController::class, 'create'])->name('ugyfel.create');
        Route::delete('/ugyfel/{ugyfel}', [UgyfelController::class, 'destroy'])->name('ugyfel.destroy');
        Route::get('/ugyfel/{ugyfel}/edit', [UgyfelController::class, 'edit'])->name('ugyfel.edit');
        Route::get('/ugyfel', [UgyfelController::class, 'index'])->name('ugyfel.index');
        Route::get('/ugyfel/{id}', [UgyfelController::class, 'show'])->name('ugyfel.show');

        Route::resource('home', HomeController::class);
    });

    Route::middleware('can:access-admin')->group(function () {

        Route::resource('users', UserController::class);
        Route::resource('anyagok', AnyagController::class);
        Route::resource('szerelok', SzereloController::class);
        Route::post('/save-image2', [SzereloController::class, 'saveImage'])->name('save-image2');

        Route::get('/cegadatok', [CegadatController::class, 'edit'])->name('cegadatok.edit');
        Route::put('/cegadatok', [CegadatController::class, 'update'])->name('cegadatok.update');
    });
    Route::middleware('can:access-uzletkoto')->group(function () {
    });

    Route::get('/megrendeles/{id}', [MegrendelesController::class, 'show'])->name('megrendeles.show');

    // ── Fizetés (minden bejelentkezett felhasználó) ──────────────────────────
    Route::get('/megrendeles/{megrendeles}/fizet',        [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/megrendeles/{megrendeles}/fizet/siker',  [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/megrendeles/{megrendeles}/fizet/megsem', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::post('/megrendeles/{megrendeles}/fizetve',     [PaymentController::class, 'manualMarkPaid'])->name('payment.manual')->middleware('can:access-admin-or-uzletkoto');

    // ── Számlázás (legacy – megrendelés-alapú) ───────────────────────────────
    Route::post('/megrendeles/{megrendeles}/szamla',         [SzamlaController::class, 'legacyBillingoCreate'])->name('szamla.create')->middleware('can:access-admin-or-uzletkoto');
    Route::get('/megrendeles/{megrendeles}/szamla/letoltes', [SzamlaController::class, 'legacyDownload'])->name('szamla.download');

    // ── Számlák CRUD ─────────────────────────────────────────────────────────
    Route::middleware('can:access-admin-or-uzletkoto')->group(function () {
        Route::get('/szamlak',                          [SzamlaController::class, 'index'])->name('szamlak.index');
        Route::get('/szamlak/create',                   [SzamlaController::class, 'createForm'])->name('szamlak.create');
        Route::post('/szamlak',                         [SzamlaController::class, 'store'])->name('szamlak.store');
        Route::get('/szamlak/{szamla}',                 [SzamlaController::class, 'show'])->name('szamlak.show');
        Route::get('/szamlak/{szamla}/edit',            [SzamlaController::class, 'edit'])->name('szamlak.edit');
        Route::put('/szamlak/{szamla}',                 [SzamlaController::class, 'update'])->name('szamlak.update');
        Route::post('/szamlak/{szamla}/fizetve',        [SzamlaController::class, 'markAsPaid'])->name('szamlak.markAsPaid');
        Route::post('/szamlak/{szamla}/storno',         [SzamlaController::class, 'storno'])->name('szamlak.storno');
        Route::post('/szamlak/{szamla}/billingo',        [SzamlaController::class, 'billingoKiallitas'])->name('szamlak.billingo');
        Route::post('/szamlak/{szamla}/sajat',           [SzamlaController::class, 'sajatKiallitas'])->name('szamlak.sajat');
        Route::get('/szamlak/{szamla}/teszt',            [SzamlaController::class, 'tesztLetoltes'])->name('szamlak.teszt');
    });

    // ── Számla letöltés (minden bejelentkezett felhasználó – ellenőrzés controllerben) ──
    Route::get('/szamlak/{szamla}/letoltes',       [SzamlaController::class, 'download'])->name('szamlak.download');
    Route::get('/szamlak/{szamla}/sajat/letoltes', [SzamlaController::class, 'sajatLetoltes'])->name('szamlak.sajat.letoltes');

    // ── Fizetések áttekintés ─────────────────────────────────────────────────
    Route::get('/fizetesek', [FizetesController::class, 'index'])->name('fizetes.index')->middleware('can:access-admin-or-uzletkoto');

    // ── Fizetési emlékeztetők GUI ──────────────────────────────────────────────
    Route::middleware('can:access-admin-or-uzletkoto')->group(function () {
        Route::get('/emlekeztetok', [EmlekeztetoController::class, 'index'])->name('emlekeztetok.index');
        Route::post('/emlekeztetok/{szamla}/kuldes', [EmlekeztetoController::class, 'kuldes'])->name('emlekeztetok.kuldes');
    });

    // ── Beállítások ──────────────────────────────────────────────────────────
    Route::get('/beallitasok', [BeallitasokController::class, 'index'])->name('beallitasok.index')->middleware('can:access-admin');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/szolgaltatas-szerelok/{szolgaltatasId}', [MegrendelesController::class, 'getSzerelokBySzolgaltatas']);
    Route::get('/download-pdf/{ugyfelId}_{ugyfelNev}_{szolgaltatasId}_{Megrendeles_ID}', [MegrendelesController::class, 'downloadPdf']);
    Route::get('/view-pdf/{ugyfelId}_{ugyfelNev}_{szolgaltatasId}_{Megrendeles_ID}', [MegrendelesController::class, 'viewPdf']);
});

require __DIR__ . '/auth.php';
