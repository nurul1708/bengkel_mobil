<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Testimonial;
use App\Models\VehicleBrand;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index()
    {
        $client = auth()->guard('client')->user();

        return view('services.index', [
            'title' => 'Services',
            'services' => Service::orderBy('service_name')->get(),
            'vehicleBrands' => VehicleBrand::orderBy('name')->get(),
            'vehicles' => $client
                ? Vehicle::where('user_id', $client->id)->orderBy('brand')->orderBy('model')->get()
                : collect(),
                'testimonials' => Testimonial::with('user')
                ->where('status', 'approved')
                ->latest('reviewed_at')
                ->latest('id')
                ->take(8)
                ->get(),
            
        ]);
    }
}
