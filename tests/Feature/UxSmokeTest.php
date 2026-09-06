<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UxSmokeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('admin');
        Role::findOrCreate('vendor');
    }

    public function test_admin_panel_renders_with_sidebar(): void
    {
        $user = User::query()->create([
            'name' => 'Admin Ux',
            'email' => 'ux-admin@example.test',
            'password' => 'rahasia123',
            'user_type' => 'admin',
        ]);
        $user->assignRole('admin');

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk()
            ->assertSee('fi-sidebar', escape: false)
            ->assertSee('BrightDor');
    }

    public function test_vendor_panel_renders_with_sidebar(): void
    {
        $user = User::query()->create([
            'name' => 'Vendor Ux',
            'email' => 'ux-vendor@example.test',
            'phone' => '0812333444555',
            'password' => 'rahasia123',
            'user_type' => 'vendor',
        ]);
        $user->assignRole('vendor');

        $this->actingAs($user)
            ->get('/vendor/dashboard')
            ->assertOk()
            ->assertSee('fi-sidebar', escape: false)
            ->assertSee('BrightDor');
    }
}