<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReviewTest extends TestCase
{

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

    private function makeBooking(User $user, string $status = 'completed'): Booking
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

    public function test_guest_redirected_to_login(): void
    {
        $couple = $this->makeCouple();
        $booking = $this->makeBooking($couple);

        $this->get(route('my-bookings.review.create', $booking))
            ->assertRedirect(route('frontend.login.create'));
    }

    public function test_couple_can_view_review_form_for_completed_booking(): void
    {
        $couple = $this->makeCouple();
        $booking = $this->makeBooking($couple);

        $this->actingAs($couple)
            ->get(route('my-bookings.review.create', $booking))
            ->assertOk()
            ->assertSee('Beri Review');
    }

    public function test_couple_cannot_review_non_completed_booking(): void
    {
        $couple = $this->makeCouple();
        $booking = $this->makeBooking($couple, 'confirmed');

        $this->actingAs($couple)
            ->get(route('my-bookings.review.create', $booking))
            ->assertForbidden();
    }

    public function test_couple_cannot_review_other_users_booking(): void
    {
        $couple = $this->makeCouple();
        $other = $this->makeCouple('couple2@brightdor.test');
        $booking = $this->makeBooking($other);

        $this->actingAs($couple)
            ->get(route('my-bookings.review.create', $booking))
            ->assertForbidden();
    }

    public function test_couple_can_submit_review(): void
    {
        $couple = $this->makeCouple();
        $booking = $this->makeBooking($couple);

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.review.store', $booking), [
                'rating' => 5,
                'content' => 'Pelayanan sangat memuaskan!',
            ])
            ->assertRedirect(route('my-bookings.index'))
            ->assertSessionHas('success');

        $booking->refresh();
        $this->assertNotNull($booking->review);
        $this->assertSame(5, $booking->review->rating);
        $this->assertSame('Pelayanan sangat memuaskan!', $booking->review->content);
    }

    public function test_review_updates_vendor_rating(): void
    {
        $couple = $this->makeCouple();
        $booking = $this->makeBooking($couple);
        $vendor = $booking->vendor;

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.review.store', $booking), [
                'rating' => 5,
                'content' => 'Pelayanan sangat memuaskan!',
            ]);

        $vendor->refresh();
        $this->assertEquals(5.00, $vendor->rating_avg);
        $this->assertSame(1, $vendor->rating_count);
    }

    public function test_cannot_submit_duplicate_review(): void
    {
        $couple = $this->makeCouple();
        $booking = $this->makeBooking($couple);

        Review::create([
            'vendor_id' => $booking->vendor_id,
            'service_id' => $booking->service_id,
            'booking_id' => $booking->id,
            'user_id' => $couple->id,
            'rating' => 4,
            'content' => 'Review pertama',
            'is_verified' => true,
        ]);

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.review.store', $booking), [
                'rating' => 5,
                'content' => 'Review kedua',
            ])
            ->assertSessionHasErrors('booking');
    }

    public function test_review_validation_requires_rating(): void
    {
        $couple = $this->makeCouple();
        $booking = $this->makeBooking($couple);

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.review.store', $booking), [
                'content' => 'Tanpa rating',
            ])
            ->assertSessionHasErrors('rating');
    }

    public function test_review_validation_rating_range(): void
    {
        $couple = $this->makeCouple();
        $booking = $this->makeBooking($couple);

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.review.store', $booking), [
                'rating' => 6,
            ])
            ->assertSessionHasErrors('rating');
    }
}