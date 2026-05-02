<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $services = Service::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('service_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->latest('id')
            ->get();

        return view('service.index', compact('services', 'search'), [
            'title' => 'Service'
        ]);
    }

    public function create()
    {
        return view('service.create', [
            'title' => 'Service'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required',
            'price' => 'required|numeric',
            'estimated_time' => 'required|numeric'
        ]);

        Service::create($request->all());

        return redirect('/admin/service')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('service.edit', compact('service'), [
            'title' => 'Service'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required',
            'price' => 'required|numeric',
            'estimated_time' => 'required|numeric'
        ]);

        $service = Service::findOrFail($id);
        $service->update($request->all());

        return redirect('/admin/service')->with('success', 'Data berhasil diupdate!');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return back()->with('success', 'Data berhasil dihapus!');
}
}
