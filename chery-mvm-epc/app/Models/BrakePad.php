<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BrakePad extends Model
{
    protected $table = 'brake_bank_pads';
    protected $fillable = [
        'technical_code', 'name', 'position', 'pad_type',
        'other_compatibility_raw', 'price', 'carton_quantity', 'source_row',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'carton_quantity' => 'integer',
        'source_row' => 'integer',
    ];

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(
            BrakePadVehicle::class,
            'brake_bank_pad_vehicle',
            'brake_pad_id',
            'brake_pad_vehicle_id'
        )->withTimestamps();
    }

    public function images(): HasMany
    {
        return $this->hasMany(BrakePadImage::class)->orderBy('sort_order');
    }

    public function compatibilities(): HasMany
    {
        return $this->hasMany(BrakePadCompatibility::class);
    }
}
