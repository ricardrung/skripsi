<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMembership extends Model
{
    //
        protected $fillable = [
        'user_id', 'membership_id', 'year', 'yearly_spending',
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
