<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    //
    protected $fillable = [
        'name', 'min_annual_spending', 'discount_percent', 'applies_to', 'benefit_note'
    ];
}
