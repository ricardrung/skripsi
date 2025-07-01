<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\BlockAdminFromPublic;
use App\Http\Middleware\BlockTherapistFromPublic;
use App\Models\TreatmentCategory;
use App\Http\Controllers\TreatmentManajemenController;
use App\Http\Controllers\TreatmentCategoryController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TherapistController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SpaRoomController;
use App\Http\Controllers\BookingFeedbackController;
use App\Http\Controllers\MembershipController;

// Rute default (bisa untuk semua)
Route::get('/', [TreatmentCategoryController::class, 'index']);

Route::get('/kategori/facetreatment', [TreatmentCategoryController::class, 'faceTreatmentPage']);

Route::get('/kategori/bodytreatment', [TreatmentCategoryController::class, 'bodyTreatmentPage']);

Route::get('/kategori/hairtreatment', [TreatmentCategoryController::class, 'hairTreatmentPage']);

Route::get('/kategori/reflexology', [TreatmentCategoryController::class, 'reflexologyTreatmentPage']);

Route::get('/kategori/treatmentpackages', [TreatmentCategoryController::class, 'packagesTreatmentPage']);

Route::get('/kategori/alacarte', [TreatmentCategoryController::class, 'alacarteTreatmentPage']);
// Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
// Route::post('/api/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
// Route::get('/kategori/prewedding', function () {
//     return view('pages.kategori.prewedding');
// });

Route::get('/contact', function () {
    return view('Pages.Contact.contact');
});

Route::get('/gallery', [SpaRoomController::class, 'gallery'])->name('gallery');


// Rute Customer
Route::middleware(['auth', 'verified', 'customer'])->group(function () {
    Route::get('/dashboard', [TreatmentCategoryController::class, 'index'])->name('dashboard');
    Route::post('/booking/customer', [BookingController::class, 'storeCustomer'])->name('booking.store.customer');
    Route::get('/api/available-therapists', [BookingController::class, 'getAvailableTherapists']);
    Route::get('/api/available-therapists-second', [BookingController::class, 'getAvailableTherapistsForSecondTreatment']);
    Route::get('/riwayatbooking', [BookingController::class, 'riwayatCustomer'])->name('booking.riwayat');
    Route::delete('/riwayatbooking/{id}', [BookingController::class, 'cancelBookingCustomer'])->name('booking.cancel.customer');
    Route::get('/promo', [TreatmentCategoryController::class, 'promoTreatmentPage']);
    Route::get('/booking/pay-again/{id}', [BookingController::class, 'payAgain'])->name('booking.payAgain');
    Route::get('/api/all-room-capacities', [BookingController::class, 'getAllRoomCapacities']);

    Route::post('/feedback', [BookingFeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/membership', [MembershipController::class, 'index'])->name('user.membership');


});

//r Rute Therapist

Route::middleware(['auth', 'verified', 'therapist'])->group(function () {
    Route::get('/dashboard-therapist', [TherapistController::class, 'dashboardTherapist'])->name('therapist.dashboard');
    Route::get('/jadwal-hari-ini', [TherapistController::class, 'scheduleToday'])->name('therapist.schedule.today');
    Route::get('/jadwal-minggu-ini', [TherapistController::class, 'scheduleWeek'])->name('therapist.schedule.week');
    Route::get('/jadwal-bulan-ini', [TherapistController::class, 'scheduleMonth'])->name('therapist.schedule.month');
});



// Rute Admin
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard-admin', [DashboardController::class, 'index'])->name('admin.dashboard');


    Route::get('/manajemen-booking', [BookingController::class, 'index'])->name('booking.admin');
    Route::delete('/manajemen-booking/{id}', [BookingController::class, 'cancelBooking'])->name('booking.cancel');
    Route::patch('/manajemen-booking/{id}/status/{status}', [BookingController::class, 'updateStatus'])->name('booking.updateStatus');
    Route::patch('/manajemen-booking/{id}/bayar', [BookingController::class, 'markAsPaid'])->name('booking.markAsPaid');

    Route::get('/input-booking-manual', [BookingController::class, 'create'])->name('booking.manual');
    Route::post('/booking/admin', [BookingController::class, 'storeAdmin'])->name('booking.store.admin');

    Route::get('/manajemen-therapist', [TherapistController::class, 'index'])->name('therapist.index');
    Route::get('/admin/manajemen-therapist', [TherapistController::class, 'index'])->name('therapist.index');
    Route::delete('/manajemen-therapist/{id}', [TherapistController::class, 'destroy'])->name('therapist.destroy');
    Route::post('/manajemen-therapist/store', [TherapistController::class, 'store'])->name('therapist.store');
    Route::put('/manajemen-therapist/{id}', [TherapistController::class, 'update'])->name('therapist.update');


    Route::get('/manajemen-paket-treatment', [TreatmentManajemenController::class, 'index'])->name('treatments.index');
    Route::post('/manajemen-paket-treatment/store', [TreatmentManajemenController::class, 'store'])->name('treatments.store');
    Route::delete('/treatments/{id}', [TreatmentManajemenController::class, 'destroy'])->name('treatments.destroy');
    Route::get('/treatments/{id}/edit', [TreatmentManajemenController::class, 'edit']);
    Route::put('/treatments/{id}', [TreatmentManajemenController::class, 'update'])->name('treatments.update');
    
    Route::get('/manajemen-ruangan', [SpaRoomController::class, 'index'])->name('spa_rooms.index');
    // Resource lengkap (CRUD)
    Route::resource('spa_rooms', SpaRoomController::class);
 


    Route::get('/manajemen-pelanggan', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/manajemen-pelanggan', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/manajemen-pembayaran', [BookingController::class, 'manajemenPembayaran'])->name('booking.manajemen.pembayaran');
    Route::patch('/booking/{id}/status-bayar', [BookingController::class, 'updateStatusBayar'])->name('booking.update.status.bayar');
    Route::get('/laporan-pemasukan/download', [LaporanController::class, 'downloadCsv'])->name('laporan.pemasukan.download');

    Route::resource('memberships', MembershipController::class);


});


Route::middleware([BlockAdminFromPublic::class, BlockTherapistFromPublic::class])->group(function () {
    Route::get('/', [TreatmentCategoryController::class, 'index']);
    Route::get('/kategori/facetreatment', [TreatmentCategoryController::class, 'faceTreatmentPage']);
    Route::get('/kategori/bodytreatment', [TreatmentCategoryController::class, 'bodyTreatmentPage']);
    Route::get('/kategori/hairtreatment', [TreatmentCategoryController::class, 'hairTreatmentPage']);
    Route::get('/kategori/reflexology', [TreatmentCategoryController::class, 'reflexologyTreatmentPage']);
    Route::get('/kategori/treatmentpackages', [TreatmentCategoryController::class, 'packagesTreatmentPage']);
    Route::get('/kategori/alacarte', [TreatmentCategoryController::class, 'alacarteTreatmentPage']);
    // Route::get('/kategori/prewedding', fn () => view('pages.kategori.prewedding'));
    Route::get('/contact', fn () => view('Pages.Contact.contact'));
    Route::get('/gallery', [SpaRoomController::class, 'gallery'])->name('gallery');
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
