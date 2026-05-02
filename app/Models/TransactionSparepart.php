<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionSparepart extends Model
{
    protected $table = 'transaction_spareparts';

    protected $fillable = [
        'transaction_id',
        'sparepart_id',
        'qty',
        'price',
        'subtotal'
    ];

    // relasi ke transaksi
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // relasi ke sparepart
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}