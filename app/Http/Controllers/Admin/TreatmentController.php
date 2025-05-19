<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TreatmentCategory;
use App\Models\Treatment;

class TreatmentController extends Controller
{
    // GAK DIPAKE LAGIE PERTAMA RENCANANYA MAU BUAT DISINI TAPI UDAH BUAT DI TREATMENTMANAJEMENCONTROLLER 
    //
    // public function index()
    // {
    //     $treatments = Treatment::with('category')->get(); // ambil relasi kategori juga
    //     $categories = TreatmentCategory::all(); // untuk dropdown form
    //     return view('pages.admin.manajementreatment', compact('treatments', 'categories'));
        
    // }
}
