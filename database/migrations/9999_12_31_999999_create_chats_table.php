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
    Schema::create('chats', function (Blueprint $table) {
        $table->id(); // Ini akan membuat id (Big Integer)
        
        // Gunakan cara ini agar tipe datanya otomatis disamakan dengan tabel users
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        $table->text('pesan');
        $table->enum('pengirim', ['customer', 'admin']);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
