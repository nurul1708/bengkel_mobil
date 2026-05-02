<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void {
    Schema::create('transactions', function ($table) {
        $table->id();
        $table->foreignId('booking_id')->constrained('bookings');
        $table->foreignId('mekanik_id')->constrained('users'); // Relasi ke users (role mekanik)
        $table->foreignId('kasir_id')->constrained('users');   // Relasi ke users (role kasir)
        $table->decimal('total_service', 12, 2);
        $table->decimal('total_sparepart', 12, 2);
        $table->decimal('grand_total', 12, 2);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
