<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VendorRegistrationTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('vendor');
    }

    public function test_guest_is_redirected_to_login_from_vendor_registration_page(): void
    {
        $this->get(route('vendors.register.create'))
            ->assertRedirect(route('frontend.login.create'));
    }

    public function test_logged_in_couple_without_paid_vendor_subscription_cannot_register_as_vendor(): void
    {
        $user = User::query()->create([
            'name' => 'User BrightDor',
            'email' => 'user@example.test',
            'phone' => '0812333444555',
            'password' => Hash::make('rahasia123'),
            'user_type' => 'couple',
            'status' => 'active',
            'vendor_subscription_status' => 'inactive',
        ]);

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($user)
            ->post(route('vendors.register.store'), [
                'name' => 'Studio Bunga Alya',
                'phone' => '0812333444555',
                'subscription_plan' => 'premium_monthly',
            ])
            ->assertForbidden();

        $user->refresh();

        $this->assertSame('couple', $user->user_type);
        $this->assertFalse($user->hasRole('vendor'));
    }

    public function test_logged_in_couple_can_register_as_vendor_after_paid_subscription(): void
    {
        $user = User::query()->create([
            'name' => 'User BrightDor',
            'email' => 'user@example.test',
            'phone' => '0812333444555',
            'password' => Hash::make('rahasia123'),
            'user_type' => 'couple',
            'status' => 'active',
            'vendor_subscription_status' => 'active',
            'vendor_subscription_plan' => 'premium_monthly',
        ]);

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($user)
            ->post(route('vendors.register.store'), [
                'name' => 'Studio Bunga Alya',
                'phone' => '0812333444555',
                'subscription_plan' => 'premium_monthly',
            ])
            ->assertRedirect('/vendor');

        $user->refresh();

        $this->assertSame('vendor', $user->user_type);
        $this->assertTrue($user->hasRole('vendor'));
        $this->assertSame('active', $user->vendor_subscription_status);
    }

    public function test_couple_with_active_subscription_and_future_expiry_can_register_as_vendor(): void
    {
        $user = User::query()->create([
            'name' => 'User Berlangganan',
            'email' => 'aktif@example.test',
            'phone' => '0812333444777',
            'password' => Hash::make('rahasia123'),
            'user_type' => 'couple',
            'status' => 'active',
            'vendor_subscription_status' => 'active',
            'vendor_subscription_plan' => 'premium_monthly',
            'vendor_subscription_expires_at' => now()->addMonth(),
        ]);

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($user)
            ->post(route('vendors.register.store'), [
                'name' => 'Studio Aktif',
                'phone' => '0812333444777',
                'subscription_plan' => 'premium_monthly',
            ])
            ->assertRedirect('/vendor');

        $user->refresh();

        $this->assertSame('vendor', $user->user_type);
        $this->assertTrue($user->hasRole('vendor'));
    }

    public function test_couple_with_expired_subscription_gets_renewal_message(): void
    {
        $user = User::query()->create([
            'name' => 'User Kedaluwarsa',
            'email' => 'kadaluarsa@example.test',
            'phone' => '0812333444888',
            'password' => Hash::make('rahasia123'),
            'user_type' => 'couple',
            'status' => 'active',
            'vendor_subscription_status' => 'active',
            'vendor_subscription_plan' => 'premium_monthly',
            'vendor_subscription_expires_at' => now()->subDay(),
        ]);

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($user)
            ->post(route('vendors.register.store'), [
                'name' => 'Studio Kedaluwarsa',
                'phone' => '0812333444888',
                'subscription_plan' => 'premium_monthly',
            ])
            ->assertForbidden()
            ->assertSee('Langganan paket vendor Anda sudah habis', false);

        $user->refresh();

        $this->assertSame('couple', $user->user_type);
        $this->assertFalse($user->hasRole('vendor'));
    }

    public function test_expires_at_is_cast_to_a_date_instance(): void
    {
        $user = User::query()->create([
            'name' => 'User Cast',
            'email' => 'cast@example.test',
            'phone' => '0812333444999',
            'password' => Hash::make('rahasia123'),
            'user_type' => 'couple',
            'status' => 'active',
            'vendor_subscription_status' => 'active',
            'vendor_subscription_expires_at' => now()->addMonth(),
        ]);

        // Guards the regression that made every subscription check throw
        // "Call to a member function isFuture() on string".
        $this->assertInstanceOf(
            \Illuminate\Support\Carbon::class,
            $user->fresh()->vendor_subscription_expires_at,
        );
        $this->assertTrue($user->fresh()->hasPaidVendorSubscription());
    }

    public function test_vendor_subscription_state_resolves_the_three_states(): void
    {
        $never = new User(['vendor_subscription_status' => 'inactive']);
        $this->assertSame(User::VENDOR_SUBSCRIPTION_NONE, $never->vendorSubscriptionState());
        $this->assertFalse($never->hasPaidVendorSubscription());

        $active = new User(['vendor_subscription_status' => 'active']);
        $active->vendor_subscription_expires_at = now()->addMonth();
        $this->assertSame(User::VENDOR_SUBSCRIPTION_ACTIVE, $active->vendorSubscriptionState());
        $this->assertTrue($active->hasPaidVendorSubscription());

        $lapsed = new User(['vendor_subscription_status' => 'active']);
        $lapsed->vendor_subscription_expires_at = now()->subDay();
        $this->assertSame(User::VENDOR_SUBSCRIPTION_EXPIRED, $lapsed->vendorSubscriptionState());
        $this->assertTrue($lapsed->hasExpiredVendorSubscription());
        $this->assertFalse($lapsed->hasPaidVendorSubscription());

        // Lifetime / no explicit expiry still counts as paid.
        $lifetime = new User(['vendor_subscription_status' => 'active']);
        $this->assertTrue($lifetime->hasPaidVendorSubscription());
    }

    public function test_vendor_registration_requires_a_logged_in_user(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.register.store'), [
                'name' => 'Vendor Baru',
                'phone' => '0812111222333',
            ])
            ->assertRedirect(route('frontend.login.create'));
    }
}
