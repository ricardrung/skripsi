<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Services\MembershipService;

class PaymentController extends Controller
{
    //
    public function handleCallback(Request $request)
    {
        $payload = json_decode($request->getContent());
        $orderId = $payload->order_id;

        $bookingIds = collect(explode('-', $orderId))
            ->filter(fn($part) => is_numeric($part)) // hanya ambil ID angka
            ->values();

        $bookings = Booking::whereIn('id', $bookingIds)->get();

        if ($bookings->isEmpty()) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        foreach ($bookings as $booking) {
            if (in_array($payload->transaction_status, ['settlement', 'capture'])) {
                $booking->update([
                    'payment_status' => 'sudah_bayar',
                    'payment_method' => 'gateway',
                ]);
                app(MembershipService::class)->addSpending($booking->user, $booking->final_price);
            }
        }


        return response()->json(['message' => 'Callback handled']);
    }
}
