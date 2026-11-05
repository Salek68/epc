<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SellerProduct extends Model
{
    protected $fillable = [
        'seller_store_id',
        'part_id',
        'product_url',
        'image_url',
        'click_count',
        'is_active',
    ];

    protected $casts = [
        'click_count' => 'integer',
        'is_active' => 'boolean',
    ];

    // فروشگاهی که این محصول را ثبت کرده
    public function store(): BelongsTo
    {
        return $this->belongsTo(
            SellerStore::class,
            'seller_store_id'
        );
    }

  public function store()
{
    return $this->belongsTo(
        \App\Models\SellerStore::class,
        'seller_store_id'
    );
}

    // تاریخچه کلیک‌های محصول
    public function clicks(): HasMany
    {
        return $this->hasMany(SellerProductClick::class);
    }

    // افزایش تعداد کلیک
    public function incrementClicks(): void
    {
        $this->increment('click_count');
    }
}
