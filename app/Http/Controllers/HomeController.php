<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\User; // 1. Pastikan Model User di-import di sini
use App\Models\Sparepart; // Tambahkan ini agar lebih rapi
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $client = auth()->guard('client')->user();
        $spareparts = Sparepart::all();

        return view('home.index', [
            'title' => 'Home',
            'services' => Service::orderBy('service_name')->get(),
            'vehicleBrands' => VehicleBrand::orderBy('name')->get(),
            'vehicles' => $client
                ? Vehicle::where('user_id', $client->id)->orderBy('brand')->orderBy('model')->get()
                : collect(),
            'spareparts' => $spareparts,
            'testimonials' => Testimonial::with('user')
                ->where('status', 'approved')
                ->latest('reviewed_at')
                ->latest('id')
                ->take(8)
                ->get(),
            
            // 2. Tambahkan variabel team di sini
            'team' => User::where('role', 'mekanik')->get(), 

        ]);
    }
}
