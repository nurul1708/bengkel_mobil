<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Sparepart extends Model
{
    protected $table = 'spareparts';

    protected $fillable = [
        'name',
        'brand',
        'stock',
        'harga_beli',
        'harga_jual',
        'gambar',
        'purchase_date',
        'last_purchase_price'
    ];

    public function setGambarAttribute($value): void
    {
        if (!$value) {
            $this->attributes['gambar'] = null;
            return;
        }

        $normalized = str_replace('\\', '/', $value);
        $fileName = basename($normalized);

        if (str_starts_with($normalized, 'spareparts/')) {
            $this->attributes['gambar'] = 'spareparts/' . $fileName;
            return;
        }

        $this->attributes['gambar'] = $fileName;
    }

    public function getGambarFileAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        $normalized = str_replace('\\', '/', $this->gambar);

        return basename($normalized);
    }

    public function getGambarUrlAttribute(): string
    {
        $path = str_replace('\\', '/', $this->gambar ?? '');
        $fileName = $this->gambar_file;

        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        if ($fileName && Storage::disk('public')->exists('spareparts/' . $fileName)) {
            return asset('storage/spareparts/' . $fileName);
        }

        return asset('be/assets/assets/img/no-img.jpg');
    }
}
