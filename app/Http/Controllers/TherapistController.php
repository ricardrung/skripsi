<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class TherapistController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'therapist');

        // Filter opsional
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }


          // PAGINATE langsung tanpa get()
    $therapists = $query->withCount(['bookings' => function ($q) {
        $q->where('status', 'menunggu')->orWhere('status', 'sedang');
    }])->paginate(10);

        return view('pages.admin.manajementherapist', compact('therapists'));
    }

    public function destroy($id)
{
    $therapist = User::findOrFail($id);

    if ($therapist->role !== 'therapist') {
        return redirect()->route('therapist.index')->with('error', 'Data bukan therapist.');
    }

    $therapist->delete();

    return redirect()->route('therapist.index')->with('success', 'Therapist berhasil dihapus.');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:100',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'gender' => 'required|in:male,female',
        'availability' => 'required|in:tersedia,sedang menangani,tidak tersedia',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);



    $photoPath = null;
    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('photos', 'public');
    }

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'phone' => $request->phone,
        'gender' => $request->gender,
        'availability' => $request->availability,
        'role' => 'therapist',
        'email_verified_at' => now(), // agar bisa login tanpa verifikasi manual
        'photo' => $photoPath,
    ]);

    return redirect()->route('therapist.index')->with('success', 'Therapist berhasil ditambahkan.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:100',
        'phone' => 'required|string|max:20',
        'gender' => 'required|in:male,female',
        'availability' => 'required|in:tersedia,sedang menangani,tidak tersedia',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $therapist = User::findOrFail($id);

    if ($therapist->role !== 'therapist') {
        return redirect()->route('therapist.index')->with('error', 'Data bukan therapist.');
    }

    $therapist->update([
        'name' => $request->name,
        'phone' => $request->phone,
        'gender' => $request->gender,
        'availability' => $request->availability,
    ]);

    if ($request->hasFile('photo')) {
    $photoPath = $request->file('photo')->store('photos', 'public');
    $therapist->update(['photo' => $photoPath]);
}

    return redirect()->route('therapist.index')->with('success', 'Therapist berhasil diperbarui.');
}

public function dashboardTherapist()
{
    $therapistId = Auth::id();

    $todayBookings = Booking::with('treatment', 'user')
        ->where('therapist_id', $therapistId)
        ->whereDate('booking_date', now())
        ->orderBy('booking_time')
        ->get();

    $weeklyCount = Booking::where('therapist_id', $therapistId)
        ->whereBetween('booking_date', [now()->startOfWeek(), now()->endOfWeek()])
        ->count();

    $monthlyCount = Booking::where('therapist_id', $therapistId)
        ->whereMonth('booking_date', now()->month)
        ->count();

    $status = Auth::user()->availability ?? 'Tersedia';

  

    $last7Days = now()->subDays(6)->startOfDay();
    $chartData = [];
    $chartLabels = [];

    for ($i = 0; $i < 7; $i++) {
        $date = $last7Days->copy()->addDays($i);
        $count = Booking::where('therapist_id', $therapistId)
            ->whereDate('booking_date', $date)
            ->count();

        $chartLabels[] = $date->format('D');
        $chartData[] = $count;
}


    return view('pages.therapist.dashboard', compact('todayBookings', 'weeklyCount', 'monthlyCount', 'status', 'chartLabels', 'chartData'));
}


}
