<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Models\Vendor;
use Filament\Auth\Pages\Login;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class LandingPageAuthAndBookingTest extends TestCase
{

    private function admin(): User
    {
        return User::query()->firstOrCreate(
            ['email' => 'admin@brightdor.test'],
            [
                'name' => 'BrightDor Admin',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'status' => 'active',
                'phone' => '081234567890',
            ],
        );
    }

    public function test_admin_login_page_is_accessible(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('BrightDor')
            ->assertSee('Daftar sebagai user di sini');
    }

    public function test_admin_can_log_in_via_filament_login_page(): void
    {
        $this->admin();

        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'admin@brightdor.test',
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect('/admin');
    }

    public function test_admin_can_log_out_from_filament(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post('/admin/logout')
            ->assertRedirect();
    }

    public function test_landing_nav_shows_login_link_for_guests_and_logout_for_admins(): void
    {
        $this->get('/')->assertOk()->assertSee('/login', false);

        $admin = $this->admin();
        $this->actingAs($admin)
            ->get('/')
            ->assertOk()
            ->assertSee($admin->name)
            ->assertSee('/login/logout', false)
            ->assertSee('Keluar');
    }

    public function test_guest_can_submit_a_booking_from_vendor_show_page(): void
    {
        $vendor = Vendor::query()
            ->where('status', 'approved')
            ->with(['services' => fn ($q) => $q->where('status', 'published')->where('is_active', true)])
            ->firstOrFail();

        $service = $vendor->services->first() ?? Service::query()->create([
            'vendor_id' => $vendor->id,
            'name' => 'Paket Reguler',
            'status' => 'published',
            'is_active' => true,
        ]);

        $this->get(route('vendors.show', $vendor->slug))->assertOk();

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.booking', $vendor->slug), [
                'name' => 'Pasangan Dinda',
                'email' => 'dinda@example.test',
                'phone' => '0812999888777',
                'service_id' => $service->id,
                'event_date' => now()->addMonths(6)->toDateString(),
                'guest_count' => 300,
                'customer_notes' => 'Minta dekorasi outdoor.',
            ]);

        $response->assertRedirect()->assertSessionHas('success');

        $booking = Booking::query()
            ->where('vendor_id', $vendor->id)
            ->where('service_id', $service->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($booking);
        $this->assertSame('pending', $booking->status);
        $this->assertSame(300, $booking->guest_count);
        $this->assertSame(now()->addMonths(6)->toDateString(), $booking->event_date->toDateString());
        $this->assertNotNull($booking->booking_code);
        $this->assertTrue(str_starts_with($booking->booking_code, 'BD-'));
    }

    public function test_vendor_show_renders_offer_buttons_that_open_booking_modal(): void
    {
        $vendor = Vendor::query()
            ->where('status', 'approved')
            ->firstOrFail();

        $this->get(route('vendors.show', $vendor->slug))
            ->assertOk()
            // The "Ajukan Penawaran" buttons open the modal in quote mode.
            ->assertSee('data-booking-open="quote"', false)
            ->assertSee('Ajukan Penawaran')
            // "Booking Tanggal" reuses the same form but in date mode, so the two CTAs differ.
            ->assertSee('data-booking-open="date"', false)
            ->assertSee('Booking Tanggal')
            // The booking modal container is present (hidden by default, shown by the JS handler).
            ->assertSee('data-booking-modal', false)
            // Intent is persisted so a failed validation reopens the modal in the same mode.
            ->assertSee('name="request_mode"', false)
            // Submit control is wired for the loading state ("Mengirim...").
            ->assertSee('data-booking-submit', false);
    }

    public function test_booking_validation_errors_flash_back_so_modal_reopens(): void
    {
        $vendor = Vendor::query()
            ->where('status', 'approved')
            ->firstOrFail();

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->from(route('vendors.show', $vendor->slug))
            ->post(route('vendors.booking', $vendor->slug), [
                'name' => '',
                'email' => 'not-an-email',
            ])
            ->assertRedirect(route('vendors.show', $vendor->slug))
            ->assertSessionHasErrors(['name', 'email', 'phone', 'service_id']);

        // Re-rendering with errors must include the flag that auto-opens the modal client-side.
        $this->assertNotNull(
            session()->get('errors'),
            'Validation errors should be flashed back to trigger the modal auto-open path.'
        );
    }

    public function test_booking_validation_rejects_invalid_service(): void
    {
        $vendor = Vendor::query()
            ->where('status', 'approved')
            ->firstOrFail();

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.booking', $vendor->slug), [
                'name' => 'Pasangan Rara',
                'email' => 'rara@example.test',
                'phone' => '0812111222333',
                'service_id' => 999999,
            ])
            ->assertNotFound();
    }
}
