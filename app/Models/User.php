<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserMembership;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birthdate',
        'gender',
        'role',
        'photo',
        'availability',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
        ];
    }

    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class, 'therapist_id');
    }
    public function bookingsAsCustomer()
    {
        return $this->hasMany(\App\Models\Booking::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';  
    }

    public function memberships()
    {
        return $this->hasMany(UserMembership::class);
    }

    public function currentMembership()
    {
        return $this->hasOne(UserMembership::class)
            ->where('year', now()->year)
            ->with('membership');
    }



    
}
