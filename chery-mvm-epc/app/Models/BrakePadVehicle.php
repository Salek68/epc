<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BrakePadVehicle extends Model
{
    protected $table = 'brake_bank_vehicles';
    protected $fillable = ['name', 'normalized_name'];

    public function brakePads(): BelongsToMany
    {
        return $this->belongsToMany(
            BrakePad::class,
            'brake_bank_pad_vehicle',
            'brake_pad_vehicle_id',
            'brake_pad_id'
        )->withTimestamps();
    }
}
