<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

protected $fillable = [
        'user_id',
        'service_id',
        'vehicle_id',
        'booking_date',
        'booking_time',
        'status',
        'complaint',
        'mekanik_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function mekanik()
    {
        return $this->belongsTo(User::class, 'mekanik_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'booking_id');
    }

    public function testimonial()
    {
        return $this->hasOne(Testimonial::class);
    }

    public function getBookingDateLabelAttribute(): string
    {
        if (empty($this->booking_date) || $this->booking_date === '0000-00-00') {
            return '-';
        }

        return Carbon::parse($this->booking_date)->format('d-m-Y');
    }

    public function getBookingTimeLabelAttribute(): string
    {
        if (empty($this->booking_time) || $this->booking_time === '00:00:00') {
            return '-';
        }

        return Carbon::parse($this->booking_time)->format('H:i');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'confirmed' => '<span class="badge bg-success">Diterima</span>',
            'in_progress' => '<span class="badge bg-primary">Sedang Dikerjakan</span>',
            'completed' => '<span class="badge bg-warning">Selesai - Menunggu Pembayaran</span>',
            'paid' => '<span class="badge bg-success">Lunas</span>',
            'cancelled' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-secondary">Menunggu</span>',
        };
    }

    public function getCanStartServiceAttribute()
    {
        return $this->status === 'confirmed';
    }

    public function getCanFinishServiceAttribute()
    {
        return $this->status === 'in_progress';
    }

    public function getCanPayAttribute()
    {
        return $this->status === 'completed';
    }
}
