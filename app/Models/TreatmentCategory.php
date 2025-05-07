<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TreatmentCategory extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function treatments()
    {
        return $this->hasMany(Treatment::class, 'category_id');
    }

}
