<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $fillable = [
        'customer_id',
        'guest_name',
        'treatment_id',
        'therapist_id',
        'start_time',
        'end_time',
        'status',
        'created_by',
    ];

        // Relasi ke customer (jika punya akun)
        public function customer()
        {
            return $this->belongsTo(User::class, 'customer_id');
        }
    
        // Relasi ke therapist
        public function therapist()
        {
            return $this->belongsTo(User::class, 'therapist_id');
        }
    
        // Relasi ke treatment
        public function treatment()
        {
            return $this->belongsTo(Treatment::class);
        }
    
        // Relasi ke user yang membuat booking (admin / customer)
        public function createdBy()
        {
            return $this->belongsTo(User::class, 'created_by');
        }
}
