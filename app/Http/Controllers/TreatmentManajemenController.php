<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\SpaRoom;


class TreatmentManajemenController extends Controller
{
    //
public function index(Request $request)
{
    $query = Treatment::with('category');

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    $order = $request->order ?? 'asc';
    $query->orderBy('created_at', $order);

    $treatments = $query->paginate(10); // pagination 10 per halaman
    $categories = TreatmentCategory::all();

    $roomTypes = SpaRoom::select('room_type')->distinct()->pluck('room_type');

    return view('pages.admin.manajementreatment', compact('treatments', 'categories', 'roomTypes'));
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

    return response()->json([
        'id' => $treatment->id,
        'name' => $treatment->name,
        'category_id' => $treatment->category_id,
        'price' => $treatment->price,
        'happy_hour_price' => $treatment->happy_hour_price,
        'duration_minutes' => $treatment->duration_minutes,
        'description' => $treatment->description,
        'demo_video_url' => $treatment->demo_video_url,
        'is_promo' => (bool) $treatment->is_promo,
        'is_best_selling' => (bool) $treatment->is_best_selling,
        'is_available' => (bool) $treatment->is_available,
        'room_type' => $treatment->room_type,
    ]);
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
        'is_available' => 'nullable|boolean',
        'room_type' => 'required|string',
    ]);

    $validated['is_promo'] = $request->input('is_promo') == '1';
    $validated['is_best_selling'] = $request->input('is_best_selling') == '1';
    $validated['is_available'] = $request->input('is_available') == '1';
    $validated['room_type'] = $request->room_type;



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
        'is_available' => 'nullable|boolean',
        'room_type' => 'required|string',
    ]);

    // Ubah nilai checkbox promo jadi default false jika tidak diceklis
    $validated['is_promo'] = $request->input('is_promo') == '1';

    $validated['is_best_selling'] = $request->input('is_best_selling') == '1';

    $validated['is_available'] = $request->input('is_available') == '1';

    $validated['room_type'] = $request->room_type;



    Treatment::create($validated);

    return redirect()->back()->with('success', 'Paket Treatment berhasil ditambahkan!');
}
}
