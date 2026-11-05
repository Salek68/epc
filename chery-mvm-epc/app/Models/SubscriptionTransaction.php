<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SubscriptionTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'duration_days',
        'amount',
        'status',
        'document_path',
        'admin_note',
        'approved_at',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'amount' => 'integer',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'transaction_id');
    }
}
