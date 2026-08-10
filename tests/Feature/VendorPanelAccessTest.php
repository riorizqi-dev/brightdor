<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VendorPanelAccessTest extends TestCase
{
    use DatabaseTransactions;

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
        ]);
        $user->assignRole('vendor');

        return $user;
    }

    public function test_vendor_panel_login_page_is_accessible(): void
    {
        $this->get('/vendor/login')
            ->assertOk()
            ->assertSee('BrightDor')
            ->assertSee('Daftar di sini');
    }

    public function test_vendor_with_profile_can_view_dashboard(): void
    {
        $user = $this->vendorUser();

        Vendor::query()->create([
            'user_id' => $user->id,
            'business_name' => 'Studio Bunga Alya',
            'city' => 'Jakarta Selatan',
            'status' => 'approved',
            'is_verified' => true,
            'rating_avg' => 4.8,
            'rating_count' => 12,
        ]);

        $this->actingAs($user)
            ->get('/vendor/dashboard')
            ->assertOk()
            ->assertSee('Dasbor Vendor')
            ->assertSee('Studio Bunga Alya')
            ->assertSee('Disetujui');
    }

    public function test_vendor_without_profile_sees_onboarding(): void
    {
        $this->actingAs($this->vendorUser())
            ->get('/vendor/dashboard')
            ->assertOk()
            ->assertSee('Profil vendor belum tersedia');
    }

    public function test_vendor_cannot_access_admin_panel(): void
    {
        $this->actingAs($this->vendorUser())
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_cannot_access_vendor_panel(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@example.test',
            'phone' => '0812444555666',
            'password' => 'rahasia123',
            'user_type' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/vendor/dashboard')
            ->assertForbidden();
    }

    public function test_couple_cannot_access_vendor_panel(): void
    {
        $couple = User::query()->create([
            'name' => 'Pasangan Baru',
            'email' => 'couple@example.test',
            'phone' => '0812555666777',
            'password' => 'rahasia123',
            'user_type' => 'couple',
        ]);

        $this->actingAs($couple)
            ->get('/vendor/dashboard')
            ->assertForbidden();
    }
}
