<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('pages.home.home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard1', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard1');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('pages.home.home');
});

Route::get('/kategori/facetreatment', function () {
    return view('pages.kategori.facetreatment');
});

Route::get('/kategori/bodytreatment', function () {
    return view('pages.kategori.bodytreatment');
});

Route::get('/kategori/hairtreatment', function () {
    return view('pages.kategori.hairtreatment');
});

Route::get('/kategori/reflexology', function () {
    return view('pages.kategori.reflexology');
});

Route::get('/kategori/treatmentpackages', function () {
    return view('pages.kategori.treatmentpackages');
});

Route::get('/kategori/alacarte', function () {
    return view('pages.kategori.alacarte');
});

Route::get('/kategori/prewedding', function () {
    return view('pages.kategori.prewedding');
});

Route::get('/contact', function () {
    return view('pages.contact.contact');
});

Route::get('/gallery', function () {
    return view('pages.gallery.gallery');
});

Route::get('/riwayatbooking', function () {
    return view('pages.riwayatbooking.riwayatbooking');
});

Route::get('/riwayatbooking', function () {
    return view('pages.riwayatbooking.riwayatbooking');
});

Route::get('/promo', function () {
    return view('pages.promo.promo');
});

Route::get('/sidebar', function () {
    return view('components.sidebar.sidebar');
});

Route::get('/navbaradmin', function () {
    return view('components.navbar.navbaradmin');
});

Route::get('/layoutadmin', function () {
    return view('components.layout.layoutadmin');
});

Route::get('/dashboard-admin', function () {
    return view('pages.admin.dashboard');
});

Route::get('/manajemen-booking', function () {
    return view('pages.admin.manajemenbooking');
});

Route::get('/input-booking-manual', function () {
    return view('pages.admin.inputbookingmanual');
});

Route::get('/manajemen-therapist', function () {
    return view('pages.admin.manajementherapist');
});

Route::get('/manajemen-paket-treatment', function () {
    return view('pages.admin.manajementreatment');
});

Route::get('/manajemen-promo', function () {
    return view('pages.admin.manajemenpromo');
});

Route::get('/manajemen-pelanggan', function () {
    return view('pages.admin.manajemenpelanggan');
});

Route::get('/manajemen-pembayaran', function () {
    return view('pages.admin.manajemenpembayaran');
});
// Route::get('/navbar', function () {
//     return view('components.navbar.navbar');
// });
// Route::get('/footer', function () {
//     return view('components.footer.footer');
// });
// Route::get('/layout', function () {
//     return view('components.layout.layout');
// });
