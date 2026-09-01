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

    protected $attributes = [
        'user_type' => 'couple',
        'status' => 'active',
    ];

    /**
     * Subscription is active and paid for.
     */
    public const VENDOR_SUBSCRIPTION_ACTIVE = 'active';

    /**
     * Subscription was paid for once, but the period has lapsed.
     */
    public const VENDOR_SUBSCRIPTION_EXPIRED = 'expired';

    /**
     * Never subscribed (or explicitly deactivated).
     */
    public const VENDOR_SUBSCRIPTION_NONE = 'none';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            // Without this cast the column comes back as a raw string and every
            // `->isFuture()` call on it throws, so subscription checks blew up.
            'vendor_subscription_expires_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

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

    /**
     * Resolve the vendor subscription into one of three explicit states.
     *
     * Kept as a single source of truth so the panel middleware and the vendor
     * registration gate can never disagree about who has paid.
     *
     * @return self::VENDOR_SUBSCRIPTION_*
     */
    public function vendorSubscriptionState(): string
    {
        $status = is_string($this->vendor_subscription_status)
            ? strtolower(trim($this->vendor_subscription_status))
            : '';

        $expiresAt = $this->vendor_subscription_expires_at;

        // An explicit expiry in the past always wins, even if the stored status
        // was never flipped by the payment callback / admin approval.
        if ($expiresAt !== null && $expiresAt->isPast()) {
            return self::VENDOR_SUBSCRIPTION_EXPIRED;
        }

        if ($status === self::VENDOR_SUBSCRIPTION_EXPIRED) {
            return self::VENDOR_SUBSCRIPTION_EXPIRED;
        }

        // Accept the values the payment/approval paths actually write, so a
        // wording mismatch stops locking paying vendors out.
        if (in_array($status, ['active', 'paid', 'subscribed', 'success'], true)) {
            return self::VENDOR_SUBSCRIPTION_ACTIVE;
        }

        return self::VENDOR_SUBSCRIPTION_NONE;
    }

    public function hasPaidVendorSubscription(): bool
    {
        return $this->vendorSubscriptionState() === self::VENDOR_SUBSCRIPTION_ACTIVE;
    }

    public function hasExpiredVendorSubscription(): bool
    {
        return $this->vendorSubscriptionState() === self::VENDOR_SUBSCRIPTION_EXPIRED;
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }
}
