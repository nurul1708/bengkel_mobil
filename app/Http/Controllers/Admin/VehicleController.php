<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\User;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $vehicles = Vehicle::with(['user', 'latestBooking'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where('license_plate', 'like', '%' . $search . '%');
            })
            ->latest('id')
            ->get();

        return view('vehicle.index', compact('vehicles'), [
            'search' => $search,
            'title' => 'Vehicle'
        ]);
      
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'customer')->get();
        return view('vehicle.create', compact('users'), [
            'title' => 'Vehicle'
        ]);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'brand' => 'required',
            'model' => 'required',
            'license_plate' => 'required|unique:vehicles,license_plate'
        ]);

        Vehicle::create($request->all());

        return redirect('/admin/vehicle')->with('success','Data berhasil ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vehicle = Vehicle::with(['user', 'latestBooking'])->findOrFail($id);

        return view('vehicle.show', compact('vehicle'), [
            'title' => 'Vehicle'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $users = User::where('role', 'customer')->get();
        return view('vehicle.edit', compact('vehicle', 'users'), [
            'title' => 'Vehicle'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'brand' => 'required',
            'model' => 'required',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'license_plate' => 'required|unique:vehicles,license_plate,' . $id,
            'color' => 'nullable|string'
        ]);

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->all());

        return redirect('/admin/vehicle')->with('success','Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return back()->with('success','Data berhasil dihapus');
    }
}
