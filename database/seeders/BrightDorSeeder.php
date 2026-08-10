<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\CommissionSetting;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Invitation;
use App\Models\InvitationOrder;
use App\Models\InvitationRsvp;
use App\Models\InvitationTemplate;
use App\Models\InvitationTemplateCategory;
use App\Models\Payout;
use App\Models\Setting;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BrightDorSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedRoles();
        $this->seedAdmin();
        $this->seedCategories();
        $this->seedVendors();
        $this->seedServices();
        $this->seedBookings();
        $this->seedInvitationTemplates();
        $this->seedInvitationOrders();
        $this->seedTransactions();
        $this->seedPayouts();
        $this->seedContent();
        $this->seedSettings();
        $this->seedCommissionSettings();
    }

    private function seedRoles(): void
    {
        foreach (['super_admin', 'admin', 'vendor', 'couple'] as $role) {
            \Spatie\Permission\Models\Role::findOrCreate($role);
        }
    }

    private function seedAdmin(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => config('brightdor.admin.email')],
            [
                'name' => config('brightdor.admin.name'),
                'password' => Hash::make(config('brightdor.admin.password')),
                'user_type' => 'admin',
                'status' => 'active',
                'phone' => '081234567890',
            ],
        );
        $admin->assignRole('super_admin');
    }

    private function seedCategories(): void
    {
        $categories = [
            ['name' => 'Venue', 'icon' => 'building-office-2', 'commission_rate' => 8, 'sort_order' => 1],
            ['name' => 'Catering', 'icon' => 'cake', 'commission_rate' => 10, 'sort_order' => 2],
            ['name' => 'Dekorasi', 'icon' => 'sparkles', 'commission_rate' => 12, 'sort_order' => 3],
            ['name' => 'Fotografer', 'icon' => 'camera', 'commission_rate' => 12, 'sort_order' => 4],
            ['name' => 'Videografer', 'icon' => 'video-camera', 'commission_rate' => 12, 'sort_order' => 5],
            ['name' => 'MUA', 'icon' => 'paint-brush', 'commission_rate' => 15, 'sort_order' => 6],
            ['name' => 'Wedding Organizer', 'icon' => 'clipboard-document-list', 'commission_rate' => 10, 'sort_order' => 7],
            ['name' => 'Entertainment', 'icon' => 'musical-note', 'commission_rate' => 12, 'sort_order' => 8],
            ['name' => 'Gaun & Jas', 'icon' => 'scissors', 'commission_rate' => 10, 'sort_order' => 9],
            ['name' => 'Undangan Digital', 'icon' => 'envelope', 'commission_rate' => 20, 'sort_order' => 10],
        ];

        foreach ($categories as $category) {
            VendorCategory::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'icon' => $category['icon'],
                    'commission_rate' => $category['commission_rate'],
                    'sort_order' => $category['sort_order'],
                    'is_active' => true,
                    'description' => 'Kategori ' . $category['name'] . ' untuk marketplace BrightDor.',
                ],
            );
        }
    }

    private function seedVendors(): void
    {
        $vendorUsers = [
            ['name' => 'Rina Sari', 'email' => 'rina@elegantvenue.id', 'phone' => '081234567891'],
            ['name' => 'Budi Santoso', 'email' => 'budi@masakjawa.id', 'phone' => '081234567892'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@bungaindah.id', 'phone' => '081234567893'],
            ['name' => 'Andi Wijaya', 'email' => 'andi@photostudio.id', 'phone' => '081234567894'],
            ['name' => 'Maya Putri', 'email' => 'maya@makeupart.id', 'phone' => '081234567895'],
            ['name' => 'Raka Pratama', 'email' => 'raka@djentertain.id', 'phone' => '081234567896'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@woelegance.id', 'phone' => '081234567897'],
            ['name' => 'Fajar Nugroho', 'email' => 'fajar@galerifoto.id', 'phone' => '081234567898'],
            ['name' => 'Anisa Rahmawati', 'email' => 'anisa@gaunindah.id', 'phone' => '081234567899'],
            ['name' => 'Hendra Kurniawan', 'email' => 'hendra@digitalinv.id', 'phone' => '081234567800'],
            ['name' => 'Lestari Budiman', 'email' => 'lestari@premiumcatering.id', 'phone' => '081234567801'],
            ['name' => 'Bambang Sutrisno', 'email' => 'bambang@dekorasiasri.id', 'phone' => '081234567802'],
        ];

        $categoryIds = VendorCategory::pluck('id')->toArray();
        $statuses = ['approved', 'approved', 'approved', 'approved', 'approved', 'pending', 'pending', 'approved', 'approved', 'approved', 'rejected', 'approved'];

        foreach ($vendorUsers as $i => $vu) {
            $user = User::query()->updateOrCreate(
                ['email' => $vu['email']],
                [
                    'name' => $vu['name'],
                    'password' => Hash::make('password'),
                    'user_type' => 'vendor',
                    'status' => 'active',
                    'phone' => $vu['phone'],
                ],
            );
            $user->assignRole('vendor');

            $businessNames = [
                'Elegant Venue Bandung', 'Catering Masak Jawa', 'Dekorasi Bunga Indah',
                'Photo Studio Pro', 'Makeup Artis Studio', 'DJ Entertainment Jakarta',
                'Wedding Organizer Elegance', 'Galeri Foto Nusantara', 'Gaun & Jas Couture',
                'Digital Invitation Pro', 'Premium Catering Service', 'Dekorasi Asri Decoration',
            ];

            $cities = ['Bandung', 'Jakarta', 'Surabaya', 'Yogyakarta', 'Bali', 'Semarang', 'Malang', 'Medan', 'Makassar', 'Palembang', 'Jakarta', 'Bandung'];
            $provinces = ['Jawa Barat', 'DKI Jakarta', 'Jawa Timur', 'DI Yogyakarta', 'Bali', 'Jawa Tengah', 'Jawa Timur', 'Sumatera Utara', 'Sulawesi Selatan', 'Sumatera Selatan', 'DKI Jakarta', 'Jawa Barat'];

            $statusesArr = $statuses;
            $isVerified = in_array($statusesArr[$i], ['approved']);

            Vendor::query()->updateOrCreate(
                ['slug' => Str::slug($businessNames[$i]) . '-' . ($i + 1)],
                [
                    'user_id' => $user->id,
                    'vendor_category_id' => $categoryIds[$i % count($categoryIds)],
                    'business_name' => $businessNames[$i],
                    'description' => $businessNames[$i] . ' adalah vendor pernikahan premium terbaik di ' . $cities[$i] . '. Kami menyediakan layanan berkualitas tinggi dengan pengalaman lebih dari 5 tahun.',
                    'address' => 'Jl. Sudirman No. ' . ($i + 10) . ', ' . $cities[$i],
                    'city' => $cities[$i],
                    'province' => $provinces[$i],
                    'phone' => $vu['phone'],
                    'whatsapp' => $vu['phone'],
                    'instagram' => '@' . Str::slug($businessNames[$i]),
                    'rating_avg' => round(4.0 + ($i % 5) * 0.2, 2),
                    'rating_count' => 10 + $i * 5,
                    'status' => $statusesArr[$i],
                    'is_verified' => $isVerified,
                    'verified_at' => $isVerified ? now()->subDays(30 - $i) : null,
                    'is_featured' => $i < 4,
                    'bank_name' => 'Bank Central Asia',
                    'bank_account_number' => '12345678' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'bank_account_name' => $vu['name'],
                ],
            );
        }
    }

    private function seedServices(): void
    {
        $vendors = Vendor::where('status', 'approved')->get();
        $serviceData = [
            ['name' => 'Paket Premium Ballroom', 'price' => 15000000, 'discount_price' => 12000000, 'description' => 'Ballroom eksklusif kapasitas 500 tamu, sudah termasuk dekorasi dasar dan sound system.', 'features' => ['Ballroom 500 pax', 'Free parking', 'AC centralized', 'Sound system']],
            ['name' => 'Paket Catering Prasmanan', 'price' => 85000, 'discount_price' => null, 'description' => 'Prasmanan premium dengan 10 menu pilihan, chef profesional, dan pelayanan terbaik.', 'features' => ['10 menu', 'Prasmanan', 'Chef profesional', 'Pelayanan 8 jam']],
            ['name' => 'Dekorasi Full Package', 'price' => 5000000, 'discount_price' => 4500000, 'description' => 'Dekorasi lengkap pelaminan, meja tamu, bunga segar, dan lighting.', 'features' => ['Pelaminan', 'Bunga segar', 'Lighting', 'Meja tamu']],
            ['name' => 'Paket Foto & Video Cinematic', 'price' => 12000000, 'discount_price' => 10000000, 'description' => 'Dokumentasi foto & video cinematic, 2 kamera, drone, 200 foto edit, video 15 menit.', 'features' => ['2 kamera', 'Drone', '200 foto', 'Video cinematic']],
            ['name' => 'Bridal Makeup Premium', 'price' => 3500000, 'discount_price' => null, 'description' => 'Makeup artist premium untuk pengantin, termasuk trial makeup dan hairdo.', 'features' => ['Trial makeup', 'Hairdo', 'Lashes', 'Skin prep']],
            ['name' => 'Live Band & DJ', 'price' => 4500000, 'discount_price' => 4000000, 'description' => 'Live band 5 person + DJ 4 jam, MC profesional termasuk.', 'features' => ['Band 5 person', 'DJ 4 jam', 'MC profesional', 'Sound system']],
            ['name' => 'Full Wedding Organizer', 'price' => 8000000, 'discount_price' => 7000000, 'description' => 'WO lengkap dari konsultasi hingga hari H, koordinasi vendor, timeline acara.', 'features' => ['Konsultasi', 'Koordinasi vendor', 'Timeline', 'Hari H']],
            ['name' => 'Foto Pre-Wedding', 'price' => 5000000, 'discount_price' => null, 'description' => 'Sesuatu foto pre-wedding di lokasi pilihan, 100 foto edit, album 20 halaman.', 'features' => ['100 foto', 'Album 20h', '2 lokasi', 'Retouch']],
            ['name' => 'Gaun Pengantin Custom', 'price' => 7500000, 'discount_price' => 6500000, 'description' => 'Gaun pengantin custom design, bahan premium, fitting 3x, aksesoris lengkap.', 'features' => ['Custom design', 'Bahan premium', '3x fitting', 'Aksesoris']],
            ['name' => 'Paket Undangan Digital Premium', 'price' => 250000, 'discount_price' => 200000, 'description' => 'Undangan digital interaktif dengan RSVP, galeri foto, love story, dan countdown timer.', 'features' => ['RSVP online', 'Galeri foto', 'Love story', 'Countdown']],
        ];

        foreach ($vendors as $i => $vendor) {
            $sd = $serviceData[$i % count($serviceData)];
            Service::query()->updateOrCreate(
                ['slug' => Str::slug($sd['name']) . '-' . ($i + 1)],
                [
                    'vendor_id' => $vendor->id,
                    'vendor_category_id' => $vendor->vendor_category_id,
                    'name' => $sd['name'],
                    'description' => $sd['description'],
                    'price' => $sd['price'],
                    'discount_price' => $sd['discount_price'],
                    'price_unit' => 'per event',
                    'features' => $sd['features'],
                    'status' => 'published',
                    'is_active' => true,
                    'is_featured' => $i < 3,
                    'views_count' => 50 + $i * 20,
                    'bookings_count' => 3 + $i,
                ],
            );
        }
    }

    private function seedBookings(): void
    {
        $couples = User::query()->where('user_type', '!=', 'admin')->where('user_type', '!=', 'vendor')->get();
        if ($couples->isEmpty()) {
            $couples = User::query()->where('user_type', 'vendor')->get();
        }

        $vendors = Vendor::where('status', 'approved')->get();
        $services = Service::where('status', 'published')->get();

        $statuses = ['pending', 'confirmed', 'confirmed', 'on_progress', 'on_progress', 'completed', 'completed', 'completed'];
        $bookingData = [
            ['customer_notes' => 'Mohon untuk diatur sesuai tema rustic.', 'event_location' => 'Hotel Grand Hyatt Jakarta'],
            ['customer_notes' => 'Tolong konfirmasi kapasitas.', 'event_location' => 'Gedung Serbaguna Bandung'],
            ['customer_notes' => '', 'event_location' => 'Bali Convention Center'],
            ['customer_notes' => 'Butuh tambahan 1 meja buffet.', 'event_location' => 'The Hermitage Jakarta'],
            ['customer_notes' => '', 'event_location' => 'JW Marriott Surabaya'],
            ['customer_notes' => 'Acara outdoor, backup indoor jika hujan.', 'event_location' => 'Taman Eden Bali'],
            ['customer_notes' => '', 'event_location' => 'Raffles Hotel Jakarta'],
            ['customer_notes' => 'Jam acara mundur 1 jam.', 'event_location' => 'Padma Resort Ubud'],
        ];

        $eventDates = [
            '2026-08-15', '2026-09-01', '2026-09-20', '2026-10-10',
            '2026-10-25', '2026-11-05', '2026-11-20', '2026-12-12',
        ];

        foreach ($statuses as $i => $status) {
            $couple = $couples[$i % $couples->count()];
            $vendor = $vendors[$i % $vendors->count()];
            $service = $services[$i % $services->count()] ?? null;

            $subtotal = $service ? $service->price : 5000000;
            $discount = $service && $service->discount_price ? ($service->price - $service->discount_price) : 0;
            $adminFee = round($subtotal * 0.05);
            $commission = round(($subtotal - $discount) * 0.10);
            $total = $subtotal - $discount + $adminFee;

            $booking = Booking::query()->updateOrCreate(
                ['booking_code' => 'BD-' . strtoupper(Str::random(8))],
                [
                    'user_id' => $couple->id,
                    'vendor_id' => $vendor->id,
                    'service_id' => $service?->id,
                    'event_date' => $eventDates[$i],
                    'event_time' => '10:00:00',
                    'event_location' => $bookingData[$i]['event_location'],
                    'guest_count' => 200 + $i * 50,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'admin_fee' => $adminFee,
                    'commission_amount' => $commission,
                    'total_amount' => $total,
                    'status' => $status,
                    'customer_notes' => $bookingData[$i]['customer_notes'],
                    'confirmed_at' => in_array($status, ['confirmed', 'on_progress', 'completed']) ? now()->subDays(20 - $i) : null,
                    'completed_at' => $status === 'completed' ? now()->subDays(5 - $i) : null,
                ],
            );
        }
    }

    private function seedInvitationTemplates(): void
    {
        InvitationTemplateCategory::query()->updateOrCreate(
            ['slug' => 'elegant'],
            ['name' => 'Elegant', 'description' => 'Template undangan bergaya elegan dan mewah', 'sort_order' => 1, 'is_active' => true],
        );
        InvitationTemplateCategory::query()->updateOrCreate(
            ['slug' => 'modern'],
            ['name' => 'Modern', 'description' => 'Template undangan bergaya modern dan minimalis', 'sort_order' => 2, 'is_active' => true],
        );
        InvitationTemplateCategory::query()->updateOrCreate(
            ['slug' => 'traditional'],
            ['name' => 'Traditional', 'description' => 'Template undangan bergaya tradisional Indonesia', 'sort_order' => 3, 'is_active' => true],
        );

        $catIds = InvitationTemplateCategory::pluck('id')->toArray();

        $templates = [
            ['name' => 'Elegant Gold', 'price' => 250000, 'is_premium' => true, 'is_featured' => true, 'features' => ['RSVP Online', 'Love Story', 'Gallery', 'Countdown']],
            ['name' => 'Garden Romance', 'price' => 150000, 'is_premium' => false, 'is_featured' => false, 'features' => ['RSVP Online', 'Gallery', 'Map']],
            ['name' => 'Modern Minimalis', 'price' => 200000, 'is_premium' => true, 'is_featured' => true, 'features' => ['RSVP Online', 'Love Story', 'Music', 'Countdown']],
            ['name' => 'Classic Rose', 'price' => 175000, 'is_premium' => false, 'is_featured' => false, 'features' => ['RSVP Online', 'Gallery', 'Map']],
            ['name' => 'Royal Palace', 'price' => 300000, 'is_premium' => true, 'is_featured' => true, 'features' => ['RSVP Online', 'Love Story', 'Gallery', 'Video', 'Countdown']],
            ['name' => 'Batik Heritage', 'price' => 180000, 'is_premium' => false, 'is_featured' => false, 'features' => ['RSVP Online', 'Gallery', 'Map']],
            ['name' => 'Floral Bliss', 'price' => 220000, 'is_premium' => true, 'is_featured' => false, 'features' => ['RSVP Online', 'Love Story', 'Gallery', 'Music']],
            ['name' => 'Sakura Dream', 'price' => 275000, 'is_premium' => true, 'is_featured' => true, 'features' => ['RSVP Online', 'Love Story', 'Gallery', 'Video', 'Countdown']],
        ];

        foreach ($templates as $i => $t) {
            $catId = $catIds[$i % count($catIds)];
            InvitationTemplate::query()->updateOrCreate(
                ['slug' => Str::slug($t['name'])],
                [
                    'invitation_template_category_id' => $catId,
                    'name' => $t['name'],
                    'price' => $t['price'],
                    'description' => 'Template undangan digital ' . $t['name'] . ' dengan desain premium dan fitur lengkap.',
                    'features' => $t['features'],
                    'is_premium' => $t['is_premium'],
                    'is_featured' => $t['is_featured'],
                    'is_active' => true,
                    'sales_count' => 20 + $i * 8,
                    'demo_url' => 'https://demo.brightdor.id/' . Str::slug($t['name']),
                ],
            );
        }
    }

    private function seedInvitationOrders(): void
    {
        $users = User::where('user_type', 'vendor')->limit(6)->get();
        $templates = InvitationTemplate::all();

        $statuses = ['paid', 'active', 'active', 'paid', 'pending', 'expired'];
        $couples = [
            ['bride' => 'Rina', 'groom' => 'Andi'],
            ['bride' => 'Sinta', 'groom' => 'Budi'],
            ['bride' => 'Maya', 'groom' => 'Raka'],
            ['bride' => 'Dewi', 'groom' => 'Fajar'],
            ['bride' => 'Anisa', 'groom' => 'Hendra'],
            ['bride' => 'Lestari', 'groom' => 'Bambang'],
        ];

        foreach ($statuses as $i => $status) {
            $user = $users[$i % $users->count()];
            $template = $templates[$i % $templates->count()];

            $order = InvitationOrder::query()->updateOrCreate(
                ['order_code' => 'INV-' . strtoupper(Str::random(8))],
                [
                    'user_id' => $user->id,
                    'invitation_template_id' => $template->id,
                    'bride_name' => $couples[$i]['bride'],
                    'groom_name' => $couples[$i]['groom'],
                    'wedding_date' => '2026-' . str_pad(8 + $i, 2, '0', STR_PAD_LEFT) . '-15',
                    'wedding_venue' => 'Grand Ballroom ' . ['Jakarta', 'Bandung', 'Bali', 'Surabaya', 'Yogyakarta', 'Semarang'][$i],
                    'subdomain' => Str::slug($couples[$i]['bride'] . '-' . $couples[$i]['groom']),
                    'price' => $template->price,
                    'status' => $status,
                    'paid_at' => in_array($status, ['paid', 'active']) ? now()->subDays(10 - $i) : null,
                ],
            );

            if (in_array($status, ['paid', 'active'])) {
                Invitation::query()->updateOrCreate(
                    ['slug' => Str::slug($couples[$i]['bride'] . '-' . $couples[$i]['groom'] . '-' . $i)],
                    [
                        'invitation_order_id' => $order->id,
                        'user_id' => $user->id,
                        'invitation_template_id' => $template->id,
                        'subdomain' => $order->subdomain,
                        'content' => json_encode([
                            'bride' => $couples[$i]['bride'],
                            'groom' => $couples[$i]['groom'],
                            'date' => $order->wedding_date,
                            'venue' => $order->wedding_venue,
                        ]),
                        'views_count' => 50 + $i * 30,
                        'rsvp_yes' => 10 + $i * 5,
                        'rsvp_no' => 2 + $i,
                        'rsvp_maybe' => 3 + $i,
                        'is_published' => $status === 'active',
                        'published_at' => $status === 'active' ? now()->subDays(5) : null,
                    ],
                );
            }
        }
    }

    private function seedTransactions(): void
    {
        $bookings = Booking::all();
        $orderCount = 0;

        foreach ($bookings as $booking) {
            Transaction::query()->updateOrCreate(
                ['transaction_code' => 'TRX-' . strtoupper(Str::random(10))],
                [
                    'user_id' => $booking->user_id,
                    'payable_type' => Booking::class,
                    'payable_id' => $booking->id,
                    'type' => 'payment',
                    'amount' => $booking->total_amount,
                    'fee' => round($booking->total_amount * 0.02),
                    'net_amount' => $booking->total_amount - round($booking->total_amount * 0.02),
                    'payment_method' => 'bank_transfer',
                    'payment_gateway' => 'midtrans',
                    'status' => in_array($booking->status, ['completed', 'on_progress']) ? 'success' : ($booking->status === 'pending' ? 'pending' : 'success'),
                    'paid_at' => in_array($booking->status, ['completed', 'on_progress', 'confirmed']) ? now()->subDays(15) : null,
                ],
            );
        }

        $invitationOrders = InvitationOrder::whereIn('status', ['paid', 'active'])->get();
        foreach ($invitationOrders as $order) {
            Transaction::query()->updateOrCreate(
                ['transaction_code' => 'TRX-' . strtoupper(Str::random(10))],
                [
                    'user_id' => $order->user_id,
                    'payable_type' => InvitationOrder::class,
                    'payable_id' => $order->id,
                    'type' => 'payment',
                    'amount' => $order->price,
                    'fee' => round($order->price * 0.02),
                    'net_amount' => $order->price - round($order->price * 0.02),
                    'payment_method' => 'ewallet',
                    'payment_gateway' => 'xendit',
                    'status' => 'success',
                    'paid_at' => $order->paid_at,
                ],
            );
        }
    }

    private function seedPayouts(): void
    {
        $vendors = Vendor::where('status', 'approved')->limit(4)->get();
        $statuses = ['pending', 'processing', 'paid', 'paid'];

        foreach ($vendors as $i => $vendor) {
            Payout::query()->updateOrCreate(
                ['payout_code' => 'PO-' . strtoupper(Str::random(8))],
                [
                    'vendor_id' => $vendor->id,
                    'amount' => 2000000 + $i * 1500000,
                    'fee' => 5000,
                    'net_amount' => 2000000 + $i * 1500000 - 5000,
                    'bank_name' => $vendor->bank_name,
                    'bank_account_number' => $vendor->bank_account_number,
                    'bank_account_name' => $vendor->bank_account_name,
                    'status' => $statuses[$i],
                    'admin_notes' => $statuses[$i] === 'paid' ? 'Payout berhasil diproses' : null,
                    'processed_at' => $statuses[$i] === 'paid' ? now()->subDays(3) : null,
                ],
            );
        }
    }

    private function seedContent(): void
    {
        // Blogs
        $blogs = [
            ['title' => '10 Tips Memilih Venue Pernikahan Impian', 'excerpt' => 'Memilih venue pernikahan adalah salah satu keputusan terpenting...', 'status' => 'published', 'is_featured' => true],
            ['title' => 'Tren Dekorasi Pernikahan 2026', 'excerpt' => 'Tahun 2026 membawa tren dekorasi baru yang segar...', 'status' => 'published', 'is_featured' => true],
            ['title' => 'Panduan Lengkap Undangan Digital', 'excerpt' => 'Undangan digital semakin populer di kalangan millennial...', 'status' => 'published', 'is_featured' => false],
        ];
        foreach ($blogs as $i => $blog) {
            Blog::query()->updateOrCreate(
                ['slug' => Str::slug($blog['title'])],
                array_merge($blog, [
                    'content' => '<p>' . $blog['excerpt'] . ' Ini adalah konten lengkap dari artikel ' . $blog['title'] . '.</p>',
                    'views_count' => 100 + $i * 50,
                    'published_at' => now()->subDays(10 - $i * 3),
                ]),
            );
        }

        // Testimonials
        $testimonials = [
            ['name' => 'Rina & Andi', 'role' => 'Jakarta', 'content' => 'BrightDor membantu kami menemukan vendor terbaik untuk pernikahan kami. Sangat puas!', 'rating' => 5],
            ['name' => 'Sinta & Budi', 'role' => 'Bandung', 'content' => 'Undangan digital dari BrightDor sangat elegan. Tamu-tamu kami terkesan!', 'rating' => 5],
            ['name' => 'Maya & Raka', 'role' => 'Bali', 'content' => 'Proses booking sangat mudah dan transparan. Terima kasih BrightDor!', 'rating' => 4],
        ];
        foreach ($testimonials as $i => $t) {
            Testimonial::query()->create(array_merge($t, ['is_active' => true, 'sort_order' => $i]));
        }

        // Banners
        Banner::query()->updateOrCreate(
            ['title' => 'Promo Pernikahan Spesial'],
            ['subtitle' => 'Diskon hingga 30% untuk semua vendor', 'position' => 'home_hero', 'is_active' => true, 'sort_order' => 1],
        );

        // FAQs
        $faqs = [
            ['question' => 'Bagaimana cara mendaftar sebagai vendor?', 'answer' => 'Klik tombol "Daftar Vendor" di halaman utama, isi form profil usaha, lalu tunggu approval admin.', 'category' => 'Vendor'],
            ['question' => 'Bagaimana sistem pembayaran?', 'answer' => 'BrightDor mendukung pembayaran via bank transfer, e-wallet (GoPay, OVO, Dana), dan kartu kredit.', 'category' => 'Pembayaran'],
            ['question' => 'Apakah bisa custom domain untuk undangan digital?', 'answer' => 'Ya, Anda bisa menggunakan custom domain sendiri atau menggunakan subdomain brightdor.id.', 'category' => 'Undangan Digital'],
        ];
        foreach ($faqs as $i => $faq) {
            Faq::query()->create(array_merge($faq, ['is_active' => true, 'sort_order' => $i]));
        }
    }

    private function seedSettings(): void
    {
        $settings = [
            ['group' => 'general', 'key' => 'site_name', 'value' => 'BrightDor', 'type' => 'string'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Premium Wedding Marketplace Indonesia', 'type' => 'string'],
            ['group' => 'general', 'key' => 'contact_email', 'value' => 'hello@brightdor.id', 'type' => 'string'],
            ['group' => 'general', 'key' => 'contact_phone', 'value' => '021-1234-5678', 'type' => 'string'],
            ['group' => 'social', 'key' => 'instagram', 'value' => 'https://instagram.com/brightdor', 'type' => 'string'],
            ['group' => 'social', 'key' => 'tiktok', 'value' => 'https://tiktok.com/@brightdor', 'type' => 'string'],
            ['group' => 'payment', 'key' => 'payment_gateway', 'value' => 'midtrans', 'type' => 'string'],
            ['group' => 'payment', 'key' => 'midtrans_is_production', 'value' => '0', 'type' => 'boolean'],
            ['group' => 'payment', 'key' => 'xendit_is_production', 'value' => '0', 'type' => 'boolean'],
            ['group' => 'commission', 'key' => 'default_commission_rate', 'value' => '10', 'type' => 'number'],
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(
                ['key' => $setting['key']],
                $setting,
            );
        }
    }

    private function seedCommissionSettings(): void
    {
        CommissionSetting::query()->updateOrCreate(
            ['label' => 'Global Default'],
            [
                'vendor_category_id' => null,
                'rate_percent' => 10,
                'rate_fixed' => 0,
                'is_active' => true,
            ],
        );
    }
}
