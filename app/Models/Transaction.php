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
        'payment_proof',
        'status',
        'paid_at',
        'expires_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fee' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    /**
     * Transaksi pending yang sudah melewati batas waktu pembayaran.
     * Dipakai untuk auto-expire saat transaksi diakses.
     */
    public function isExpiredPending(): bool
    {
        return $this->status === 'pending'
            && $this->expires_at !== null
            && $this->expires_at->isPast();
    }

    /**
     * Tandai transaksi sebagai expired bila pending dan sudah lewat batas.
     * Mengembalikan true bila status berubah menjadi expired.
     */
    public function expireIfOverdue(): bool
    {
        if (! $this->isExpiredPending()) {
            return false;
        }

        $this->forceFill(['status' => 'expired'])->save();

        return true;
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
