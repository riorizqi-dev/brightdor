<?php

namespace Tests\Feature;

use App\Filament\Vendor\Pages\VendorDashboard;
use App\Filament\Vendor\Resources\VendorPayout\Pages\CreateVendorPayout;
use App\Filament\Vendor\Resources\VendorPayout\Pages\ListVendorPayouts;
use App\Filament\Vendor\Resources\VendorPayout\Pages\ViewVendorPayout;
use App\Filament\Vendor\Resources\VendorProfile\Pages\EditVendorProfile;
use App\Filament\Vendor\Resources\VendorProfile\Pages\ListVendorProfiles;
use App\Filament\Vendor\Resources\VendorService\Pages\CreateVendorService;
use App\Filament\Vendor\Resources\VendorService\Pages\EditVendorService;
use App\Filament\Vendor\Resources\VendorService\Pages\ListVendorServices;
use App\Filament\Vendor\Resources\VendorService\VendorServiceResource;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorCategory;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VendorModuleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('vendor');

        Filament::setCurrentPanel(Filament::getPanel('vendor'));
    }

    private function vendorWithProfile(): array
    {
        $category = VendorCategory::query()->firstOrCreate(
            ['slug' => 'test-category'],
            [
                'name' => 'Kategori Test',
                'commission_rate' => 10,
                'is_active' => true,
            ],
        );

        $user = User::query()->create([
            'name' => 'Vendor Module',
            'email' => 'modul@example.test',
            'phone' => '0812777888999',
            'password' => Hash::make('rahasia123'),
            'user_type' => 'vendor',
            'status' => 'active',
        ]);
        $user->assignRole('vendor');

        $vendor = Vendor::query()->create([
            'user_id' => $user->id,
            'vendor_category_id' => $category->id,
            'business_name' => 'Modul Craft',
            'city' => 'Denpasar',
            'status' => 'approved',
            'is_verified' => true,
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Vendor Module',
        ]);

        return [$user, $vendor, $category];
    }

    private function completedBookingWithEarnings(Vendor $vendor): Booking
    {
        $service = Service::query()->create([
            'vendor_id' => $vendor->id,
            'vendor_category_id' => $vendor->vendor_category_id,
            'name' => 'Paket Modul',
            'price' => 5_000_000,
            'status' => 'published',
            'is_active' => true,
        ]);

        $couple = User::query()->create([
            'name' => 'Couple Modul',
            'email' => 'couple-modul@example.test',
            'phone' => '0812666777888',
            'password' => Hash::make('rahasia123'),
            'user_type' => 'couple',
            'status' => 'active',
        ]);

        return Booking::query()->create([
            'user_id' => $couple->id,
            'vendor_id' => $vendor->id,
            'service_id' => $service->id,
            'subtotal' => 5_000_000,
            'discount' => 0,
            'admin_fee' => 0,
            'commission_amount' => 500_000,
            'total_amount' => 5_000_000,
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function test_vendor_dashboard_shows_stats_and_recent_bookings(): void
    {
        [$user, $vendor] = $this->vendorWithProfile();
        $this->completedBookingWithEarnings($vendor);

        $this->actingAs($user);

        Livewire::test(VendorDashboard::class)
            ->assertSuccessful()
            ->assertSee('Selamat datang, Vendor Module')
            ->assertSee('Modul Craft')
            ->assertSee('Pemesanan Terbaru')
            ->assertSee('Disetujui')
            ->assertSee('Saldo Payout Tersedia')
            ->assertSee('Rp 4.500.000')
            ->assertSee('Ajukan Payout');
    }

    public function test_vendor_can_create_service(): void
    {
        [$user, $vendor, $category] = $this->vendorWithProfile();

        $this->actingAs($user);

        Livewire::test(CreateVendorService::class)
            ->fillForm([
                'vendor_category_id' => $category->id,
                'name' => 'Paket Hemat',
                'price' => 750_000,
                'description' => 'Paket hemat untuk acara kecil.',
                'status' => 'draft',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $service = Service::query()->where('vendor_id', $vendor->id)->latest('id')->firstOrFail();

        $this->assertSame('Paket Hemat', $service->name);
        $this->assertSame(750_000.0, (float) $service->price);
        $this->assertNotSame('', (string) $service->slug);
        $this->assertSame((int) $vendor->id, (int) $service->vendor_id);
    }

    public function test_vendor_sees_only_own_services(): void
    {
        [$user, $vendor] = $this->vendorWithProfile();
        Service::query()->create([
            'vendor_id' => $vendor->id,
            'vendor_category_id' => $vendor->vendor_category_id,
            'name' => 'Paket Sendiri',
            'price' => 100_000,
            'status' => 'published',
            'is_active' => true,
        ]);

        $otherVendor = Vendor::query()->create([
            'user_id' => User::query()->create([
                'name' => 'Vendor Lain',
                'email' => 'modul-lain@example.test',
                'phone' => '0812333444555',
                'password' => Hash::make('rahasia123'),
                'user_type' => 'vendor',
                'status' => 'active',
            ])->id,
            'business_name' => 'Craft Lain',
            'city' => 'Jakarta',
            'status' => 'approved',
        ]);
        Service::query()->create([
            'vendor_id' => $otherVendor->id,
            'name' => 'Paket Rahasia Lain',
            'price' => 999_999,
            'status' => 'published',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(ListVendorServices::class)
            ->assertSuccessful()
            ->assertSee('Paket Sendiri')
            ->assertDontSee('Paket Rahasia Lain');
    }

    public function test_vendor_can_edit_own_service(): void
    {
        [$user, $vendor] = $this->vendorWithProfile();
        $service = Service::query()->create([
            'vendor_id' => $vendor->id,
            'vendor_category_id' => $vendor->vendor_category_id,
            'name' => 'Paket Awal',
            'price' => 100_000,
            'status' => 'published',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(EditVendorService::class, ['record' => $service->getRouteKey()])
            ->fillForm([
                'name' => 'Paket Terbaru',
                'price' => 250_000,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $service->refresh();

        $this->assertSame('Paket Terbaru', $service->name);
        $this->assertSame(250_000.0, (float) $service->price);
    }

    public function test_vendor_cannot_edit_other_vendors_service(): void
    {
        [$user] = $this->vendorWithProfile();
        $otherVendor = Vendor::query()->create([
            'user_id' => User::query()->create([
                'name' => 'Vendor Zed',
                'email' => 'modul-zed@example.test',
                'phone' => '0812999888777',
                'password' => Hash::make('rahasia123'),
                'user_type' => 'vendor',
                'status' => 'active',
            ])->id,
            'business_name' => 'Craft Zed',
            'city' => 'Jogja',
            'status' => 'approved',
        ]);
        $otherService = Service::query()->create([
            'vendor_id' => $otherVendor->id,
            'name' => 'Paket Zed',
            'price' => 333_000,
            'status' => 'published',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $this->get(VendorServiceResource::getUrl('edit', ['record' => $otherService]))
            ->assertNotFound();
    }

    public function test_vendor_can_create_payout_from_completed_earnings(): void
    {
        [$user, $vendor] = $this->vendorWithProfile();
        $booking = $this->completedBookingWithEarnings($vendor);

        $this->assertSame(4_500_000.0, $vendor->payoutsAvailable());

        $this->actingAs($user);

        Livewire::test(CreateVendorPayout::class)
            ->fillForm([
                'amount' => 1_000_000,
                'method' => 'bank_transfer',
                'bank_name' => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_name' => 'Vendor Module',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $vendor->refresh();

        $this->assertSame(3_500_000.0, $vendor->payoutsAvailable());
        $this->assertSame(1, $vendor->payouts()->count());
        $this->assertSame('pending', $vendor->payouts()->firstOrFail()->status);
    }

    public function test_vendor_cannot_request_payout_exceeding_available_balance(): void
    {
        [$user, $vendor] = $this->vendorWithProfile();

        $this->actingAs($user);

        Livewire::test(CreateVendorPayout::class)
            ->fillForm([
                'amount' => 100_000,
                'method' => 'bank_transfer',
                'bank_name' => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_name' => 'Vendor Module',
            ])
            ->call('create');

        $this->assertSame(0, $vendor->payouts()->count());
    }

    public function test_vendor_can_see_and_view_own_payouts(): void
    {
        [$user, $vendor] = $this->vendorWithProfile();
        $this->completedBookingWithEarnings($vendor);

        $this->actingAs($user);

        Livewire::test(CreateVendorPayout::class)
            ->fillForm([
                'amount' => 2_000_000,
                'method' => 'bank_transfer',
                'bank_name' => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_name' => 'Vendor Module',
            ])
            ->call('create');

        $payout = $vendor->payouts()->firstOrFail();

        Livewire::test(ListVendorPayouts::class)
            ->assertSuccessful()
            ->assertSee('Saldo Payout Tersedia')
            ->assertSee('Rp 2.500.000')
            ->assertSee($payout->payout_code);

        Livewire::test(ViewVendorPayout::class, ['record' => $payout->getRouteKey()])
            ->assertSuccessful()
            ->assertSee($payout->payout_code);
    }

    public function test_vendor_can_edit_own_profile(): void
    {
        [$user, $vendor] = $this->vendorWithProfile();

        $this->actingAs($user);

        Livewire::test(EditVendorProfile::class, ['record' => $vendor->getRouteKey()])
            ->fillForm([
                'business_name' => 'Modul Craft Premium',
                'city' => 'Denpasar',
                'description' => 'Vendor terbaik dari Denpasar.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $vendor->refresh();

        $this->assertSame('Modul Craft Premium', $vendor->business_name);
        $this->assertSame('Vendor terbaik dari Denpasar.', $vendor->description);
    }

    public function test_vendor_profile_list_shows_only_own_profile(): void
    {
        [$user, $vendor] = $this->vendorWithProfile();

        Vendor::query()->create([
            'user_id' => User::query()->create([
                'name' => 'Vendor Beta',
                'email' => 'modul-beta@example.test',
                'phone' => '0812111222333',
                'password' => Hash::make('rahasia123'),
                'user_type' => 'vendor',
                'status' => 'active',
            ])->id,
            'business_name' => 'Craft Beta',
            'city' => 'Malang',
            'status' => 'approved',
        ]);

        $this->actingAs($user);

        Livewire::test(ListVendorProfiles::class)
            ->assertSuccessful()
            ->assertSee($vendor->business_name)
            ->assertDontSee('Craft Beta');
    }
}