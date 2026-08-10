<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invitation_order_id',
        'user_id',
        'invitation_template_id',
        'slug',
        'subdomain',
        'custom_domain',
        'content',
        'theme_settings',
        'views_count',
        'rsvp_yes',
        'rsvp_no',
        'rsvp_maybe',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'theme_settings' => 'array',
            'views_count' => 'integer',
            'rsvp_yes' => 'integer',
            'rsvp_no' => 'integer',
            'rsvp_maybe' => 'integer',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Invitation $invitation): void {
            if (blank($invitation->slug)) {
                $invitation->slug = Str::slug(($invitation->subdomain ?? 'undangan') . '-' . Str::random(4));
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(InvitationOrder::class, 'invitation_order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(InvitationTemplate::class, 'invitation_template_id');
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(InvitationRsvp::class);
    }
}
