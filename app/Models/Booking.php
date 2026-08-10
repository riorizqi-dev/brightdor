<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_code',
        'user_id',
        'vendor_id',
        'service_id',
        'event_date',
        'event_time',
        'event_location',
        'guest_count',
        'subtotal',
        'discount',
        'admin_fee',
        'commission_amount',
        'total_amount',
        'status',
        'customer_notes',
        'admin_notes',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'admin_fee' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'guest_count' => 'integer',
            'confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking): void {
            if (blank($booking->booking_code)) {
                $booking->booking_code = 'BD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'payable');
    }
}
