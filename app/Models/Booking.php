<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

            self::validateServiceVendor($booking);
        });

        static::updating(function (Booking $booking): void {
            if ($booking->isDirty('status') && ! self::isValidStatusTransition($booking->getOriginal('status'), $booking->status)) {
                throw ValidationException::withMessages([
                    'status' => 'Perubahan status booking tidak valid.',
                ]);
            }

            self::validateServiceVendor($booking);
        });
    }

    private static function validateServiceVendor(Booking $booking): void
    {
        if ($booking->service_id && $booking->service && (int) $booking->service->vendor_id !== (int) $booking->vendor_id) {
            throw ValidationException::withMessages([
                'service_id' => 'Paket yang dipilih bukan milik vendor tersebut.',
            ]);
        }
    }

    public static function isValidStatusTransition(?string $from, ?string $to): bool
    {
        if ($from === $to) {
            return true;
        }

        return in_array($to, match ($from) {
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['on_progress', 'cancelled'],
            'on_progress' => ['completed', 'cancelled'],
            'cancelled' => ['refund'],
            default => [],
        }, true);
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

    public function review(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Review::class);
    }
}
