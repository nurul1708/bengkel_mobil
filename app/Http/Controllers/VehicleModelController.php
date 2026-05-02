<?php

namespace App\Http\Controllers;

use App\Models\VehicleBrand;

class VehicleModelController extends Controller
{
    public function byBrand(VehicleBrand $brand)
    {
        return $brand->models()
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
