<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BookingsController extends Controller
{
    public function index()
    {
        return view('bookings.index', [
            'title' => 'Booking',
            'services' => Service::orderBy('service_name')->get(),
            'vehicleBrands' => VehicleBrand::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $client = auth()->guard('client')->user();

        if (!$client) {
            return redirect()->route('client.loginForm');
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'vehicle_brand_id' => 'required|exists:vehicle_brands,id',
            'vehicle_model_id' => [
                'required',
                Rule::exists('vehicle_models', 'id')
                    ->where(fn ($query) => $query->where('vehicle_brand_id', $request->vehicle_brand_id)),
            ],
            'license_plate' => 'required|string|max:20',
            'color' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'complaint' => 'required|string|max:1000',
        ]);

        $vehicleModel = VehicleModel::with('brand')->findOrFail($validated['vehicle_model_id']);
        $licensePlate = strtoupper(preg_replace('/\s+/', ' ', trim($validated['license_plate'])));

        DB::transaction(function () use ($client, $validated, $vehicleModel, $licensePlate) {
            // Simpan atau cari kendaraan berdasarkan user + plat agar tidak duplikat per customer.
            $vehicle = Vehicle::firstOrCreate(
                [
                    'user_id' => $client->id,
                    'license_plate' => $licensePlate,
                ],
                [
                    'vehicle_brand_id' => $vehicleModel->vehicle_brand_id,
                    'vehicle_model_id' => $vehicleModel->id,
                    'brand' => $vehicleModel->brand->name,
                    'model' => $vehicleModel->name,
                    'color' => $validated['color'],
                    'year' => $validated['year'],
                ]
            );

            Booking::create([
                'user_id' => $client->id,
                'service_id' => $validated['service_id'],
                'vehicle_id' => $vehicle->id,
                'booking_date' => $validated['booking_date'],
                'booking_time' => $validated['booking_time'],
                'status' => 'pending',
                'complaint' => $validated['complaint'],
            ]);
        });

        return redirect()
            ->route('customer.bookings.index')
            ->with('success', 'Booking berhasil dibuat.');
    }
}
