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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // Customer: bisa registered (users) atau guest
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('guest_name')->nullable();
            // Treatment & Therapist → nullable supaya tidak hilangkan riwayat
            $table->foreignId('treatment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('therapist_id')->nullable()->constrained('users')->onDelete('set null');
            // Waktu treatment
            $table->datetime('start_time');
            $table->datetime('end_time');
            // Status booking
            $table->enum('status', ['pending', 'ongoing', 'completed', 'cancelled'])->default('pending');
            // Siapa yang buat booking (admin / customer)
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
