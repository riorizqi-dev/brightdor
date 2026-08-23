<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() || $user->hasAnyRole(['admin', 'super_admin']) ? true : null;
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || $user->vendor?->id === $booking->vendor_id;
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->vendor?->id === $booking->vendor_id;
    }
}
