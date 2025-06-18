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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Classic, Silver, etc
            $table->unsignedInteger('min_annual_spending')->default(0); // Syarat
            $table->unsignedTinyInteger('discount_percent')->default(0); // Benefit diskon
            $table->string('applies_to')->default('all'); // 'all' atau 'body'
            $table->string('benefit_note')->nullable(); // opsional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
