<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrakeBankSellerOffer extends Model
{
    protected $table = 'brake_bank_seller_offers';

    protected $fillable = [
        'seller_store_id',
        'brake_pad_id',
        'product_url',
        'image_url',
        'status',
        'admin_note',
    ];

    public function sellerStore()
    {
        return $this->belongsTo(
            SellerStore::class,
            'seller_store_id'
        );
    }

    public function brakePad()
    {
        return $this->belongsTo(
            BrakePad::class,
            'brake_pad_id'
        );
    }

    public function brands()
    {
        return $this->belongsToMany(
            BrakeBankBrand::class,
            'brake_bank_seller_offer_brand',
            'seller_offer_id',
            'brand_id'
        )->withTimestamps();
    }
}