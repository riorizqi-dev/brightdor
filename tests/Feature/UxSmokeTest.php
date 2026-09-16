<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
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

    public function test_all_public_frontend_pages_render_without_errors(): void
    {
        // Halaman publik utama harus bisa di-render tanpa error 500.
        $this->get('/')->assertOk();
        $this->get('/vendors')->assertOk();
        $this->get('/paket-populer')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/lupa-password')->assertOk();
    }

    public function test_vendor_detail_page_renders_without_errors(): void
    {
        $vendor = Vendor::query()->where('status', 'approved')->firstOrFail();

        $this->get('/vendor/' . $vendor->slug)->assertOk();
    }

    public function test_vendor_category_page_renders_without_errors(): void
    {
        $vendor = Vendor::query()->where('status', 'approved')->with('category')->firstOrFail();

        if ($vendor->category) {
            $this->get('/vendors/' . $vendor->category->slug)->assertOk();
        } else {
            $this->markTestSkipped('Tidak ada kategori vendor untuk diuji.');
        }
    }

    public function test_all_booking_statuses_have_vendor_dashboard_translations(): void
    {
        // Semua status booking yang mungkin tampil di dashboard vendor harus punya
        // terjemahan, agar tidak muncul mentah sebagai "BRIGHTDOR.VENDOR_DASHBOARD.X".
        $statuses = ['pending', 'confirmed', 'on_progress', 'completed', 'cancelled', 'refund'];

        foreach (['id', 'en'] as $locale) {
            foreach ($statuses as $status) {
                $key = "brightdor.vendor_dashboard.{$status}";
                $translated = __($key, [], $locale);

                $this->assertNotSame(
                    $key,
                    $translated,
                    "Terjemahan hilang untuk status '{$status}' di locale '{$locale}'"
                );
            }
        }
    }
}