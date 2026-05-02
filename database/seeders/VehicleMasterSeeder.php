<?php

namespace Database\Seeders;

use App\Models\VehicleBrand;
use Illuminate\Database\Seeder;

class VehicleMasterSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Toyota' => ['Avanza', 'Innova', 'Fortuner', 'Yaris', 'Rush'],
            'Honda' => ['Brio', 'Jazz', 'Civic', 'HR-V', 'CR-V'],
            'Daihatsu' => ['Xenia', 'Terios', 'Ayla', 'Sigra', 'Gran Max'],
            'Suzuki' => ['Ertiga', 'Carry', 'Baleno', 'XL7', 'Ignis'],
            'Mitsubishi' => ['Xpander', 'Pajero Sport', 'Triton', 'L300'],
            'Nissan' => ['Livina', 'March', 'Serena', 'X-Trail'],
            'Hyundai' => ['Stargazer', 'Creta', 'Palisade', 'Santa Fe'],
            'Wuling' => ['Confero', 'Cortez', 'Almaz', 'Air EV'],
        ];

        foreach ($brands as $brandName => $modelNames) {
            $brand = VehicleBrand::firstOrCreate(['name' => $brandName]);

            foreach ($modelNames as $modelName) {
                $brand->models()->firstOrCreate(['name' => $modelName]);
            }
        }
    }
}
