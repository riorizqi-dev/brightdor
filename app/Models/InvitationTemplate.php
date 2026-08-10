<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class InvitationTemplate extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'invitation_template_category_id',
        'name',
        'slug',
        'description',
        'price',
        'preview_image',
        'thumbnail',
        'demo_url',
        'features',
        'is_premium',
        'is_featured',
        'is_active',
        'sales_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'features' => 'array',
            'is_premium' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sales_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (InvitationTemplate $template): void {
            if (blank($template->slug)) {
                $template->slug = Str::slug($template->name);
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('preview')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(InvitationTemplateCategory::class, 'invitation_template_category_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(InvitationOrder::class);
    }
}
