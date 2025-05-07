<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;

class TreatmentManajemenController extends Controller
{
    //
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'happy_hour_price' => 'nullable|numeric',
        'duration_minutes' => 'required|integer',
        'demo_video_url' => 'nullable|string',
        'is_promo' => 'nullable|boolean',
        'promo_required_bookings' => 'nullable|integer',
        'category_id' => 'required|exists:treatment_categories,id',
    ]);

    // Ubah nilai checkbox promo jadi default false jika tidak diceklis
    $validated['is_promo'] = $request->has('is_promo');

    Treatment::create($validated);

    return redirect()->back()->with('success', 'Paket Treatment berhasil ditambahkan!');
}
}
