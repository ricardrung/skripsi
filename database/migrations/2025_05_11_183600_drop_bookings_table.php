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
        //
        Schema::dropIfExists('bookings');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
         Schema::create('bookings', function (Blueprint $table) {
        $table->id();
          $table->id();

            // Customer bisa dari user_id (akun) ATAU tamu guest
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('guest_name')->nullable();   // untuk tamu guest
            $table->string('guest_phone')->nullable();  // untuk tamu guest

            // Relasi treatment & therapist
            $table->foreignId('treatment_id')->constrained()->onDelete('cascade');
            $table->foreignId('therapist_id')->nullable()->constrained('users')->onDelete('set null');

            // Siapa yang membuat booking (user / admin)
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');

            // Detail waktu
            $table->date('booking_date');
            $table->time('booking_time');
            $table->integer('duration_minutes');

            // Harga & Promo
            $table->decimal('original_price', 10, 2)->nullable(); // harga normal
            $table->decimal('final_price', 10, 2);                // harga akhir (bisa diskon / gratis)
            $table->boolean('is_happy_hour')->default(false);    // apakah happy hour
            $table->boolean('is_promo_reward')->default(false);  // apakah reward ke-5 booking

            // Pembayaran
            $table->enum('payment_method', ['cash', 'gateway'])->default('cash');
            $table->enum('payment_status', ['belum_bayar', 'sudah_bayar'])->default('belum_bayar');

            // Status Booking
            $table->enum('status', ['menunggu', 'sedang', 'selesai', 'batal'])->default('menunggu');

            // Tambahan
            $table->text('note')->nullable();                   // catatan dari admin/customer
            $table->text('cancellation_reason')->nullable();   // alasan batal
            $table->timestamp('canceled_at')->nullable();      // waktu batal

            $table->timestamps();
        $table->timestamps();
    });
    }
};
