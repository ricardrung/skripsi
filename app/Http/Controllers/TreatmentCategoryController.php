<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class TreatmentCategoryController extends Controller
{
    //
 public function faceTreatmentPage()
{
    $treatments = Treatment::whereHas('category', function ($query) {
        $query->where('slug', 'facetreatment');
    })->get();

    $allTreatments = Treatment::where('is_available', true)->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.kategori.facetreatment', compact('treatments', 'therapists', 'allTreatments'));
}

public function bodyTreatmentPage()
{
    $treatments = Treatment::whereHas('category', function ($query) {
        $query->where('slug', 'bodytreatment');
    })->get();

    $allTreatments = Treatment::where('is_available', true)->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.kategori.bodytreatment', compact('treatments', 'therapists', 'allTreatments'));
}

public function hairTreatmentPage()
{
    $treatments = Treatment::whereHas('category', function ($query) {
        $query->where('slug', 'hairtreatment');
    })->get();

    $allTreatments = Treatment::where('is_available', true)->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.kategori.hairtreatment', compact('treatments', 'therapists', 'allTreatments'));
}

public function reflexologyTreatmentPage()
{
    $treatments = Treatment::whereHas('category', function ($query) {
        $query->where('slug', 'reflexology');
    })->get();

    $allTreatments = Treatment::where('is_available', true)->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.kategori.reflexology', compact('treatments', 'therapists', 'allTreatments'));
}

public function packagesTreatmentPage()
{
    $treatments = Treatment::whereHas('category', function ($query) {
        $query->where('slug', 'treatmentpackages');
    })->get();

    $allTreatments = Treatment::where('is_available', true)->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.kategori.treatmentpackages', compact('treatments', 'therapists', 'allTreatments'));
}

public function alacarteTreatmentPage()
{
    $treatments = Treatment::whereHas('category', function ($query) {
        $query->where('slug', 'alacarte');
    })->get();
    $allTreatments = Treatment::where('is_available', true)->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.kategori.alacarte', compact('treatments', 'therapists', 'allTreatments'));
}

public function promoTreatmentPage()
{
    $userId = Auth::id();

    $jumlahTransaksi = Booking::where('user_id', $userId)
        ->where('status', 'selesai')
        ->count();

    $allTreatments = Treatment::where('is_available', true)->get();

    $claimedPromoCount = Booking::where('user_id', $userId)
        ->where('is_promo_reward', true)
        ->count();

    $treatments = Treatment::where('is_promo', true)->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('pages.promo.promo', compact(
        'treatments',
        'therapists',
        'jumlahTransaksi',
        'claimedPromoCount', 'allTreatments'
    ));
}



public function index()
    {
        $bestSellingTreatments = Treatment::with('category')
            ->where('is_best_selling', true)
            ->limit(3)
            ->get();

        $therapists = User::where('role', 'therapist')->get(); // ambil t

        return view('Pages.Home.home', compact('bestSellingTreatments', 'therapists'));
    }




}
