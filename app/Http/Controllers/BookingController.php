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
use App\Services\MembershipService;



class BookingController extends Controller
{
    //
public function index(Request $request)
{
    $query = Booking::with(['treatment.category', 'therapist', 'user', 'creator', 'feedback']);
    $creators = User::select('id', 'name')->get();

    // 🔍 Filter berdasarkan nama layanan (search)
    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
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

    if ($request->order === 'id_asc') {
    $query->orderBy('id');
    } elseif ($request->order === 'id_desc') {
        $query->orderByDesc('id');
    }

    if ($request->filled('creator_id')) {
    $query->where('created_by', $request->creator_id); 
    }

    if ($request->filled('min_rating')) {
    $query->whereHas('feedback', function ($q) use ($request) {
        $q->where('rating', '>=', (int) $request->min_rating);
    });
    }

    


    $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

    // Kalau butuh kategori untuk dropdown kategori (meskipun tidak terlihat di Blade kamu sekarang)
    $categories = \App\Models\TreatmentCategory::pluck('name', 'id');

    return view('Pages.Admin.manajemenbooking', compact('bookings', 'categories', 'creators'));
}


    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        $treatments = Treatment::all();
        $therapists = User::where('role', 'therapist')->whereIn('availability', ['tersedia', 'sedang menangani'])->get();
        $allTreatments = Treatment::where('category_id', '!=', 7)->get();


        return view('Pages.Admin.inputbookingmanual', compact('customers', 'treatments', 'therapists', 'allTreatments'));
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
        'room_type' => 'required|in:single,double,hair,reflexology',
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

    // therapist pilihkan untuk saya
    if (empty($request->therapist_id)) {
        $availableTherapists = User::where('role', 'therapist')->whereIn('availability', ['tersedia', 'sedang menangani'])->get()->filter(function ($therapist) use ($request, $startTime, $endTime) {
            return !Booking::where('therapist_id', $therapist->id)
                ->where('booking_date', $request->booking_date)
               ->whereIn('status', ['menunggu', 'sedang'])
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
          ->whereIn('status', ['menunggu', 'sedang'])
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
            ->where('status', '!=', 'batal')
            ->count();

        if ($eligiblePromoCount <= $claimedPromoCount) {
            return back()->withErrors(['Anda belum memenuhi syarat untuk promo gratis.']);
        }
    }

    // $basePrice = $treatment->price;
    // if ($isPromoReward) {
    //     $finalPrice = 0;
    // } elseif ($isHappyHour) {
    //     $basePrice = $treatment->happy_hour_price;
    // }

    // // 💸 Hitung diskon membership
    // $discountPercent = app(MembershipService::class)->getUserDiscount(Auth::user(), $treatment->category->name ?? '');
    // $finalPrice = $basePrice - ($basePrice * $discountPercent / 100);
    $basePrice = $treatment->price;
    if ($isPromoReward) {
        // Promo reward = gratis tanpa diskon membership
        $finalPrice = 0;
    } else {
        if ($isHappyHour) {
            $basePrice = $treatment->happy_hour_price;
        }

        // 💸 Hitung diskon membership hanya jika bukan promo reward
        $discountPercent = app(MembershipService::class)->getUserDiscount(Auth::user(), $treatment->category->name ?? '');
        $finalPrice = $basePrice - ($basePrice * $discountPercent / 100);
    }



        // 1. Ambil treatment & room_type-nya
    $treatment = Treatment::findOrFail($request->treatment_id);
    $roomType = $request->room_type;

    // 2. Ambil kapasitas maksimal ruangan dari spa_rooms
    $maxCapacity = \App\Models\SpaRoom::where('room_type', $roomType)->sum('capacity');

    // 3. Hitung jumlah booking yang sudah dilakukan pada waktu yang sama(ruangan)
    $existingBookingCount = \App\Models\Booking::where('booking_date', $request->booking_date)
        ->where('booking_time', $request->booking_time)
        ->where('room_type', $roomType) 
        // ->where('status', '!=', 'batal')
        ->whereIn('status', ['menunggu', 'sedang']) 
        ->whereHas('treatment', function ($query) use ($roomType) {
            $query->where('room_type', $roomType)
                ->orWhere('room_type', 'both');
        })    ->count();

    // 4. Cek kapasitas
    if ($existingBookingCount >= $maxCapacity) {
        return back()->with('error', 'Maaf, semua ruangan untuk treatment ini penuh pada waktu tersebut.');
    }
    // Validasi tambahan jika treatment tidak cocok dengan room_type yang dipilih user
    if (!in_array($request->room_type, ['single', 'double', 'hair', 'reflexology'])) {
        return back()->with('error', 'Tipe ruangan tidak valid.');
    }

    // Mapping fleksibel room_type treatment → daftar room real di spa_rooms
    $roomMapping = [
        'single' => ['single'],
        'double' => ['double'],
        'both' => ['single', 'double'],
        'hair' => ['hair'],
        'reflexology' => ['reflexology'],
        'triple' => ['single', 'double', 'reflexology'],
    ];

    // Pastikan room_type yang diminta user sesuai isi SpaRoom
    $roomType = $request->room_type;
    $allowedRoomTypes = $roomMapping[$treatment->room_type] ?? [];

    if (!in_array($roomType, $allowedRoomTypes)) {
        return back()->with('error', 'Treatment ini tidak tersedia untuk tipe ruangan yang dipilih.');
    }

    // if ($treatment->room_type !== 'both' && $treatment->room_type !== $request->room_type) {
    //     return back()->with('error', 'Treatment ini tidak tersedia untuk tipe ruangan yang dipilih.');
    // }

DB::beginTransaction(); // ✅ Mulai transaksi

    try {
    $secondTherapistId = $request->second_therapist_id;
    if ($request->room_type === 'double') {
    if (
        $request->therapist_id &&
        $secondTherapistId &&
        $request->therapist_id == $secondTherapistId
    ) {
        throw new \Exception('Therapist pertama dan kedua harus berbeda untuk ruangan double.');
    }
}
$createdBookingIds = [];

    $booking1 = Booking::create([
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
        'room_type' => $request->room_type,
    ]);
    $createdBookingIds[] = $booking1->id;
// booking kedua
    if ($request->room_type === 'double') {
        if (empty($request->second_treatment_id)) {
            throw new \Exception('Harap pilih treatment kedua untuk ruangan double.');
        }

    // Cek apakah treatment kedua dipilih
    $treatment2 = Treatment::findOrFail($request->second_treatment_id);

    $isHappyHour2 = $treatment2->happy_hour_price &&
        in_array(Carbon::parse($request->booking_date)->dayOfWeek, [1, 2, 3, 4, 5]) &&
        Carbon::parse($request->booking_time)->between(
            Carbon::createFromTimeString('10:00:00'),
            Carbon::createFromTimeString('13:00:00')
        );

    $basePrice2 = $treatment2->price;
    if ($isHappyHour2) {
        $basePrice2 = $treatment2->happy_hour_price;
    }

    $discountPercent2 = app(MembershipService::class)->getUserDiscount(Auth::user(), $treatment2->category->name ?? '');
    $finalPrice2 = $basePrice2 - ($basePrice2 * $discountPercent2 / 100);


    $secondTherapistId = $request->second_therapist_id;


    // Jika therapist kedua tidak dipilih
    if (empty($secondTherapistId)) {
        // Cari therapist yang tersedia dan bukan therapist pertama
        $startTime = Carbon::parse($request->booking_time);
        $endTime = $startTime->copy()->addMinutes($treatment2->duration_minutes);

        // Cari therapist yang tidak sama dengan therapist pertama
        $availableSecondTherapists = User::where('role', 'therapist')->whereIn('availability', ['tersedia', 'sedang menangani'])
            ->where('id', '!=', $request->therapist_id) // exclude therapist pertama
            ->get()
            ->filter(function ($therapist) use ($request, $startTime, $endTime) {
                return !Booking::where('therapist_id', $therapist->id)
                    ->where('booking_date', $request->booking_date)
                    ->whereIn('status', ['menunggu', 'sedang'])
                    ->where(function ($query) use ($startTime, $endTime) {
                        $query->whereBetween('booking_time', [$startTime, $endTime->copy()->subMinute()])
                              ->orWhereRaw('? BETWEEN booking_time AND ADDTIME(booking_time, SEC_TO_TIME(duration_minutes * 60))', [$startTime->format('H:i:s')]);
                    })
                    ->exists();
            });

        // Jika tidak ada therapist kedua yang tersedia, tampilkan error
        if ($availableSecondTherapists->isEmpty()) {
            throw new \Exception('Tidak ada therapist kedua yang tersedia pada waktu ini. (Semua Therapist Sudah di booking)');
        }

        // Pilih therapist yang pertama dari availableSecondTherapists
        $secondTherapistId = $availableSecondTherapists->first()->id;
    }
    //pilih treatment kedua yang cocok dengan ruang doouble
    if (!in_array($treatment2->room_type, ['both', 'double', 'reflexology', 'triple'])) {
    throw new \Exception('Treatment kedua tidak cocok untuk ruangan double.');
    }



    // Proses booking kedua dengan therapist kedua
    $booking2 =  Booking::create([
        'user_id' => Auth::id(),
        'guest_name' => null,
        'guest_phone' => null,
        'treatment_id' => $treatment2->id,
        'therapist_id' => $secondTherapistId,
        'created_by' => Auth::id(),
        'booking_date' => $request->booking_date,
        'booking_time' => $request->booking_time,
        'duration_minutes' => $treatment2->duration_minutes,
        'original_price' => $treatment2->price,
        'final_price' => $finalPrice2,
        'is_happy_hour' => $isHappyHour2,
        'is_promo_reward' => false,
        'payment_method' => $request->payment_method,
        'payment_status' => 'belum_bayar',
        'status' => 'menunggu',
        'note' => $request->note,
        'room_type' => 'double',
    ]);
    $createdBookingIds[] = $booking2->id;
}
DB::commit();




     //payment gateway 
        // Payment Gateway Logic (support single & double room)
        if ($request->payment_method === 'cash') {
        return redirect()->route('booking.riwayat')->with('success', 'Booking berhasil ditambahkan.');
        }

    $bookings = Booking::with('treatment')
        ->whereIn('id', $createdBookingIds)
        ->where('payment_method', 'gateway')
        ->get();

    if ($bookings->isEmpty()) {
        return back()->with('error', 'Data booking tidak ditemukan.');
    }

$totalFinalPrice = $bookings->sum('final_price');

// Pakai salah satu ID booking untuk order_id unik
$orderId = 'BOOKING-' . $bookings->first()->id;

if ($request->payment_method === 'gateway') {

    if ($totalFinalPrice <= 0) {
        return redirect()->route('booking.riwayat')
            ->with('success', 'Booking berhasil ditambahkan tanpa pembayaran.');
    }

    \Midtrans\Config::$serverKey = config('midtrans.serverKey');
    \Midtrans\Config::$isProduction = config('midtrans.isProduction');
    \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
    \Midtrans\Config::$is3ds = config('midtrans.is3ds');

    $itemDetails = [];
    foreach ($bookings as $b) {
        if ($b->treatment) {
            $itemDetails[] = [
                'id' => 'BOOKING-' . $b->id,
                'price' => (int) $b->final_price,
                'quantity' => 1,
                'name' => $b->treatment->name . ' (BOOKING-' . $b->id . ')',
            ];
        }
    }

    $params = [
        'transaction_details' => [
            'order_id' => 'BOOKING-' . implode('-', $bookings->pluck('id')->toArray()) . '-' . now()->timestamp,
            'gross_amount' => (int) $totalFinalPrice,
        ],
        'customer_details' => [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email ?? 'guest@example.com',
            'phone' => Auth::user()->phone ?? '08123456789',
        ],
        'item_details' => $itemDetails,
        'callbacks' => [
            'finish' => route('booking.riwayat'),
        ]
    ];

    if ($bookings->first()->snap_token) {
        $snapToken = $bookings->first()->snap_token;
    } else {
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // ⛳ Simpan token ke SEMUA booking yang baru dibuat
        foreach ($bookings as $b) {
            $b->update(['snap_token' => $snapToken]);
        }
    }

    return view('Pages.Booking.snap', compact('snapToken'));
}

    if ($request->payment_method === 'cash') {
        // ✅ Tambah belanja ke membership langsung
        app(MembershipService::class)->addSpending(Auth::user(), $totalFinalPrice);

        return redirect()->route('booking.riwayat')->with('success', 'Booking berhasil ditambahkan.');
    }

return redirect()->route('booking.riwayat')->with('success', 'Booking berhasil ditambahkan.');


    } catch (\Exception $e) {
        DB::rollBack(); // ❌ Gagal → batalkan semua yang sudah disimpan
        return back()->with('error', $e->getMessage());
    }
}

public function payAgain($id)
{
    $booking = Booking::with('treatment')
        ->where('id', $id)
        ->where('user_id', Auth::id())
        ->where('payment_status', 'belum_bayar')
        ->where('payment_method', 'gateway') // filter hanya payment gateway
        ->firstOrFail();

    // Ambil semua booking dengan waktu dan user yang sama, hanya yang pakai gateway dan belum bayar
    $bookings = Booking::with('treatment')
        ->where('user_id', Auth::id())
        ->where('booking_date', $booking->booking_date)
        ->where('booking_time', $booking->booking_time)
        ->where('payment_method', 'gateway')
        ->where('payment_status', 'belum_bayar')
        ->orderBy('id', 'asc')
        ->get();

    if ($bookings->isEmpty()) {
        return back()->with('error', 'Booking belum bayar tidak ditemukan.');
    }

    $totalFinalPrice = $bookings->sum('final_price');

    if ($totalFinalPrice <= 0) {
        return redirect()->route('booking.riwayat')->with('success', 'Booking tidak memerlukan pembayaran.');
    }

    \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
    \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
    \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
    \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');

    $itemDetails = [];
    foreach ($bookings as $b) {
        $itemDetails[] = [
            'id' => 'BOOKING-' . $b->id,
            'price' => (int) $b->final_price,
            'quantity' => 1,
            'name' => $b->treatment->name . ' (BOOKING-' . $b->id . ')',
        ];
    }

    $orderId = 'BOOKING-' . $booking->id . '-' . now()->timestamp;

    // Cek apakah sudah ada snap_token
    if ($booking->snap_token) {
        $snapToken = $booking->snap_token;
    } else {
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int)$totalFinalPrice,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email ?? 'guest@example.com',
                'phone' => Auth::user()->phone ?? '08123456789',
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('booking.riwayat'),
            ]
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Simpan Snap Token ke SEMUA booking yang relevan
        foreach ($bookings as $b) {
            $b->update(['snap_token' => $snapToken]);
        }
    }

    return view('Pages.Booking.snap', compact('snapToken'));
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
        // 'user_id' => 'nullable|exists:users,id',
        'guest_name' => 'required|string',
        'guest_phone' => 'required|string',
        'room_type' => 'required|in:single,double,hair,reflexology',
        'second_treatment_id' => 'nullable|exists:treatments,id',
        'second_therapist_id' => 'nullable|exists:users,id',
        'is_promo_reward' => 'nullable|boolean',
        'payment_status' => 'nullable|in:sudah_bayar,belum_bayar',
    ]);

    $bookingDateTime = Carbon::parse($request->booking_date . ' ' . $request->booking_time);
    if ($bookingDateTime->isPast()) return back()->with('error', 'Tidak bisa booking di waktu yang sudah lewat.');
    if ($bookingDateTime->gt(now()->addDays(7))) return back()->with('error', 'Booking hanya bisa dilakukan maksimal 7 hari ke depan.');

    if (!$request->user_id && (!$request->guest_name || !$request->guest_phone)) {
        return back()->with('error', 'Harap pilih customer atau isi data tamu guest.');
    }

    $treatment = Treatment::where('id', $request->treatment_id)->where('is_available', true)->first();
    if (!$treatment) return back()->with('error', 'Treatment ini tidak tersedia untuk saat ini.');

    // ✅ Mapping fleksibilitas room_type treatment ke room fisik spa_rooms
    $roomMapping = [
        'single' => ['single'],
        'double' => ['double'],
        'both' => ['single', 'double'],
        'hair' => ['hair'],
        'reflexology' => ['reflexology'],
        'triple' => ['single', 'double', 'reflexology'],
    ];

    $roomType = $request->room_type;
    $allowedRoomTypes = $roomMapping[$treatment->room_type] ?? [];

    if (!in_array($roomType, $allowedRoomTypes)) {
        return back()->with('error', 'Treatment ini tidak tersedia untuk tipe ruangan yang dipilih.');
    }

    // if ($treatment->room_type !== 'both' && $treatment->room_type !== $request->room_type) {
    //     return back()->with('error', 'Treatment tidak sesuai dengan tipe ruangan yang dipilih.');
    // }

    $startTime = Carbon::parse($request->booking_time);
    $endTime = $startTime->copy()->addMinutes($treatment->duration_minutes);

    // ✅ Auto pilih therapist jika tidak diisi
    if (empty($request->therapist_id)) {
        $availableTherapists = User::where('role', 'therapist')->whereIn('availability', ['tersedia', 'sedang menangani'])->get()->filter(function ($therapist) use ($request, $startTime, $endTime) {
            return !Booking::where('therapist_id', $therapist->id)
                ->where('booking_date', $request->booking_date)
               ->whereIn('status', ['menunggu', 'sedang'])
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('booking_time', [$startTime, $endTime->copy()->subMinute()])
                          ->orWhereRaw('? BETWEEN booking_time AND ADDTIME(booking_time, SEC_TO_TIME(duration_minutes * 60))', [$startTime->format('H:i:s')]);
                })->exists();
        });

        if ($availableTherapists->isEmpty()) {
            return back()->with('error', 'Semua therapist sudah memiliki booking di waktu tersebut.');
        }

        $bookingCounts = Booking::where('booking_date', $request->booking_date)
            ->whereIn('therapist_id', $availableTherapists->pluck('id'))
            ->select('therapist_id', DB::raw('count(*) as total'))
            ->groupBy('therapist_id')
            ->pluck('total', 'therapist_id');

        $sorted = $availableTherapists->sortBy(fn($t) => $bookingCounts[$t->id] ?? 0);

        $request->merge(['therapist_id' => $sorted->first()->id]);
    }

    // Validasi bentrok
    $isOverlap = Booking::where('therapist_id', $request->therapist_id)
        ->where('booking_date', $request->booking_date)
       ->whereIn('status', ['menunggu', 'sedang'])
        ->where(function ($query) use ($startTime, $endTime) {
            $query->whereBetween('booking_time', [$startTime, $endTime->copy()->subMinute()])
                  ->orWhereRaw('? BETWEEN booking_time AND ADDTIME(booking_time, SEC_TO_TIME(duration_minutes * 60))', [$startTime->format('H:i:s')]);
        })->exists();
    if ($isOverlap) return back()->with('error', 'Therapist sudah memiliki booking bentrok di waktu tersebut.');

    // $isHappyHour = $treatment->happy_hour_price &&
    //     in_array($bookingDateTime->dayOfWeek, [1, 2, 3, 4, 5]) &&
    //     $bookingDateTime->between(Carbon::createFromTimeString('10:00:00'), Carbon::createFromTimeString('13:00:00'));
        
    $isHappyHour = $treatment->happy_hour_price &&
        in_array($bookingDateTime->dayOfWeek, [1, 2, 3, 4, 5]) &&
        Carbon::parse($request->booking_time)->between(
            Carbon::createFromTimeString('10:00:00'),
            Carbon::createFromTimeString('13:00:00')
        );


    $isPromoReward = $request->input('is_promo_reward') == true;
    $finalPrice = $isPromoReward ? 0 : ($isHappyHour ? $treatment->happy_hour_price : $treatment->price);

    $roomType = $request->room_type;
    $maxCapacity = \App\Models\SpaRoom::where('room_type', $roomType)->sum('capacity');

    $existingBookingCount = Booking::where('booking_date', $request->booking_date)
        ->where('booking_time', $request->booking_time)
        ->where('room_type', $roomType)
        // ->where('status', '!=', 'batal')
        ->whereIn('status', ['menunggu', 'sedang']) 
        ->count();

    if ($existingBookingCount >= $maxCapacity) {
        return back()->with('error', 'Ruangan sudah penuh pada waktu tersebut.');
    }

    DB::beginTransaction();
    try {
        Booking::create([
            'user_id' => $request->user_id,
            'guest_name' => $request->guest_name,
            'guest_phone' => $request->guest_phone,
            'treatment_id' => $treatment->id,
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
            'payment_status' => $request->payment_status ?? 'belum_bayar',
            'status' => 'menunggu',
            'note' => $request->note,
            'room_type' => $roomType,
        ]);

        if ($roomType === 'double') {
            if (empty($request->second_treatment_id)) {
                throw new \Exception('Harap pilih treatment kedua untuk ruangan double.');
            }

            $treatment2 = Treatment::findOrFail($request->second_treatment_id);
            if (!in_array($treatment2->room_type, ['both', 'double', 'reflexology', 'triple'])) {
                throw new \Exception('Treatment kedua tidak cocok untuk ruangan double.');
            }


            $isHappyHour2 = $treatment2->happy_hour_price &&
                in_array($bookingDateTime->dayOfWeek, [1, 2, 3, 4, 5]) &&
                $bookingDateTime->between(Carbon::createFromTimeString('10:00:00'), Carbon::createFromTimeString('13:00:00'));

            $finalPrice2 = $isHappyHour2 ? $treatment2->happy_hour_price : $treatment2->price;
            $secondTherapistId = $request->second_therapist_id;

            if ($request->therapist_id == $secondTherapistId) {
                throw new \Exception('Therapist pertama dan kedua harus berbeda.');
            }

            if (empty($secondTherapistId)) {
                $availableTherapists = User::where('role', 'therapist')->whereIn('availability', ['tersedia', 'sedang menangani'])
                    ->where('id', '!=', $request->therapist_id)
                    ->get()
                    ->filter(function ($therapist) use ($request, $startTime, $endTime) {
                        return !Booking::where('therapist_id', $therapist->id)
                            ->where('booking_date', $request->booking_date)
                            ->whereIn('status', ['menunggu', 'sedang']) 
                            ->where(function ($query) use ($startTime, $endTime) {
                                $query->whereBetween('booking_time', [$startTime, $endTime->copy()->subMinute()])
                                      ->orWhereRaw('? BETWEEN booking_time AND ADDTIME(booking_time, SEC_TO_TIME(duration_minutes * 60))', [$startTime->format('H:i:s')]);
                            })->exists();
                    });

                if ($availableTherapists->isEmpty()) {
                    throw new \Exception('Tidak ada therapist kedua yang tersedia.');
                }

                $secondTherapistId = $availableTherapists->first()->id;
            }

            Booking::create([
                'user_id' => $request->user_id,
                'guest_name' => $request->guest_name,
                'guest_phone' => $request->guest_phone,
                'treatment_id' => $treatment2->id,
                'therapist_id' => $secondTherapistId,
                'created_by' => Auth::id(),
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'duration_minutes' => $treatment2->duration_minutes,
                'original_price' => $treatment2->price,
                'final_price' => $finalPrice2,
                'is_happy_hour' => $isHappyHour2,
                'is_promo_reward' => false,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status ?? 'belum_bayar',
                'status' => 'menunggu',
                'note' => $request->note,
                'room_type' => 'double',
            ]);
        }

        // if ($request->user_id && $request->payment_method === 'cash' && $request->input('payment_status') === 'sudah_bayar' ) {
        //         $totalSpending = $finalPrice + ($finalPrice2 ?? 0);

        //         app(MembershipService::class)->addSpending(
        //             User::find($request->user_id),
        //             $totalSpending
        //         );
        //     }



        DB::commit();

        return redirect()->route('booking.admin')->with('success', 'Booking berhasil ditambahkan.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', $e->getMessage());
    }
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
    $therapists = User::where('role', 'therapist')->whereIn('availability', ['tersedia', 'sedang menangani'])->get()->filter(function ($therapist) use ($tanggal, $startTime, $endTime) {
        return !Booking::where('therapist_id', $therapist->id)
            ->where('booking_date', $tanggal)
           ->whereIn('status', ['menunggu', 'sedang']) 
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
   $query->orderBy('created_at', $order);


    $bookings = $query->paginate(8)->appends(request()->query());

    return view('Pages.RiwayatBooking.riwayatbooking', compact('bookings'));
}


public function cancelBookingCustomer($id)
{
    $booking = Booking::where('id', $id)
        ->where('user_id', Auth::id())
        ->where('status', 'menunggu')
        ->firstOrFail();

        if (now()->diffInMinutes(Carbon::parse($booking->booking_date . ' ' . $booking->booking_time), false) < 60) {
        return redirect()->back()->with('error', 'Booking tidak bisa dibatalkan karena waktu sudah terlalu dekat.');
    }


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

// public function updateStatus($id, $status)
// {
//     $booking = Booking::with('therapist')->findOrFail($id);

//     if (!in_array($status, ['sedang', 'selesai'])) {
//         return back()->with('error', 'Status tidak valid.');
//     }

//     // Update status booking
//     $booking->update(['status' => $status]);

//     // Update availability therapist (jika ada therapist-nya)
//     if ($booking->therapist) {
//         if ($status === 'sedang') {
//             $booking->therapist->update(['availability' => 'sedang menangani']);
//         }

//         if ($status === 'selesai') {
//             $booking->therapist->update(['availability' => 'tersedia']);
//         }
//     }


//     return back()->with('success', "Status booking diubah menjadi '$status'.");
// }

public function updateStatus($id, $status)
{
    $booking = Booking::with('therapist')->findOrFail($id);

    if (!in_array($status, ['sedang', 'selesai'])) {
        return back()->with('error', 'Status tidak valid.');
    }

    if ($status === 'sedang') {
        // Update status booking
        $booking->update(['status' => $status]);

        // Update availability therapist menjadi sedang menangani
        $booking->therapist?->update(['availability' => 'sedang menangani']);
    }

    if ($status === 'selesai') {
        $now = now();
        $bookingStart = Carbon::parse($booking->booking_date . ' ' . $booking->booking_time);
        $actualDuration = max($now->diffInMinutes($bookingStart), 1); // Minimal 1 menit

        // Update status booking dan durasi_minutes menjadi durasi aktual selesai
        $booking->update([
            'status' => $status,
            'duration_minutes' => $actualDuration,
        ]);

        // Update availability therapist menjadi tersedia
        $booking->therapist?->update(['availability' => 'tersedia']);
    }

    return back()->with('success', "Status booking diubah menjadi '$status'.");
}


// dibookingmanajemen
public function markAsPaid($id)
{
    $booking = Booking::findOrFail($id);

    if ($booking->status === 'batal') {
        return back()->with('error', 'Tidak bisa menandai lunas karena booking sudah dibatalkan.');
    }

    // Cek apakah status berubah dari belum_bayar ke sudah_bayar
    $wasUnpaid = $booking->payment_status === 'belum_bayar';

    $booking->update(['payment_status' => 'sudah_bayar']);

     // ✅ Tambahkan spending jika memenuhi syarat
    if ($wasUnpaid && $booking->user_id && $booking->payment_method === 'cash') {
        try {
            app(MembershipService::class)->addSpending(
                User::find($booking->user_id),
                $booking->final_price
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to add spending in markAsPaid: ' . $e->getMessage());
        }
    }

    return back()->with('success', 'Status pembayaran ditandai sebagai lunas.');
}

public function manajemenPembayaran(Request $request)
{
    $query = Booking::with(['user', 'treatment', 'therapist'])
        ->orderBy('created_at', 'desc');

    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('payment_status')) {
        $query->where('payment_status', $request->payment_status);
    }
    if ($request->filled('therapist_id')) {
        $query->where('therapist_id', $request->therapist_id);
    }
    if ($request->filled('start_date')) {
        $query->whereDate('booking_date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('booking_date', '<=', $request->end_date);
    }


    $therapists = User::where('role', 'therapist')->get(); 
    $bookings = $query->paginate(10);

    return view('Pages.Admin.manajemenpembayaran', compact('bookings', 'therapists'));
}

// di manajemenPembayaran
public function updateStatusBayar($id)
{
    $booking = Booking::findOrFail($id);

    if ($booking->status === 'batal') {
        return back()->with('error', 'Tidak bisa memperbarui status pembayaran karena booking sudah dibatalkan.');
    }
    // Cek apakah status berubah dari belum_bayar ke sudah_bayar
    $wasUnpaid = $booking->payment_status === 'belum_bayar';

    $booking->payment_status = 'sudah_bayar';
    $booking->save();

     // ✅ Tambahkan spending jika memenuhi syarat
    if ($wasUnpaid && $booking->user_id && $booking->payment_method === 'cash') {
        try {
            app(MembershipService::class)->addSpending(
                User::find($booking->user_id),
                $booking->final_price
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to add spending in updateStatusBayar: ' . $e->getMessage());
        }
    }

    return redirect()->back()->with('success', 'Status pembayaran diperbarui!');
}

public function getAvailableTherapistsForSecondTreatment(Request $request)
{
    $tanggal = $request->tanggal;
    $jam = $request->jam;
    $treatmentId = $request->treatment_id;
    $firstTherapistId = $request->first_therapist_id ?? null;

    if (!$tanggal || !$jam || !$treatmentId) {
        return response()->json(['error' => 'Tanggal, jam, dan treatment wajib diisi'], 400);
    }

    $treatment = Treatment::find($treatmentId);
    if (!$treatment) {
        return response()->json(['error' => 'Treatment tidak ditemukan'], 404);
    }

    $startTime = Carbon::parse($jam);
    $endTime = $startTime->copy()->addMinutes($treatment->duration_minutes);

    // Filter therapist yang tidak bentrok di waktu treatment kedua
    $therapists = User::where('role', 'therapist')->whereIn('availability', ['tersedia', 'sedang menangani'])->get()->filter(function ($therapist) use ($tanggal, $startTime, $endTime, $firstTherapistId) {
        if ($firstTherapistId && $therapist->id == $firstTherapistId) {
            // exclude therapist pertama
            return false;
        }

        $hasBooking = Booking::where('therapist_id', $therapist->id)
            ->where('booking_date', $tanggal)
         ->whereIn('status', ['menunggu', 'sedang']) 
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('booking_time', [$startTime, $endTime->copy()->subMinute()])
                      ->orWhereRaw('? BETWEEN booking_time AND ADDTIME(booking_time, SEC_TO_TIME(duration_minutes * 60))', [$startTime->format('H:i:s')]);
            })
            ->exists();

        return !$hasBooking;
    });

    return response()->json([
        'therapists' => $therapists->values(),
    ]);
}

public function getAllRoomCapacities(Request $request)
{
    $date = $request->date;
    $time = $request->time;
    $treatmentId1 = $request->treatment_id1; // treatment pertama
    $treatmentId2 = $request->treatment_id2; // treatment kedua (opsional)

    if (!$date || !$time) {
        return response()->json(['error' => 'Tanggal dan jam wajib diisi.'], 400);
    }

    $startTime = \Carbon\Carbon::parse($time);

    // Durasi treatment pertama
    $duration1 = \App\Models\Treatment::find($treatmentId1)?->duration_minutes ?? 0;
    // Durasi treatment kedua (jika ruangan double)
    $duration2 = \App\Models\Treatment::find($treatmentId2)?->duration_minutes ?? 0;

    // Durasi untuk pengecekan ruangan double: ambil yang terpanjang
    $maxDoubleDuration = max($duration1, $duration2);

    $roomTypes = ['single', 'double', 'reflexology', 'hair'];
    $data = [];

    foreach ($roomTypes as $roomType) {
        $maxCapacity = \App\Models\SpaRoom::where('room_type', $roomType)->sum('capacity');

        // Tentukan endTime sesuai durasi dan tipe ruangan
        if ($roomType == 'double') {
            $endTime = $startTime->copy()->addMinutes($maxDoubleDuration);
        } else {
            // TreatmentId1 tetap digunakan untuk room selain double
            $endTime = $startTime->copy()->addMinutes($duration1);
        }

        $bookedCount = \App\Models\Booking::where('booking_date', $date)
            ->where('room_type', $roomType)
            // ->where('status', '!=', 'batal')
            ->whereIn('status', ['menunggu', 'sedang']) 
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('booking_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                    ->orWhereRaw('? BETWEEN booking_time AND ADDTIME(booking_time, SEC_TO_TIME(duration_minutes * 60))', [$startTime->format('H:i:s')]);
            })
            ->count();

        $data[$roomType] = [
            'max_capacity' => $maxCapacity,
            'booked_count' => $bookedCount,
            'available' => max($maxCapacity - $bookedCount, 0)
        ];
    }

    return response()->json($data);
}





}
