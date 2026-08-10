<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payout extends Model
{
    protected $fillable = [
        'payout_code',
        'vendor_id',
        'amount',
        'fee',
        'net_amount',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'status',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fee' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Payout $payout): void {
            if (blank($payout->payout_code)) {
                $payout->payout_code = 'PO-' . strtoupper(Str::random(8));
            }
        });
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
