<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingFeedback;

class BookingFeedbackController extends Controller
{
    //

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        BookingFeedback::create($request->only('booking_id', 'rating', 'comment'));

        return back()->with('success', 'Terima kasih atas feedback Anda!');
    }

}
