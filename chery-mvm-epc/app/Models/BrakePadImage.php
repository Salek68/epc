<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrakePadImage extends Model
{
    protected $table = 'brake_bank_pad_images';
    protected $fillable = ['brake_pad_id', 'image_path', 'is_primary', 'sort_order'];
    protected $casts = ['is_primary' => 'boolean', 'sort_order' => 'integer'];

    public function brakePad(): BelongsTo
    {
        return $this->belongsTo(BrakePad::class);
    }
}
