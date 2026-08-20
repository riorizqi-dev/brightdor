<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'user_type',
        'status',
        'vendor_subscription_status',
        'vendor_subscription_plan',
        'vendor_subscription_expires_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => in_array($this->user_type, ['admin'], true)
                || $this->hasRole(['super_admin', 'admin']),
            'vendor' => $this->isVendor() || $this->hasRole('vendor'),
            default => false,
        };
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function invitationOrders(): HasMany
    {
        return $this->hasMany(InvitationOrder::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    public function isCouple(): bool
    {
        return $this->user_type === 'couple';
    }

    public function isVendor(): bool
    {
        return $this->user_type === 'vendor';
    }

    public function hasPaidVendorSubscription(): bool
    {
        return $this->user_type === 'vendor'
            || ($this->vendor_subscription_status === 'active'
                && ($this->vendor_subscription_expires_at === null || $this->vendor_subscription_expires_at->isFuture()));
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }
}
