<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PemasukanExport;

class LaporanController extends Controller
{
    //
    public function download(Request $request)
{
    $query = Booking::with(['user', 'treatment'])
        ->where('payment_status', 'sudah_bayar');

    if ($request->filled('start_date')) {
        $query->whereDate('booking_date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('booking_date', '<=', $request->end_date);
    }

    $data = $query->orderBy('booking_date')->get();

    if ($request->format === 'excel') {
        return Excel::download(new PemasukanExport($data), 'laporan_pemasukan.xlsx');
    }

    $pdf = Pdf::loadView('laporan.pemasukan_pdf', compact('data'));
    return $pdf->download('laporan_pemasukan.pdf');
}
}
