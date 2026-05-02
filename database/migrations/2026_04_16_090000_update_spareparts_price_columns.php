<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('spareparts')) {
            return;
        }

        if (Schema::hasColumn('spareparts', 'price') && !Schema::hasColumn('spareparts', 'harga_jual')) {
            DB::statement('ALTER TABLE spareparts CHANGE price harga_jual DECIMAL(12,2) NOT NULL');
        }

        if (!Schema::hasColumn('spareparts', 'harga_beli')) {
            DB::statement('ALTER TABLE spareparts ADD harga_beli DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER stock');
        }

        if (Schema::hasColumn('spareparts', 'harga_beli') && Schema::hasColumn('spareparts', 'harga_jual')) {
            DB::statement('UPDATE spareparts SET harga_beli = harga_jual WHERE harga_beli = 0 OR harga_beli IS NULL');
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('spareparts')) {
            return;
        }

        if (Schema::hasColumn('spareparts', 'harga_beli')) {
            DB::statement('ALTER TABLE spareparts DROP COLUMN harga_beli');
        }

        if (Schema::hasColumn('spareparts', 'harga_jual') && !Schema::hasColumn('spareparts', 'price')) {
            DB::statement('ALTER TABLE spareparts CHANGE harga_jual price DECIMAL(12,2) NOT NULL');
        }
    }
};
