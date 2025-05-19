<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;
use App\Models\TreatmentCategory;

class TreatmentManajemenController extends Controller
{
    //
    public function index()
{
    $treatments = Treatment::with('category')->get();
    $categories = TreatmentCategory::all();

    return view('pages.admin.manajementreatment', compact('treatments', 'categories'));
}

public function destroy($id)
{
    $treatment = Treatment::findOrFail($id);
    $treatment->delete();

    return redirect()->back()->with('success', 'Treatment berhasil dihapus!');
}

public function edit($id)
{
    $treatment = Treatment::findOrFail($id);
    return response()->json($treatment);
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'happy_hour_price' => 'nullable|numeric',
        'duration_minutes' => 'required|integer',
        'description' => 'nullable|string',
        'demo_video_url' => 'nullable|string',
        'category_id' => 'required|exists:treatment_categories,id',
        'is_promo' => 'nullable|boolean',
        'is_best_selling' => 'nullable|boolean',
    ]);

    $validated['is_promo'] = $request->input('is_promo') == '1';
    $validated['is_best_selling'] = $request->has('is_best_selling');

    Treatment::where('id', $id)->update($validated);

    return redirect()->back()->with('success', 'Treatment berhasil diperbarui!');
}

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
        'category_id' => 'required|exists:treatment_categories,id',
        'is_best_selling' => 'nullable|boolean',
    ]);

    // Ubah nilai checkbox promo jadi default false jika tidak diceklis
    $validated['is_promo'] = $request->input('is_promo') == '1';

    $validated['is_best_selling'] = $request->has('is_best_selling');

    Treatment::create($validated);

    return redirect()->back()->with('success', 'Paket Treatment berhasil ditambahkan!');
}
}
