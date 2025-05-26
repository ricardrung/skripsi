<?php

namespace App\Http\Controllers;

use App\Models\SpaRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpaRoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = SpaRoom::latest()->paginate(10);
        return view('pages.admin.manajemenruangan', compact('rooms'));
    }
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'room_type' => 'required|string|max:255',
            'capacity'  => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('spa_rooms', 'public');
        }

        $validated['is_active'] = $request->input('is_active', false);
        $validated['slug'] = Str::slug($validated['room_name']) . '-' . Str::random(5);

        SpaRoom::create($validated);

        return redirect()->route('spa_rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $room = SpaRoom::findOrFail($id);
        return response()->json($room);
    }

    public function update(Request $request, $id)
    {
        $room = SpaRoom::findOrFail($id);

        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'room_type' => 'required|string|max:255',
            'capacity'  => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($room->image_path) {
                Storage::disk('public')->delete($room->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('spa_rooms', 'public');
        }

        $validated['is_active'] = $request->input('is_active', false);

        $room->update($validated);

        return redirect()->route('spa_rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $room = SpaRoom::findOrFail($id);
        if ($room->image_path) {
            Storage::disk('public')->delete($room->image_path);
        }
        $room->delete();

        return redirect()->route('spa_rooms.index')->with('success', 'Ruangan berhasil dihapus.');
    }

        public function gallery()
    {
        $rooms = SpaRoom::where('is_active', true)->get();
        return view('pages.gallery.gallery', compact('rooms'));
    }

}
