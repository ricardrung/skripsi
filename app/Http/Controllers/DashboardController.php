<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $today = Carbon::today();
        $thisWeek = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
        $thisMonth = Carbon::now()->month;

        // Pendapatan per 12 bulan 
        $incomeData = Booking::where('payment_status', 'sudah_bayar')
            ->where('status', '!=', 'batal')
            ->whereYear('booking_date', now()->year)
            ->selectRaw('MONTH(booking_date) as month, SUM(final_price) as total')
            ->groupByRaw('MONTH(booking_date)')
            ->pluck('total', 'month');

        // Pastikan semua bulan ada dari Des
        $months = collect(range(1, 12))->map(function ($m) use ($incomeData) {
            return $incomeData->get($m, 0);
        });


        return view('pages.admin.dashboard', [
            'todayBookings' => Booking::whereDate('booking_date', $today)->count(),
            'weeklyBookings' => Booking::whereBetween('booking_date', $thisWeek)->count(),
            'monthlyBookings' => Booking::whereMonth('booking_date', $thisMonth)->count(),
            'activeTherapists' => User::where('role', 'therapist')->where('availability', 'tersedia')->count(),
            'totalTherapists' => User::where('role', 'therapist')->count(),
            'notifications' => Booking::latest()->take(5)->get(),
            'calendarBookings' => Booking::with(['user', 'therapist', 'treatment'])->select('id', 'user_id', 'therapist_id', 'guest_name', 'treatment_id','booking_date','booking_time', 'duration_minutes', 'status', 'note')->get(),
            'monthlyIncome' => $months->values()->toArray(),
            'therapistList' => [
                'tersedia' => User::where('role', 'therapist')->where('availability', 'tersedia')->get(),
                'sedang' => User::where('role', 'therapist')->where('availability', 'sedang menangani')->get(),
                'tidak' => User::where('role', 'therapist')->where('availability', 'tidak tersedia')->get(),
            ],

        ]);
    }
}
