<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'roemahrempahspamanado@gmail.com'], // Unique constraint

            [ // Lengkapi kolom 
                'name' => 'Admin Roemah Rempah',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'), 
                'phone' => '08114321986',
                'birthdate' => '1991-11-17',
                'gender' => 'female',
                'role' => 'admin',
                'photo' => null, // Atau kosong/null kalau tidak ada
            ]
        );
    }
}
