<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class InvitationOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_code',
        'user_id',
        'invitation_template_id',
        'bride_name',
        'groom_name',
        'wedding_date',
        'wedding_venue',
        'subdomain',
        'custom_domain',
        'price',
        'status',
        'paid_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'wedding_date' => 'date',
            'price' => 'decimal:2',
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (InvitationOrder $order): void {
            if (blank($order->order_code)) {
                $order->order_code = 'INV-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(InvitationTemplate::class, 'invitation_template_id');
    }

    public function invitation(): HasOne
    {
        return $this->hasOne(Invitation::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'payable');
    }
}
