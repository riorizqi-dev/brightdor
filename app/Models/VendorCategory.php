<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class VendorCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'commission_rate',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (VendorCategory $category): void {
            if (blank($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function commissionSettings(): HasMany
    {
        return $this->hasMany(CommissionSetting::class);
    }
}
