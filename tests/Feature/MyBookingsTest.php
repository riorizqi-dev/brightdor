<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MyBookingsTest extends TestCase
{
    use DatabaseTransactions;

    private function makeCouple(string $email = 'couple@brightdor.test'): User
    {
        return User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Couple User',
                'password' => Hash::make('password'),
                'user_type' => 'couple',
                'status' => 'active',
            ],
        );
    }

    private function makeBooking(User $user, string $status = 'pending'): Booking
    {
        $vendor = Vendor::query()->where('status', 'approved')->firstOrFail();

        return Booking::query()->create([
            'user_id' => $user->id,
            'vendor_id' => $vendor->id,
            'subtotal' => 1000000,
            'total_amount' => 1000000,
            'status' => $status,
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('my-bookings.index'))->assertRedirect(route('frontend.login.create'));
    }

    public function test_couple_sees_only_their_own_bookings(): void
    {
        $couple = $this->makeCouple();
        $other = $this->makeCouple('couple-lain@brightdor.test');

        $mine = $this->makeBooking($couple);
        $theirs = $this->makeBooking($other);

        $this->actingAs($couple)
            ->get(route('my-bookings.index'))
            ->assertOk()
            ->assertSee($mine->booking_code)
            ->assertDontSee($theirs->booking_code);
    }

    public function test_couple_can_cancel_pending_booking(): void
    {
        $couple = $this->makeCouple();
        $booking = $this->makeBooking($couple);

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.cancel', $booking), [
                'cancellation_reason' => 'Berubah rencana.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $booking->refresh();
        $this->assertSame('cancelled', $booking->status);
        $this->assertNotNull($booking->cancelled_at);
        $this->assertSame('Berubah rencana.', $booking->cancellation_reason);
    }

    public function test_couple_cannot_cancel_completed_booking(): void
    {
        $couple = $this->makeCouple();
        $booking = $this->makeBooking($couple, 'completed');

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.cancel', $booking))
            ->assertSessionHasErrors('booking');

        $this->assertSame('completed', $booking->fresh()->status);
    }

    public function test_couple_cannot_cancel_other_users_booking(): void
    {
        $couple = $this->makeCouple();
        $other = $this->makeCouple('couple-lain@brightdor.test');
        $booking = $this->makeBooking($other);

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.cancel', $booking))
            ->assertForbidden();

        $this->assertSame('pending', $booking->fresh()->status);
    }
}
