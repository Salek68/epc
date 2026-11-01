<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SellerStore extends Model
{
    protected $fillable = [
        'user_id',
        'store_name',
        'owner_name',
        'phone',
        'website_url',
        'status',
        'admin_note',
        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // کاربری که مالک فروشگاه است
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // محصولات ثبت شده توسط فروشگاه
    public function products(): HasMany
    {
        return $this->hasMany(SellerProduct::class);
    }

    // مدارک احراز هویت فروشگاه
    public function documents(): HasMany
    {
        return $this->hasMany(SellerVerificationDocument::class);
    }

    // بررسی تأیید فروشگاه
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
