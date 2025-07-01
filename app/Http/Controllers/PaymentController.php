<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Services\MembershipService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function handleCallback(Request $request)
    {
        Log::info('Midtrans Callback Received', ['payload' => $request->all()]);

        $payload = json_decode($request->getContent());

        if (!$payload || !isset($payload->order_id)) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $orderId = $payload->order_id;

        $bookingIds = collect(explode('-', $orderId))
            ->filter(fn($part) => is_numeric($part))
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

        return response()->json(['message' => 'Callback handled'], 200);
    }
}
