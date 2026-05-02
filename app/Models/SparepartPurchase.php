<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SparepartPurchase extends Model
{
    protected $table = 'sparepart_purchases';

    protected $fillable = [
        'sparepart_id',
        'quantity',
        'unit_price',
        'total_price',
        'purchase_type',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function sparepart(): BelongsTo
    {
        return $this->belongsTo(Sparepart::class);
    }
}