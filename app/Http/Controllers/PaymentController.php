<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class PaymentController extends Controller
{
    //
     public function handleCallback(Request $request)
    {
        $payload = json_decode($request->getContent());
        $orderId = explode('-', $payload->order_id)[1] ?? null;

        $booking = Booking::find($orderId);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if (in_array($payload->transaction_status, ['settlement', 'capture'])) {
            $booking->update([
                'payment_status' => 'sudah_bayar',
                'payment_method' => 'gateway',
            ]);
        }

        return response()->json(['message' => 'Callback handled']);
    }
}
