<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerVerificationDocument extends Model
{
    protected $fillable = [
        'seller_store_id',
        'document_type',
        'file_path',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(
            SellerStore::class,
            'seller_store_id'
        );
    }
}
