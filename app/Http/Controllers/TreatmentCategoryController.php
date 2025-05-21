<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;
use App\Models\User;

class TreatmentCategoryController extends Controller
{
    //
 public function faceTreatmentPage()
{
    $treatments = Treatment::whereHas('category', function ($query) {
        $query->where('slug', 'facetreatment');
    })->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.kategori.facetreatment', compact('treatments', 'therapists'));
}

public function bodyTreatmentPage()
{
    $treatments = Treatment::whereHas('category', function ($query) {
        $query->where('slug', 'bodytreatment');
    })->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.kategori.bodytreatment', compact('treatments', 'therapists'));
}

public function hairTreatmentPage()
{
    $treatments = Treatment::whereHas('category', function ($query) {
        $query->where('slug', 'hairtreatment');
    })->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.kategori.hairtreatment', compact('treatments', 'therapists'));
}

public function reflexologyTreatmentPage()
{
    $treatments = Treatment::whereHas('category', function ($query) {
        $query->where('slug', 'reflexology');
    })->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.kategori.reflexology', compact('treatments', 'therapists'));
}

public function packagesTreatmentPage()
{
    $treatments = Treatment::whereHas('category', function ($query) {
        $query->where('slug', 'treatmentpackages');
    })->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.kategori.treatmentpackages', compact('treatments', 'therapists'));
}

public function alacarteTreatmentPage()
{
    $treatments = Treatment::whereHas('category', function ($query) {
        $query->where('slug', 'alacarte');
    })->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.kategori.alacarte', compact('treatments', 'therapists'));
}

public function promoTreatmentPage()
{
    // Jumlah booking user, ganti dengan query riil dari database jika sudah ada
    $jumlahTransaksi = session('jumlah_transaksi', 5); // nanti bisa dari DB

    $treatments = Treatment::where('is_promo', true)->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.promo.promo', compact('treatments', 'therapists', 'jumlahTransaksi'));
}

public function index()
    {
        $bestSellingTreatments = Treatment::with('category')
            ->where('is_best_selling', true)
            ->limit(3)
            ->get();

        return view('pages.home.home', compact('bestSellingTreatments'));
    }


}
