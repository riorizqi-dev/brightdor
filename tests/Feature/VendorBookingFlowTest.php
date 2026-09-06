<?php

namespace Tests\Feature;

use App\Filament\Vendor\Resources\VendorBooking\Pages\ListVendorBookings;
use App\Filament\Vendor\Resources\VendorBooking\VendorBookingResource as VendorResource;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Models\Vendor;
use Filament\Facades\Filament;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VendorBookingFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('vendor');
    }

    private function vendorUser(): User
    {
        $user = User::query()->create([
            'name' => 'Bunga Alya',
            'email' => 'vendor@example.test',
            'phone' => '0812333444555',
            'password' => 'rahasia123',
            'user_type' => 'vendor',
            'status' => 'active',
        ]);
        $user->assignRole('vendor');

        return $user;
    }

    private function vendorAndService(User $user): Vendor
    {
        $vendor = Vendor::query()->create([
            'user_id' => $user->id,
            'business_name' => 'Studio Bunga Alya',
            'city' => 'Jakarta Selatan',
            'status' => 'approved',
            'is_verified' => true,
            'rating_avg' => 4.8,
            'rating_count' => 12,
        ]);

        Service::query()->create([
            'vendor_id' => $vendor->id,
            'name' => 'Paket Uji Vendor',
            'price' => 2_000_000,
            'status' => 'published',
            'is_active' => true,
        ]);

        return $vendor;
    }

    private function couple(string $email = 'couple@example.test'): User
    {
        return User::query()->create([
            'name' => 'Pasangan Alya',
            'email' => $email,
            'phone' => '0812444555666',
            'password' => 'rahasia123',
            'user_type' => 'couple',
            'status' => 'active',
        ]);
    }

    private function createBooking(User $couple): Booking
    {
        [$user, $vendor] = [User::query()->where('email', 'vendor@example.test')->firstOrFail(), null];
        $vendor = $vendor ?? Vendor::query()->where('user_id', $user->id)->firstOrFail();
        $service = $vendor->services()->where('status', 'published')->firstOrFail();

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.booking', $vendor->slug), [
                'name' => $couple->name,
                'email' => $couple->email,
                'phone' => $couple->phone,
                'service_id' => $service->id,
                'event_date' => now()->addMonths(2)->toDateString(),
                'guest_count' => 150,
            ])
            ->assertSessionHas('success');

        return Booking::query()->where('user_id', $couple->id)->latest('id')->firstOrFail();
    }

    public function test_vendor_can_see_own_incoming_bookings(): void
    {
        $vendorUser = $this->vendorUser();
        $this->vendorAndService($vendorUser);
        $booking = $this->createBooking($this->couple());

        Filament::setCurrentPanel(Filament::getPanel('vendor'));

        $this->actingAs($vendorUser)
            ->get('/vendor/vendor-booking/vendor-bookings')
            ->assertOk()
            ->assertSee('Booking Masuk');

        Livewire::actingAs($vendorUser)
            ->test(ListVendorBookings::class)
            ->assertSuccessful()
            ->assertSee($booking->booking_code);
    }

    public function test_vendor_does_not_see_other_vendors_bookings(): void
    {
        $vendorUser = $this->vendorUser();
        $this->vendorAndService($vendorUser);
        $otherUser = User::query()->create([
            'name' => 'Lain',
            'email' => 'vendor-lain@example.test',
            'phone' => '0812888777666',
            'password' => 'rahasia123',
            'user_type' => 'vendor',
            'status' => 'active',
        ]);
        $otherUser->assignRole('vendor');
        $otherVendor = Vendor::query()->create([
            'user_id' => $otherUser->id,
            'business_name' => 'Vendor Lain',
            'city' => 'Bandung',
            'status' => 'approved',
        ]);
        $otherService = Service::query()->create([
            'vendor_id' => $otherVendor->id,
            'name' => 'Paket Vendor Lain',
            'price' => 1_500_000,
            'status' => 'published',
            'is_active' => true,
        ]);

        $couple = $this->couple('couple2@example.test');
        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.booking', $otherVendor->slug), [
                'name' => $couple->name,
                'email' => $couple->email,
                'phone' => $couple->phone,
                'service_id' => $otherService->id,
                'event_date' => now()->addMonths(2)->toDateString(),
                'guest_count' => 150,
            ])
            ->assertSessionHas('success');

        $this->actingAs($vendorUser)
            ->get('/vendor/vendor-booking/vendor-bookings')
            ->assertOk()
            ->assertDontSee($otherVendor->business_name);
    }

    public function test_vendor_can_confirm_then_start_then_complete_booking(): void
    {
        $vendorUser = $this->vendorUser();
        $this->vendorAndService($vendorUser);
        $booking = $this->createBooking($this->couple());

        $this->actingAs($vendorUser);

        Filament::setCurrentPanel(Filament::getPanel('vendor'));

        Livewire::test(ListVendorBookings::class)
            ->callTableAction('confirm', $booking)
            ->assertHasNoTableActionErrors();

        $this->assertSame('confirmed', $booking->refresh()->status);
        $this->assertNotNull($booking->confirmed_at);

        Livewire::test(ListVendorBookings::class)
            ->callTableAction('start', $booking)
            ->assertHasNoTableActionErrors();

        $this->assertSame('on_progress', $booking->refresh()->status);

        Livewire::test(ListVendorBookings::class)
            ->callTableAction('complete', $booking)
            ->assertHasNoTableActionErrors();

        $this->assertSame('completed', $booking->refresh()->status);
        $this->assertNotNull($booking->completed_at);
    }

    public function test_vendor_can_reject_pending_booking_with_reason(): void
    {
        $vendorUser = $this->vendorUser();
        $this->vendorAndService($vendorUser);
        $booking = $this->createBooking($this->couple());

        $this->actingAs($vendorUser);

        Filament::setCurrentPanel(Filament::getPanel('vendor'));

        Livewire::test(ListVendorBookings::class)
            ->callTableAction('reject', $booking, data: [
                'cancellation_reason' => 'Jadwal sudah penuh',
            ])
            ->assertHasNoTableActionErrors();

        $booking->refresh();

        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('Jadwal sudah penuh', $booking->cancellation_reason);
        $this->assertNotNull($booking->cancelled_at);
    }

    public function test_vendor_can_open_booking_detail_page(): void
    {
        $vendorUser = $this->vendorUser();
        $this->vendorAndService($vendorUser);
        $booking = $this->createBooking($this->couple());

        $this->actingAs($vendorUser);

        Filament::setCurrentPanel(Filament::getPanel('vendor'));

        $this->get(VendorResource::getUrl('view', ['record' => $booking]))
            ->assertOk()
            ->assertSee($booking->booking_code)
            ->assertSee($booking->user->name);
    }

    public function test_vendor_cannot_access_other_vendors_booking_detail(): void
    {
        $vendorUser = $this->vendorUser();
        $this->vendorAndService($vendorUser);
        $otherUser = User::query()->create([
            'name' => 'Lain',
            'email' => 'vendor-xy@example.test',
            'phone' => '0812000111222',
            'password' => 'rahasia123',
            'user_type' => 'vendor',
            'status' => 'active',
        ]);
        $otherUser->assignRole('vendor');
        $otherVendor = Vendor::query()->create([
            'user_id' => $otherUser->id,
            'business_name' => 'Vendor XY',
            'city' => 'Surabaya',
            'status' => 'approved',
        ]);
        $otherService = Service::query()->create([
            'vendor_id' => $otherVendor->id,
            'name' => 'Paket XY',
            'price' => 900_000,
            'status' => 'published',
            'is_active' => true,
        ]);
        $couple = $this->couple('couple3@example.test');
        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.booking', $otherVendor->slug), [
                'name' => $couple->name,
                'email' => $couple->email,
                'phone' => $couple->phone,
                'service_id' => $otherService->id,
                'event_date' => now()->addMonths(2)->toDateString(),
                'guest_count' => 50,
            ])
            ->assertSessionHas('success');
        $otherBooking = Booking::query()->where('user_id', $couple->id)->latest('id')->firstOrFail();

        Filament::setCurrentPanel(Filament::getPanel('vendor'));

        $this->actingAs($vendorUser)
            ->get(VendorResource::getUrl('view', ['record' => $otherBooking]))
            ->assertNotFound();
    }
}