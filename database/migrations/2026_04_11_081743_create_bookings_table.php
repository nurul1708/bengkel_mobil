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
    Schema::create('bookings', function ($table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users');
        $table->foreignId('service_id')->constrained('services'); // <--- TAMBAHKAN BARIS INI
        $table->foreignId('vehicle_id')->constrained('vehicles');
        $table->date('booking_date');
        $table->time('booking_time');
        $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'paid']);
        $table->text('complaint');
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
