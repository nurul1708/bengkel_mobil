<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('vehicle_brand_id')->nullable()->after('user_id')->constrained('vehicle_brands')->nullOnDelete();
            $table->foreignId('vehicle_model_id')->nullable()->after('vehicle_brand_id')->constrained('vehicle_models')->nullOnDelete();
            $table->index(['user_id', 'license_plate']);
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vehicle_model_id');
            $table->dropConstrainedForeignId('vehicle_brand_id');
            $table->dropIndex(['user_id', 'license_plate']);
        });
    }
};
