<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TreatmentCategory;

class TreatmentController extends Controller
{
    //
    public function index()
    {
        $categories = TreatmentCategory::all();
        return view('pages.admin.manajementreatment', compact('categories'));
    }
}
