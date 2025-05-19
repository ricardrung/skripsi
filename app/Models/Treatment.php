<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    //

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'happy_hour_price',
        'duration_minutes',
        'demo_video_url',
        'is_promo',
        'is_best_selling',
        'promo_required_bookings',
    ];

    public function category()
    {
        return $this->belongsTo(TreatmentCategory::class, 'category_id');
    }
}
