<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrakePadCompatibility extends Model
{
    protected $table = 'brake_bank_pad_compatibilities';
    protected $fillable = ['brake_pad_id', 'vehicle_name', 'notes'];

    public function brakePad(): BelongsTo
    {
        return $this->belongsTo(BrakePad::class);
    }
}
