<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sparepart;
use App\Models\SparepartPurchase;
use Illuminate\Support\Facades\Storage;

class SparepartController extends Controller
{
    private function normalizeStoredImagePath(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $normalized = str_replace('\\', '/', $value);

        if (str_starts_with($normalized, 'spareparts/')) {
            return 'spareparts/' . basename($normalized);
        }

        return 'spareparts/' . basename($normalized);
    }

    private function deleteImageIfUnused(?string $storedPath, ?int $ignoreId = null): void
    {
        if (!$storedPath) {
            return;
        }

        $normalizedPath = $this->normalizeStoredImagePath($storedPath);
        $legacyFileName = basename(str_replace('\\', '/', $storedPath));

        $query = Sparepart::query()->where(function ($builder) use ($normalizedPath, $legacyFileName) {
            $builder->where('gambar', $normalizedPath)
                ->orWhere('gambar', $legacyFileName);
        });

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        if (!$query->exists()) {
            Storage::disk('public')->delete($normalizedPath);
        }
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $spareparts = Sparepart::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('brand', 'like', '%' . $search . '%');
            })
            ->latest('id')
            ->get();

        return view('spareparts.index', compact('spareparts', 'search'), [
            'title' => 'Spareparts'
        ]);
    }

    public function create()
    {
        return view('spareparts.create', [
            'title' => 'Spareparts'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'brand' => 'required',
            'stock' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'gambar' => 'nullable'
        ]);

        $data = $request->only(['name', 'brand', 'stock', 'harga_beli', 'harga_jual']);

        // Track purchase info for expense calculation
        $data['purchase_date'] = now()->toDateString();
        $data['last_purchase_price'] = $request->harga_beli;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $folderPath = storage_path('app/public/spareparts');

            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            $file->move($folderPath, $namaFile);
            $data['gambar'] = 'spareparts/' . $namaFile;
        }

        $sparepart = Sparepart::create($data);

        // Record initial purchase for expense tracking
        SparepartPurchase::create([
            'sparepart_id' => $sparepart->id,
            'quantity' => $request->stock,
            'unit_price' => $request->harga_beli,
            'total_price' => $request->stock * $request->harga_beli,
            'purchase_type' => 'initial',
            'notes' => 'Initial stock purchase'
        ]);

        return redirect('/admin/spareparts')->with('success','Data berhasil ditambah');
    }

    public function show($id)
    {
        $spareparts = Sparepart::findOrFail($id);
        return view('spareparts.show', compact('spareparts'), [
            'title' => 'Spareparts'
        ]);
    }
    public function edit($id)
    {
        $spareparts = Sparepart::findOrFail($id);
        return view('spareparts.edit', compact('spareparts'), [
            'title' => 'Spareparts'
        ]);
    }

   public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'brand' => 'required',
        'stock' => 'required|numeric',
        'harga_beli' => 'required|numeric',
        'harga_jual' => 'required|numeric',
        'gambar' => 'nullable'
    ]);

    $sparepart = Sparepart::findOrFail($id);
    $data = $request->only(['name', 'brand', 'stock', 'harga_beli', 'harga_jual']);

    // Track purchase when stock or harga_beli changes
    $stockChange = $request->stock - $sparepart->stock;
    $priceChanged = $request->harga_beli != $sparepart->harga_beli;

    if ($stockChange > 0 || $priceChanged) {
        $data['purchase_date'] = now()->toDateString();
        $data['last_purchase_price'] = $request->harga_beli;

        // Record stock addition
        if ($stockChange > 0) {
            SparepartPurchase::create([
                'sparepart_id' => $sparepart->id,
                'quantity' => $stockChange,
                'unit_price' => $request->harga_beli,
                'total_price' => $stockChange * $request->harga_beli,
                'purchase_type' => 'stock_add',
                'notes' => 'Stock addition'
            ]);
        }

        // Record price update (even if stock unchanged)
        if ($priceChanged && $stockChange <= 0) {
            SparepartPurchase::create([
                'sparepart_id' => $sparepart->id,
                'quantity' => $sparepart->stock,
                'unit_price' => $request->harga_beli,
                'total_price' => $sparepart->stock * $request->harga_beli,
                'purchase_type' => 'price_update',
                'notes' => 'Price update'
            ]);
        }
    }

    if ($request->hasFile('gambar')) {
        $oldFile = $sparepart->gambar;
        $file = $request->file('gambar');
        $namaFile = time() . "_" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $folderPath = storage_path('app/public/spareparts');

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $file->move($folderPath, $namaFile);
        $data['gambar'] = 'spareparts/' . $namaFile;

        $this->deleteImageIfUnused($oldFile, $sparepart->id);
    }

    $sparepart->update($data);

    return redirect('/admin/spareparts')->with('success', 'Data berhasil diupdate');
}

    public function destroy($id)
    {
        $spareparts = Sparepart::findOrFail($id);

        $this->deleteImageIfUnused($spareparts->gambar, $spareparts->id);

        $spareparts->delete();

        return back()->with('success','Data berhasil dihapus');
    }
}
