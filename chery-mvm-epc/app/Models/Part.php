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
 public function activeSellerProducts()
{
    return $this->hasMany(SellerProduct::class, 'part_id')
        ->where('is_active', true)
        ->whereHas('store', function ($query) {
            $query->where('status', 'approved');
        });
}

public function store()
{
    return $this->belongsTo(
        \App\Models\SellerStore::class,
        'seller_store_id'
    );
}
}
