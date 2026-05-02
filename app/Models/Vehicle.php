<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';

protected $fillable = [
    'user_id',
    'vehicle_brand_id',
    'vehicle_model_id',
    'brand',
    'model',
    'year',
    'license_plate',
    'color'
];

public function user(){
    return $this->belongsTo(User::class);
}

public function bookings()
{
    return $this->hasMany(Booking::class);
}

public function latestBooking()
{
    return $this->hasOne(Booking::class)->latestOfMany();
}

public function brandMaster()
{
    return $this->belongsTo(VehicleBrand::class, 'vehicle_brand_id');
}

public function modelMaster()
{
    return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
}
}
