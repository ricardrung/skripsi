<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Treatment;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Treatment::create([
            'name' => 'Awet Enom',
            'description' => 'Totok Wajah + Serum + Masker Wajah',
            'price' => 105000,
            'duration_minutes' => 60,
            'demo_video_url' => 'https://www.youtube.com/embed/j_r4ROcphKQ',
            'is_promo' => false,
        ]);

        Treatment::create([
            'name' => 'Signature Roemah Rempah',
            'description' => 'Massage + The Javanese Lulur + Totok Wajah + Masker Wajah + Ear Candling',
            'price' => 275000,
            'happy_hour_price' => 240000,
            'duration_minutes' => 90,
            'is_promo' => false,
        ]);
    }
}
