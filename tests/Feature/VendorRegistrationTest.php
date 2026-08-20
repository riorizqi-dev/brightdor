<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VendorRegistrationTest extends TestCase
{
    use DatabaseTransactions;

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
