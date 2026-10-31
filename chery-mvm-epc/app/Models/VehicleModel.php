<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VehicleModel extends Model
{
    protected $table = 'vehicle_models';

    protected $guarded = [];

    public function parts(): BelongsToMany
    {
        return $this->belongsToMany(
            Part::class,
            'part_vehicle_model',
            'vehicle_model_id',
            'part_id'
        );
    }
}