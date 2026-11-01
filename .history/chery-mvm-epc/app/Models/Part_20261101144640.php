<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Part extends Model
{
    protected $guarded = [];

    public function vehicleModels(): BelongsToMany
    {
        return $this->belongsToMany(
            VehicleModel::class,
            'part_vehicle_model',
            'part_id',
            'vehicle_model_id'
        );
    }
}