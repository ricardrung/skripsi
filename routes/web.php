<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\BlockAdminFromPublic;
use App\Models\TreatmentCategory;
use App\Http\Controllers\TreatmentManajemenController;

// Rute default (bisa untuk semua)
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


// Rute Customer
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role !== 'customer') {
            abort(403);
        }
        return view('pages.home.home');
    })->name('dashboard');

    Route::get('/riwayatbooking', function () {
        if (Auth::user()->role !== 'customer') {
            abort(403);
        }
        return view('pages.riwayatbooking.riwayatbooking');
    });

    Route::get('/promo', function () {
        if (Auth::user()->role !== 'customer') {
            abort(403);
        }
        return view('pages.promo.promo');
    });
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
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard-admin', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        return view('pages.admin.dashboard');
    });

    Route::get('/manajemen-booking', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        return view('pages.admin.manajemenbooking');
    });

    Route::get('/input-booking-manual', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        return view('pages.admin.inputbookingmanual');
    });

    Route::get('/manajemen-therapist', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        return view('pages.admin.manajementherapist');
    });
    
    Route::get('/manajemen-paket-treatment', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $categories = TreatmentCategory::all(); // ambil semua kategori dari DB
        return view('pages.admin.manajementreatment', compact('categories'));
    });
    Route::post('/manajemen-paket-treatment/store', [TreatmentManajemenController::class, 'store'])->name('treatments.store');
    
    Route::get('/manajemen-promo', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        return view('pages.admin.manajemenpromo');
    });
    
    Route::get('/manajemen-pelanggan', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        return view('pages.admin.manajemenpelanggan');
    });
    
    Route::get('/manajemen-pembayaran', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        return view('pages.admin.manajemenpembayaran');
    });
});


Route::middleware(BlockAdminFromPublic::class)->group(function () {
    Route::get('/', fn () => view('pages.home.home'));
    Route::get('/kategori/facetreatment', fn () => view('pages.kategori.facetreatment'));
    Route::get('/kategori/bodytreatment', fn () => view('pages.kategori.bodytreatment'));
    Route::get('/kategori/hairtreatment', fn () => view('pages.kategori.hairtreatment'));
    Route::get('/kategori/reflexology', fn () => view('pages.kategori.reflexology'));
    Route::get('/kategori/treatmentpackages', fn () => view('pages.kategori.treatmentpackages'));
    Route::get('/kategori/alacarte', fn () => view('pages.kategori.alacarte'));
    Route::get('/kategori/prewedding', fn () => view('pages.kategori.prewedding'));
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
