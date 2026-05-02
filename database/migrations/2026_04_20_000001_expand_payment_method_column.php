<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY payment_method VARCHAR(50) NOT NULL");
            return;
        }

        Schema::table('payments', function ($table) {
            $table->string('payment_method', 50)->change();
        });
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('cash','transfer','qris') NOT NULL");
            return;
        }

        Schema::table('payments', function ($table) {
            $table->enum('payment_method', ['cash', 'transfer', 'qris'])->change();
        });
    }
};
