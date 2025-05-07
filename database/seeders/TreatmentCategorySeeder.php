<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TreatmentCategory;
class TreatmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        TreatmentCategory::insert([
            ['name' => 'Face Treatment', 'slug' => 'facetreatment'],
            ['name' => 'Body Treatment', 'slug' => 'bodytreatment'],
            ['name' => 'Hair Treatment', 'slug' => 'hairtreatment'],
            ['name' => 'Reflexology', 'slug' => 'reflexology'],
            ['name' => 'Treatment Packages', 'slug' => 'treatmentpackages'],
            ['name' => 'A La Carte', 'slug' => 'alacarte'],
        ]);
    }
}
