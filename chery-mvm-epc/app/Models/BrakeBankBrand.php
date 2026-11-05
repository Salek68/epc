<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrakeBankBrand extends Model
{
    protected $table = 'brake_bank_brands';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function sellerOffers()
    {
        return $this->belongsToMany(
            BrakeBankSellerOffer::class,
            'brake_bank_seller_offer_brand',
            'brand_id',
            'seller_offer_id'
        )->withTimestamps();
    }
}