<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
class LaporanController extends Controller
{
    //
    public function downloadCsv(Request $request)
{
    $query = Booking::with(['user', 'treatment'])
        ->where('payment_status', 'sudah_bayar');

    if ($request->filled('start_date')) {
        $query->whereDate('booking_date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('booking_date', '<=', $request->end_date);
    }

    $bookings = $query->get();

    $filename = 'laporan_pemasukan_' . now()->format('Ymd_His') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($bookings) {
        $handle = fopen('php://output', 'w');

        // Header kolom
        fputcsv($handle, ['Tanggal', 'Nama', 'Treatment', 'Harga', 'Metode']);

        foreach ($bookings as $b) {
            fputcsv($handle, [
                $b->booking_date,
                $b->user->name ?? $b->guest_name,
                $b->treatment->name ?? '-',
                $b->final_price,
                ucfirst($b->payment_method ?? '-'),
            ]);
        }

        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}
    
}
