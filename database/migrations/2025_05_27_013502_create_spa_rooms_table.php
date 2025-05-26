<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spa_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_name');       // Nama ruangan (optional)
            $table->string('room_type');       // Jenis treatment ruangan: reflexology, facial, dll
            $table->integer('capacity');       // Kapasitas total ruangan ini (berapa orang bisa sekaligus)
            $table->text('description')->nullable(); // Deskripsi ruangan
            $table->string('image_path')->nullable(); // Lokasi gambar
            $table->boolean('is_active')->default(true); // Tampilkan / sembunyikan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spa_rooms');
    }
};
