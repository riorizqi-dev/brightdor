<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionSetting extends Model
{
    protected $fillable = [
        'vendor_category_id',
        'rate_percent',
        'rate_fixed',
        'is_active',
        'label',
    ];

    protected function casts(): array
    {
        return [
            'rate_percent' => 'decimal:2',
            'rate_fixed' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VendorCategory::class, 'vendor_category_id');
    }
}
