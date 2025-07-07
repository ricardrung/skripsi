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
    
    $membership = Auth::check() ? Auth::user()->membership : null;
    return view('Pages.Kategori.facetreatment', compact('treatments', 'therapists', 'allTreatments', 'membership'));
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

    $membership = Auth::check() ? Auth::user()->membership : null;
    return view('Pages.Kategori.bodytreatment', compact('treatments', 'therapists', 'allTreatments', 'membership'));
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

    $membership = Auth::check() ? Auth::user()->membership : null;
    return view('Pages.Kategori.hairtreatment', compact('treatments', 'therapists', 'allTreatments', 'membership'));
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

    $membership = Auth::check() ? Auth::user()->membership : null;
    return view('Pages.Kategori.reflexology', compact('treatments', 'therapists', 'allTreatments', 'membership'));
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

    $membership = Auth::check() ? Auth::user()->membership : null;
    return view('Pages.Kategori.treatmentpackages', compact('treatments', 'therapists', 'allTreatments', 'membership'));
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

    $membership = Auth::check() ? Auth::user()->membership : null;
    return view('Pages.Kategori.alacarte', compact('treatments', 'therapists', 'allTreatments', 'membership'));
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
        ->where('status', '!=', 'batal')
        ->count();

    $treatments = Treatment::where('is_promo', true)->get();

    $therapists = User::where('role', 'therapist')
        ->where('availability', 'tersedia')
        ->get();

    return view('Pages.Promo.promo', compact(
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
