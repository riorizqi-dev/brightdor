<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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

    public function test_guest_can_view_vendor_registration_page(): void
    {
        $this->get(route('vendors.register.create'))
            ->assertOk()
            ->assertSee('Daftar sebagai Vendor')
            ->assertSee('/daftar-vendor', false);
    }

    public function test_guest_can_register_as_vendor(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.register.store'), [
                'name' => 'Studio Bunga Alya',
                'email' => 'alya@example.test',
                'phone' => '0812333444555',
                'password' => 'rahasia123',
                'password_confirmation' => 'rahasia123',
            ])
            ->assertRedirect(route('vendors.register.create'))
            ->assertSessionHas('success');

        $user = User::query()->where('email', 'alya@example.test')->first();

        $this->assertNotNull($user);
        $this->assertSame('vendor', $user->user_type);
        $this->assertTrue($user->hasRole('vendor'));
    }

    public function test_registration_rejects_duplicate_email(): void
    {
        User::query()->create([
            'name' => 'Vendor Lama',
            'email' => 'lama@example.test',
            'phone' => '0812000000000',
            'password' => 'rahasia123',
            'user_type' => 'vendor',
        ]);

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.register.store'), [
                'name' => 'Vendor Baru',
                'email' => 'lama@example.test',
                'phone' => '0812111222333',
                'password' => 'rahasia123',
                'password_confirmation' => 'rahasia123',
            ])
            ->assertSessionHasErrors(['email']);
    }

    public function test_registration_rejects_unconfirmed_password(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.register.store'), [
                'name' => 'Vendor Baru',
                'email' => 'baru@example.test',
                'phone' => '0812111222333',
                'password' => 'rahasia123',
                'password_confirmation' => 'rahasia124',
            ])
            ->assertSessionHasErrors(['password']);
    }
}
