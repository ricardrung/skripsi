<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaRoom extends Model
{
    use HasFactory;

    protected $table = 'spa_rooms';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'room_name',
        'room_type',
        'description',
        'image_path',
        'capacity',
        'is_active',
    ];

    // Optional: casting kolom
    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];
}
