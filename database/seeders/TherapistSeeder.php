<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TherapistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $therapists = [
            [
                'name' => 'Therapist A',
                'email' => 'therapist1@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'birthdate' => '1990-01-01',
                'gender' => 'female',
                'role' => 'therapist',
                'photo' => null, // Atau kosong/null kalau tidak ada
            ],
            [
                'name' => 'Therapist B',
                'email' => 'therapist2@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'birthdate' => '1992-02-02',
                'gender' => 'male',
                'role' => 'therapist',
                'photo' => null, // Atau kosong/null kalau tidak ada
            ],
        ];
//  // Hapus dulu semua therapist
//  User::where('role', 'therapist')->delete();

        foreach ($therapists as $therapist) {
            User::updateOrCreate($therapist);
        }
    }
}
