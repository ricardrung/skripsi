<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
    //
public function index(Request $request)
{
    $query = Booking::with(['treatment.category', 'therapist', 'user', 'creator']);

    // 🔍 Filter berdasarkan nama layanan (search)
    if ($request->filled('search')) {
        $query->whereHas('treatment', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    // 🔍 Filter berdasarkan status booking
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 🔍 Filter tanggal mulai & akhir
    if ($request->filled('start_date')) {
        $query->whereDate('booking_date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('booking_date', '<=', $request->end_date);
    }

    $bookings = $query->orderBy('booking_date', 'desc')->paginate(10);

    // Kalau butuh kategori untuk dropdown kategori (meskipun tidak terlihat di Blade kamu sekarang)
    $categories = \App\Models\TreatmentCategory::pluck('name', 'id');

    return view('pages.admin.manajemenbooking', compact('bookings', 'categories'));
}


    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        $treatments = Treatment::all();
        $therapists = User::where('role', 'therapist')->get();

        return view('pages.admin.inputbookingmanual', compact('customers', 'treatments', 'therapists'));
    }


    // Digunakan saat customer melakukan booking dari halaman depan (misalnya modal popup)
public function storeCustomer(Request $request)
{
    $request->validate([
        'treatment_id' => 'required|exists:treatments,id',
        'booking_date' => 'required|date',
        'booking_time' => 'required',
        'therapist_id' => 'nullable|exists:users,id',
        'payment_method' => 'required|in:cash,gateway',
    ]);

    //waktu
    $bookingDateTime = Carbon::parse($request->booking_date . ' ' . $request->booking_time);
    if ($bookingDateTime->isPast()) {
        return back()->with('error', 'Tidak bisa booking di waktu yang sudah lewat.');
    }
    if ($bookingDateTime->gt(now()->addDays(7))) {
        return back()->with('error', 'Booking hanya bisa dilakukan maksimal 7 hari ke depan.');
    }

    $treatment = Treatment::where('id', $request->treatment_id)->where('is_available', true)->first();
        if (!$treatment) {
            return back()->with('error', 'Treatment ini tidak tersedia untuk saat ini.');
        }

    $startTime = Carbon::parse($request->booking_time);
    $endTime = $startTime->copy()->addMinutes($treatment->duration_minutes);

    if (empty($request->therapist_id)) {
        $availableTherapists = User::where('role', 'therapist')->get()->filter(function ($therapist) use ($request, $startTime, $endTime) {
            return !Booking::where('therapist_id', $therapist->id)
                ->where('booking_date', $request->booking_date)
                ->where('status', '!=', 'batal')
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('booking_time', [$startTime, $endTime->copy()->subMinute()])
                          ->orWhereRaw('? BETWEEN booking_time AND ADDTIME(booking_time, SEC_TO_TIME(duration_minutes * 60))', [$startTime->format('H:i:s')]);
                })
                ->exists();
        });

        if ($availableTherapists->isEmpty()) {
            return back()->with('error', 'Semua therapist sudah dibooking di waktu ini.');
        }

        $bookingCounts = Booking::where('booking_date', $request->booking_date)
            ->whereIn('therapist_id', $availableTherapists->pluck('id'))
            ->select('therapist_id', DB::raw('count(*) as total'))
            ->groupBy('therapist_id')
            ->pluck('total', 'therapist_id');

        $sorted = $availableTherapists->sortBy(fn($t) => $bookingCounts[$t->id] ?? 0);
        $request->merge(['therapist_id' => $sorted->first()->id]);
    } else {
        $isOverlap = Booking::where('therapist_id', $request->therapist_id)
            ->where('booking_date', $request->booking_date)
            ->where('status', '!=', 'batal')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('booking_time', [$startTime, $endTime->copy()->subMinute()])
                      ->orWhereRaw('? BETWEEN booking_time AND ADDTIME(booking_time, SEC_TO_TIME(duration_minutes * 60))', [$startTime->format('H:i:s')]);
            })
            ->exists();

        if ($isOverlap) {
            return back()->with('error', 'Therapist yang Anda pilih sudah memiliki jadwal bentrok.');
        }
    }

    $isHappyHour = $treatment->happy_hour_price &&
        in_array(Carbon::parse($request->booking_date)->dayOfWeek, [1, 2, 3, 4, 5]) &&
        Carbon::parse($request->booking_time)->between(
            Carbon::createFromTimeString('10:00:00'),
            Carbon::createFromTimeString('13:00:00')
        );

        // Cek jika promo reward
    $isPromoReward = $request->input('is_promo_reward') == true;

    if ($isPromoReward) {
        $bookingCount = Booking::where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->count();

        $eligiblePromoCount = floor($bookingCount / 5);

        $claimedPromoCount = Booking::where('user_id', Auth::id())
            ->where('is_promo_reward', true)
            ->count();

        if ($eligiblePromoCount <= $claimedPromoCount) {
            return back()->withErrors(['Anda belum memenuhi syarat untuk promo gratis.']);
        }
    }

    $finalPrice = $treatment->price;

    if ($isPromoReward) {
        $finalPrice = 0;
    } elseif ($isHappyHour) {
        $finalPrice = $treatment->happy_hour_price;
    }

        // 1. Ambil treatment & room_type-nya
    $treatment = Treatment::findOrFail($request->treatment_id);
    $roomType = $treatment->room_type;

    // 2. Ambil kapasitas maksimal ruangan dari spa_rooms
    $maxCapacity = \App\Models\SpaRoom::where('room_type', $roomType)->sum('capacity');

    // 3. Hitung jumlah booking yang sudah dilakukan pada waktu yang sama
    $existingBookingCount = \App\Models\Booking::where('booking_date', $request->booking_date)
        ->where('booking_time', $request->booking_time)
        ->whereHas('treatment', function ($query) use ($roomType) {
            $query->where('room_type', $roomType);
        })->count();

    // 4. Cek kapasitas
    if ($existingBookingCount >= $maxCapacity) {
        return back()->with('error', 'Maaf, semua ruangan untuk treatment ini penuh pada waktu tersebut.');
    }



    Booking::create([
        'user_id' => Auth::id(),
        'guest_name' => null,
        'guest_phone' => null,
        'treatment_id' => $request->treatment_id,
        'therapist_id' => $request->therapist_id,
        'created_by' => Auth::id(),
        'booking_date' => $request->booking_date,
        'booking_time' => $request->booking_time,
        'duration_minutes' => $treatment->duration_minutes,
        'original_price' => $treatment->price,
        'final_price' => $finalPrice,
        'is_happy_hour' => $isHappyHour,
        'is_promo_reward' => $isPromoReward,
        'payment_method' => $request->payment_method,
        'payment_status' => 'belum_bayar',
        'status' => 'menunggu',
        'note' => $request->note,
    ]);

     //payment gateway 
        $booking = Booking::latest()->first();

    if ($booking->payment_method === 'gateway') {
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = config('midtrans.isSanitized');
        Config::$is3ds = config('midtrans.is3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'BOOKING-' . $booking->id,
                'gross_amount' => (int)$booking->final_price,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email ?? 'guest@example.com',
                'phone' => Auth::user()->phone ?? '08123456789',
            ],
            'callbacks' => [
                'finish' => route('booking.riwayat'),
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('pages.booking.snap', compact('snapToken'));
    }

    return redirect()->route('booking.riwayat')->with('success', 'Booking berhasil ditambahkan.');
}


// Digunakan saat admin input booking secara manual dari halaman admin
public function storeAdmin(Request $request)
{
    $request->validate([
        'treatment_id' => 'required|exists:treatments,id',
        'booking_date' => 'required|date',
        'booking_time' => 'required',
        'therapist_id' => 'nullable|exists:users,id',
        'payment_method' => 'required|in:cash,gateway',
        'user_id' => 'nullable|exists:users,id',
        'guest_name' => 'nullable|string',
        'guest_phone' => 'nullable|string',
    ]);

      //waktu
$bookingDateTime = Carbon::parse($request->booking_date . ' ' . $request->booking_time);
if ($bookingDateTime->isPast()) {
    return back()->with('error', 'Tidak bisa booking di waktu yang sudah lewat.');
}
if ($bookingDateTime->gt(now()->addDays(7))) {
    return back()->with('error', 'Booking hanya bisa dilakukan maksimal 7 hari ke depan.');
}

    if (!$request->user_id && (!$request->guest_name || !$request->guest_phone)) {
        return back()->with('error', 'Harap pilih customer atau isi data tamu guest.');
    }

    //Jika Treatment tidak tersedia
    $treatment = Treatment::where('id', $request->treatment_id)->where('is_available', true)->first();
        if (!$treatment) {
            return back()->with('error', 'Treatment ini tidak tersedia untuk saat ini.');
        }

    $startTime = Carbon::parse($request->booking_time);
    $endTime = $startTime->copy()->addMinutes($treatment->duration_minutes);

    if (empty($request->therapist_id)) {
        $availableTherapists = User::where('role', 'therapist')->get()->filter(function ($therapist) use ($request, $startTime, $endTime) {
            return !Booking::where('therapist_id', $therapist->id)
                ->where('booking_date', $request->booking_date)
                ->where('status', '!=', 'batal')
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('booking_time', [$startTime, $endTime->copy()->subMinute()])
                          ->orWhereRaw('? BETWEEN booking_time AND ADDTIME(booking_time, SEC_TO_TIME(duration_minutes * 60))', [$startTime->format('H:i:s')]);
                })
                ->exists();
        });

        if ($availableTherapists->isEmpty()) {
            return back()->with('error', 'Semua therapist sudah penuh pada waktu ini.');
        }

        $bookingCounts = Booking::where('booking_date', $request->booking_date)
            ->whereIn('therapist_id', $availableTherapists->pluck('id'))
            ->select('therapist_id', DB::raw('count(*) as total'))
            ->groupBy('therapist_id')
            ->pluck('total', 'therapist_id');

        $sorted = $availableTherapists->sortBy(fn($t) => $bookingCounts[$t->id] ?? 0);
        $request->merge(['therapist_id' => $sorted->first()->id]);
    } else {
        $isOverlap = Booking::where('therapist_id', $request->therapist_id)
            ->where('booking_date', $request->booking_date)
            ->where('status', '!=', 'batal')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('booking_time', [$startTime, $endTime->copy()->subMinute()])
                      ->orWhereRaw('? BETWEEN booking_time AND ADDTIME(booking_time, SEC_TO_TIME(duration_minutes * 60))', [$startTime->format('H:i:s')]);
            })
            ->exists();

        if ($isOverlap) {
            return back()->with('error', 'Therapist sudah memiliki booking bentrok di waktu tersebut.');
        }
    }

    $isHappyHour = $treatment->happy_hour_price &&
        in_array(Carbon::parse($request->booking_date)->dayOfWeek, [1, 2, 3, 4, 5]) &&
        Carbon::parse($request->booking_time)->between(
            Carbon::createFromTimeString('10:00:00'),
            Carbon::createFromTimeString('13:00:00')
        );

     // 1. Ambil treatment & room_type-nya
    $treatment = Treatment::findOrFail($request->treatment_id);
    $roomType = $treatment->room_type;

    // 2. Ambil kapasitas maksimal ruangan dari spa_rooms
    $maxCapacity = \App\Models\SpaRoom::where('room_type', $roomType)->sum('capacity');

    // 3. Hitung jumlah booking yang sudah dilakukan pada waktu yang sama
    $existingBookingCount = \App\Models\Booking::where('booking_date', $request->booking_date)
        ->where('booking_time', $request->booking_time)
        ->whereHas('treatment', function ($query) use ($roomType) {
            $query->where('room_type', $roomType);
        })->count();

    // 4. Cek kapasitas
    if ($existingBookingCount >= $maxCapacity) {
        return back()->with('error', 'Maaf, semua ruangan untuk treatment ini penuh pada waktu tersebut.');
    }


    Booking::create([
        'user_id' => $request->user_id,
        'guest_name' => $request->guest_name,
        'guest_phone' => $request->guest_phone,
        'treatment_id' => $request->treatment_id,
        'therapist_id' => $request->therapist_id,
        'created_by' => Auth::id(),
        'booking_date' => $request->booking_date,
        'booking_time' => $request->booking_time,
        'duration_minutes' => $treatment->duration_minutes,
        'original_price' => $treatment->price,
        'final_price' => $isHappyHour ? $treatment->happy_hour_price : $treatment->price,
        'is_happy_hour' => $isHappyHour,
        'is_promo_reward' => false,
        'payment_method' => $request->payment_method,
        'payment_status' => 'belum_bayar',
        'status' => 'menunggu',
        'note' => $request->note,
    ]);

    return redirect()->route('booking.admin')->with('success', 'Booking berhasil ditambahkan.');
}


public function getAvailableTherapists(Request $request)
{
    $tanggal = $request->tanggal;
    $jam = $request->jam;
    $treatmentId = $request->treatment_id;

    if (!$tanggal || !$jam || !$treatmentId) {
        return response()->json(['error' => 'Tanggal, jam, dan treatment wajib diisi'], 400);
    }

    $treatment = Treatment::find($treatmentId);
    if (!$treatment) {
        return response()->json(['error' => 'Treatment tidak ditemukan'], 404);
    }

    $startTime = Carbon::parse($jam);
    $endTime = $startTime->copy()->addMinutes($treatment->duration_minutes);

    // Filter therapist yang tidak bentrok di waktu tersebut
    $therapists = User::where('role', 'therapist')->get()->filter(function ($therapist) use ($tanggal, $startTime, $endTime) {
        return !Booking::where('therapist_id', $therapist->id)
            ->where('booking_date', $tanggal)
            ->where('status', '!=', 'batal')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('booking_time', [$startTime, $endTime->copy()->subMinute()])
                      ->orWhereRaw('? BETWEEN booking_time AND ADDTIME(booking_time, SEC_TO_TIME(duration_minutes * 60))', [$startTime->format('H:i:s')]);
            })
            ->exists();
    });

    // Hitung jumlah booking mereka di hari itu
    $bookingCount = Booking::where('booking_date', $tanggal)
        ->whereIn('therapist_id', $therapists->pluck('id'))
        ->select('therapist_id', DB::raw('count(*) as total'))
        ->groupBy('therapist_id')
        ->pluck('total', 'therapist_id');

    return response()->json([
        'therapists' => $therapists->values(), // reset key agar JSON valid
        'booking_count' => $bookingCount,
    ]);
}


public function riwayatCustomer(Request $request)
{
    $query = Booking::with(['treatment', 'therapist'])
        ->where('user_id', Auth::id());

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
        $query->whereHas('treatment', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('start_date')) {
        $query->whereDate('booking_date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('booking_date', '<=', $request->end_date);
    }

    $order = $request->input('order', 'desc');
    $query->orderBy('booking_date', $order)->orderBy('booking_time', $order);

    $bookings = $query->paginate(8)->appends(request()->query());

    return view('pages.riwayatbooking.riwayatbooking', compact('bookings'));
}


public function cancelBookingCustomer($id)
{
    $booking = Booking::where('id', $id)
        ->where('user_id', Auth::id())
        ->where('status', 'menunggu')
        ->firstOrFail();

    $booking->update([
    'status' => 'batal',
    'canceled_at' => now(),
    'cancellation_reason' => 'Dibatalkan oleh customer',
]);

    return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
}

public function cancelBooking($id)
{
    $booking = Booking::where('id', $id)
        ->where('status', 'menunggu')
        ->firstOrFail();

    $booking->update([
        'status' => 'batal',
        'canceled_at' => now(),
        'cancellation_reason' => 'Dibatalkan oleh admin',
    ]);

    return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
}

public function updateStatus($id, $status)
{
    $booking = Booking::with('therapist')->findOrFail($id);

    if (!in_array($status, ['sedang', 'selesai'])) {
        return back()->with('error', 'Status tidak valid.');
    }

    // Update status booking
    $booking->update(['status' => $status]);

    // Update availability therapist (jika ada therapist-nya)
    if ($booking->therapist) {
        if ($status === 'sedang') {
            $booking->therapist->update(['availability' => 'sedang menangani']);
        }

        if ($status === 'selesai') {
            $booking->therapist->update(['availability' => 'tersedia']);
        }
    }

    return back()->with('success', "Status booking diubah menjadi '$status'.");
}


public function markAsPaid($id)
{
    $booking = Booking::findOrFail($id);

    $booking->update(['payment_status' => 'sudah_bayar']);

    return back()->with('success', 'Status pembayaran ditandai sebagai lunas.');
}

public function manajemenPembayaran(Request $request)
{
    $query = Booking::with(['user', 'treatment'])
        ->orderBy('created_at', 'desc');

    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('payment_status')) {
        $query->where('payment_status', $request->payment_status);
    }

    $bookings = $query->paginate(10);

    return view('pages.admin.manajemenpembayaran', compact('bookings'));
}

public function updateStatusBayar($id)
{
    $booking = Booking::findOrFail($id);
    $booking->payment_status = 'sudah_bayar';
    $booking->save();

    return redirect()->back()->with('success', 'Status pembayaran diperbarui!');
}




}
