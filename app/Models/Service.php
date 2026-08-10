<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'vendor_category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'price_unit',
        'capacity',
        'duration',
        'location',
        'features',
        'is_featured',
        'is_active',
        'status',
        'views_count',
        'bookings_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'features' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'capacity' => 'integer',
            'views_count' => 'integer',
            'bookings_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Service $service): void {
            if (blank($service->slug)) {
                $service->slug = Str::slug($service->name) . '-' . Str::random(5);
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VendorCategory::class, 'vendor_category_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getFinalPriceAttribute(): float
    {
        return (float) ($this->discount_price ?? $this->price);
    }
}
