<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\BlockAdminFromPublic;
use App\Models\TreatmentCategory;
use App\Http\Controllers\TreatmentManajemenController;
use App\Http\Controllers\TreatmentCategoryController;
use App\Http\Controllers\BookingController;



// Rute default (bisa untuk semua)
Route::get('/', [TreatmentCategoryController::class, 'index']);

Route::get('/kategori/facetreatment', [TreatmentCategoryController::class, 'faceTreatmentPage']);

Route::get('/kategori/bodytreatment', [TreatmentCategoryController::class, 'bodyTreatmentPage']);

Route::get('/kategori/hairtreatment', [TreatmentCategoryController::class, 'hairTreatmentPage']);

Route::get('/kategori/reflexology', [TreatmentCategoryController::class, 'reflexologyTreatmentPage']);

Route::get('/kategori/treatmentpackages', [TreatmentCategoryController::class, 'packagesTreatmentPage']);

Route::get('/kategori/alacarte', [TreatmentCategoryController::class, 'alacarteTreatmentPage']);

// Route::get('/kategori/prewedding', function () {
//     return view('pages.kategori.prewedding');
// });

Route::get('/contact', function () {
    return view('pages.contact.contact');
});

Route::get('/gallery', function () {
    return view('pages.gallery.gallery');
});


// Rute Customer
Route::middleware(['auth', 'verified', 'customer'])->group(function () {
    Route::get('/dashboard', [TreatmentCategoryController::class, 'index'])->name('dashboard');
    Route::post('/booking/customer', [BookingController::class, 'storeCustomer'])->name('booking.store.customer');
    Route::post('/booking/admin', [BookingController::class, 'storeAdmin'])->name('booking.store.admin');
    Route::get('/api/available-therapists', [BookingController::class, 'getAvailableTherapists']);
    Route::get('/riwayatbooking', [BookingController::class, 'riwayatCustomer'])->name('booking.riwayat');
    Route::delete('/riwayatbooking/{id}', [BookingController::class, 'cancelBooking'])->name('booking.cancel');
    Route::get('/promo', [TreatmentCategoryController::class, 'promoTreatmentPage']);
});

//r Rute Therapist

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard-therapist', function () {
        if (Auth::user()->role !== 'therapist') {
            abort(403);
        }
        return view('pages.therapist.dashboard');
    });
});

// Rute Admin
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard-admin', fn () => view('pages.admin.dashboard'));
    Route::get('/manajemen-booking', [BookingController::class, 'index'])->name('booking.admin');
    Route::get('/input-booking-manual', [BookingController::class, 'create'])->name('booking.manual');
    Route::get('/manajemen-therapist', fn () => view('pages.admin.manajementherapist'));
    Route::get('/manajemen-paket-treatment', [TreatmentManajemenController::class, 'index'])->name('treatments.index');
    Route::post('/manajemen-paket-treatment/store', [TreatmentManajemenController::class, 'store'])->name('treatments.store');
    Route::delete('/treatments/{id}', [TreatmentManajemenController::class, 'destroy'])->name('treatments.destroy');
    Route::get('/treatments/{id}/edit', [TreatmentManajemenController::class, 'edit']);
    Route::put('/treatments/{id}', [TreatmentManajemenController::class, 'update'])->name('treatments.update');
    Route::get('/manajemen-promo', fn () => view('pages.admin.manajemenpromo'));
    Route::get('/manajemen-pelanggan', fn () => view('pages.admin.manajemenpelanggan'));
    Route::get('/manajemen-pembayaran', fn () => view('pages.admin.manajemenpembayaran'));
});


Route::middleware(BlockAdminFromPublic::class)->group(function () {
    Route::get('/', [TreatmentCategoryController::class, 'index']);
    Route::get('/kategori/facetreatment', [TreatmentCategoryController::class, 'faceTreatmentPage']);
    Route::get('/kategori/bodytreatment', [TreatmentCategoryController::class, 'bodyTreatmentPage']);
    Route::get('/kategori/hairtreatment', [TreatmentCategoryController::class, 'hairTreatmentPage']);
    Route::get('/kategori/reflexology', [TreatmentCategoryController::class, 'reflexologyTreatmentPage']);
    Route::get('/kategori/treatmentpackages', [TreatmentCategoryController::class, 'packagesTreatmentPage']);
    Route::get('/kategori/alacarte', [TreatmentCategoryController::class, 'alacarteTreatmentPage']);
    // Route::get('/kategori/prewedding', fn () => view('pages.kategori.prewedding'));
    Route::get('/contact', fn () => view('pages.contact.contact'));
    Route::get('/gallery', fn () => view('pages.gallery.gallery'));
});

// Route::get('/dashboard1', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard1');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Route::get('/riwayatbooking', function () {
//     return view('pages.riwayatbooking.riwayatbooking');
// });


// Route::get('/sidebar', function () {
//     return view('components.sidebar.sidebar');
// });

// Route::get('/navbaradmin', function () {
//     return view('components.navbar.navbaradmin');
// });

// Route::get('/layoutadmin', function () {
//     return view('components.layout.layoutadmin');
// });

// Route::get('/dashboard-admin', function () {
//     return view('pages.admin.dashboard');
// });

// Route::get('/manajemen-booking', function () {
//     return view('pages.admin.manajemenbooking');
// });

// Route::get('/input-booking-manual', function () {
//     return view('pages.admin.inputbookingmanual');
// });


// Route::get('/navbar', function () {
//     return view('components.navbar.navbar');
// });
// Route::get('/footer', function () {
//     return view('components.footer.footer');
// });
// Route::get('/layout', function () {
//     return view('components.layout.layout');
// });
