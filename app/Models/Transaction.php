<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'booking_id',
        'service_id',
        'mekanik_id',
        'kasir_id',
        'status',
        'total_service',
        'total_sparepart',
        'grand_total',
        'items'
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function mekanik()
    {
        return $this->belongsTo(User::class, 'mekanik_id');
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    // Tambahkan ini di dalam class Transaction

public function payments()
{
    // Karena satu transaksi bisa dicicil (status partial), 
    // maka hubungannya adalah hasMany
    return $this->hasMany(Payment::class, 'transaction_id');
}

    public function transactionSpareparts()
    {
        return $this->hasMany(TransactionSparepart::class, 'transaction_id');
    }

    public function testimonial()
    {
        return $this->hasOne(Testimonial::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'paid' => '<span class="badge bg-success">Lunas</span>',
            'partial' => '<span class="badge bg-warning">Sebagian</span>',
            default => '<span class="badge bg-danger">Belum Bayar</span>',
        };
    }
}
