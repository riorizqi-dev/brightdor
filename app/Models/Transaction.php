<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_code',
        'user_id',
        'payable_type',
        'payable_id',
        'type',
        'amount',
        'fee',
        'net_amount',
        'payment_method',
        'payment_gateway',
        'gateway_reference',
        'status',
        'paid_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fee' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Transaction $transaction): void {
            if (blank($transaction->transaction_code)) {
                $transaction->transaction_code = 'TRX-' . strtoupper(Str::random(10));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}
