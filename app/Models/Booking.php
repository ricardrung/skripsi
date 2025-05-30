<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    //
    protected $fillable = [
       'user_id',
        'guest_name',
        'guest_phone',
        'treatment_id',
        'therapist_id',
        'created_by',
        'booking_date',
        'booking_time',
        'duration_minutes',
        'original_price',
        'final_price',
        'is_happy_hour',
        'is_promo_reward',
        'payment_method',
        'payment_status',
        'status',
        'note',
        'room_type',
        'cancellation_reason',
        'canceled_at',
    ];

         // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
