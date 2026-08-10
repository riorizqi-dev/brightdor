<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vendor extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id',
        'vendor_category_id',
        'business_name',
        'slug',
        'description',
        'address',
        'city',
        'province',
        'postal_code',
        'latitude',
        'longitude',
        'phone',
        'whatsapp',
        'website',
        'instagram',
        'rating_avg',
        'rating_count',
        'status',
        'is_verified',
        'verified_at',
        'rejection_reason',
        'is_featured',
        'portfolio_url',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'rating_avg' => 'decimal:2',
            'rating_count' => 'integer',
            'is_verified' => 'boolean',
            'is_featured' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Vendor $vendor): void {
            if (blank($vendor->slug)) {
                $vendor->slug = Str::slug($vendor->business_name) . '-' . Str::random(5);
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('portfolio');
        $this->addMediaCollection('documents');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VendorCategory::class, 'vendor_category_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
