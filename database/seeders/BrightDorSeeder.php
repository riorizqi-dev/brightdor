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
        $this->seedExtendedVendors();
        $this->seedExtendedServices();
        $this->seedExtendedBookings();
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
        $vendorProfiles = [
            [
                'name' => 'Rina Sasmita',
                'email' => 'rina.sasmita@brightdor.test',
                'phone' => '0812-3456-7891',
                'business_name' => 'The Grand Pavilion Ballroom',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'address' => 'Jl. Ir. H. Juanda No. 128, Dago',
                'description' => 'The Grand Pavilion menghadirkan kemegahan arsitektur kolonial kontemporer dengan kapasitas hingga 1.200 tamu. Dilengkapi kristal chandelier megah, ceiling setinggi 8 meter, sistem pendingin udara terpusat, dan private bridal suite eksklusif.',
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'name' => 'Budi Prasetyo',
                'email' => 'budi.prasetyo@brightdor.test',
                'phone' => '0812-3456-7892',
                'business_name' => 'Dulang Rasa Royal Catering',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'address' => 'Jl. Kemang Raya No. 45, Jakarta Selatan',
                'description' => 'Spesialis hidangan resepsi pernikahan nusantara dan fusion internasional. Diracik langsung oleh tim executive chef berpengalaman hotel berbintang, mengedepankan bahan organik segar, standar higienis bersertifikasi, dan tata saji buffet artistik.',
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@brightdor.test',
                'phone' => '0812-3456-7893',
                'business_name' => 'Larasati Atelier & Floral Design',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'address' => 'Jl. Raya Darmo No. 88, Surabaya',
                'description' => 'Atelier dekorasi pernikahan konseptual bernuansa modern romantic dan botanical luxury. Mengutamakan instalasi bunga segar impor, pelaminan berdimensi arsitektural, dan pencahayaan panggung tematik yang menghidupkan setiap sudut momen bahagia.',
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi.wijaya@brightdor.test',
                'phone' => '0812-3456-7894',
                'business_name' => 'Lentera Cerita Visuals',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'address' => 'Jl. Kaliurang Km 8.5, Sleman',
                'description' => 'Kolektif fotografer dokumenter pernikahan yang mendedikasikan diri untuk menangkap emosi otentik, keintiman keluarga, dan detail estetis hari bahagia Anda. Hasil akhir dikurasi dengan tone warna film hangat abadi tanpa kesan pose kaku.',
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'name' => 'Maya Amanda Putri',
                'email' => 'maya.putri@brightdor.test',
                'phone' => '0812-3456-7895',
                'business_name' => 'Aura Pengantin Makeup Studio',
                'city' => 'Denpasar',
                'province' => 'Bali',
                'address' => 'Jl. Sunset Road No. 102, Kuta, Badung',
                'description' => 'Studio tata rias pengantin profesional spesialisasi complexion flawless dan natural glam yang tahan uji sepanjang hari. Menggunakan rangkaian produk kosmetik high-end internasional yang disesuaikan dengan skin undertone dan karakter gaun pengantin.',
                'status' => 'approved',
                'is_featured' => false,
            ],
            [
                'name' => 'Raka Pratama',
                'email' => 'raka.pratama@brightdor.test',
                'phone' => '0812-3456-7896',
                'business_name' => 'Harmoni Nada Orchestral & Band',
                'city' => 'Semarang',
                'province' => 'Jawa Tengah',
                'address' => 'Jl. Pandanaran No. 56, Semarang',
                'description' => 'Penyedia hiburan musik pernikahan premium mulai dari string quartet untuk prosesi akad/pemberkatan sakral hingga all-star live band 8-piece untuk kemeriahan resepsi, dipandu oleh MC profesional dwibahasa.',
                'status' => 'pending',
                'is_featured' => false,
            ],
            [
                'name' => 'Dewi Lestari Kusuma',
                'email' => 'dewi.lestari@brightdor.test',
                'phone' => '0812-3456-7897',
                'business_name' => 'Kalyana Wedding Planner & Organizer',
                'city' => 'Malang',
                'province' => 'Jawa Timur',
                'address' => 'Jl. Ijen No. 34, Klojen, Malang',
                'description' => 'Perencana dan pelaksana pernikahan menyeluruh dari hulu ke hilir. Tim planner bersertifikasi kami mengelola detail timeline, sinkronisasi puluhan vendor, dan protokol acara agar kedua mempelai serta keluarga dapat menikmati momen tanpa rasa cemas.',
                'status' => 'pending',
                'is_featured' => false,
            ],
            [
                'name' => 'Fajar Nugroho',
                'email' => 'fajar.nugroho@brightdor.test',
                'phone' => '0812-3456-7898',
                'business_name' => 'Pratama Cinematic Motion Films',
                'city' => 'Medan',
                'province' => 'Sumatera Utara',
                'address' => 'Jl. Ring Road No. 72, Medan',
                'description' => 'Rumah produksi sinematik pernikahan dengan teknologi kamera bioskop 4K, rekaman aerial drone berlisensi, dan penyuntingan audio emosional. Menghadirkan wedding teaser same-day edit dan film dokumenter dokumentasi utuh berdurasi 20 menit.',
                'status' => 'approved',
                'is_featured' => false,
            ],
            [
                'name' => 'Anisa Rahmawati',
                'email' => 'anisa.rahmawati@brightdor.test',
                'phone' => '0812-3456-7899',
                'business_name' => 'Maison de Kebaya & Bespoke Bridal',
                'city' => 'Makassar',
                'province' => 'Sulawesi Selatan',
                'address' => 'Jl. Pengayoman No. 19, Panakkukang',
                'description' => 'House of couture spesialis kebaya pengantin nusantara modern, gaun pesta adibusana, serta setelan jas pria tailor-made. Menggunakan material lace Prancis, sutra alami, dan sulaman payet tangan presisi tinggi dengan sesi fitting eksklusif.',
                'status' => 'approved',
                'is_featured' => false,
            ],
            [
                'name' => 'Hendra Kurniawan',
                'email' => 'hendra.kurniawan@brightdor.test',
                'phone' => '0812-3456-7800',
                'business_name' => 'Warkat Cinta Digital Invitation',
                'city' => 'Palembang',
                'province' => 'Sumatera Selatan',
                'address' => 'Jl. Basuki Rahmat No. 40, Palembang',
                'description' => 'Studio kreatif undangan pernikahan digital berbasis web interaktif. Dilengkapi fitur manajemen RSVP real-time, live streaming link, maps integration, QR code check-in tamu undangan, dan musik pengiring berlisensi resmi.',
                'status' => 'approved',
                'is_featured' => false,
            ],
            [
                'name' => 'Lestari Budiman',
                'email' => 'lestari.budiman@brightdor.test',
                'phone' => '0812-3456-7801',
                'business_name' => 'Nirmala Culinary Heritage',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'address' => 'Jl. Senopati No. 62, Kebayoran Baru',
                'description' => 'Pelopor katering jamuan adat dan jamuan modern nusantara berkelas dunia. Menyajikan signature menu legendaris, live cooking stall interaktif, dessert corner artisan, dan pramusaji berseragam rapi dengan protokol hospitality prima.',
                'status' => 'approved',
                'is_featured' => false,
            ],
            [
                'name' => 'Bambang Sutrisno',
                'email' => 'bambang.sutrisno@brightdor.test',
                'phone' => '0812-3456-7802',
                'business_name' => 'Svarga Botanical Wedding Design',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'address' => 'Jl. R.E. Martadinata No. 90, Citarum',
                'description' => 'Jasa tata ruang dan perancangan panggung pernikahan bertema botanical luxury dan glasshouse elegance. Mengombinasikan dedaunan tropis eksotis, instalasi gantung melayang, dan struktur backdrop modern kontemporer.',
                'status' => 'approved',
                'is_featured' => false,
            ],
        ];

        $categoryIds = VendorCategory::pluck('id')->toArray();

        foreach ($vendorProfiles as $i => $vp) {
            $user = User::query()->updateOrCreate(
                ['email' => $vp['email']],
                [
                    'name' => $vp['name'],
                    'password' => Hash::make('password'),
                    'user_type' => 'vendor',
                    'status' => 'active',
                    'phone' => $vp['phone'],
                ],
            );
            $user->assignRole('vendor');

            $isVerified = in_array($vp['status'], ['approved'], true);

            $vendor = Vendor::query()->updateOrCreate(
                ['slug' => Str::slug($vp['business_name']) . '-' . ($i + 1)],
                [
                    'user_id' => $user->id,
                    'vendor_category_id' => $categoryIds[$i % count($categoryIds)],
                    'business_name' => $vp['business_name'],
                    'description' => $vp['description'],
                    'address' => $vp['address'],
                    'city' => $vp['city'],
                    'province' => $vp['province'],
                    'phone' => $vp['phone'],
                    'whatsapp' => $vp['phone'],
                    'instagram' => '@' . Str::slug($vp['business_name']),
                    'rating_avg' => round(4.5 + ($i % 5) * 0.1, 2),
                    'rating_count' => 15 + $i * 6,
                    'status' => $vp['status'],
                    'is_verified' => $isVerified,
                    'verified_at' => $isVerified ? now()->subDays(45 - $i) : null,
                    'is_featured' => $vp['is_featured'],
                    'bank_name' => 'Bank Central Asia',
                    'bank_account_number' => '12345678' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                    'bank_account_name' => $vp['name'],
                ],
            );

            if ($vendor->getMedia('portfolio')->isEmpty()) {
                $categoryName = strtolower(VendorCategory::find($categoryIds[$i % count($categoryIds)])->name);
                $imagePath = $this->resolveSeederImagePath($categoryName, $i);

                if ($imagePath && file_exists($imagePath)) {
                    $vendor->addMedia($imagePath)->preservingOriginal()->toMediaCollection('portfolio');
                }
            }
        }
    }

    private function seedServices(): void
    {
        $vendors = Vendor::where('status', 'approved')->get();
        $serviceData = [
            [
                'name' => 'Grand Crystal Ballroom Package',
                'price' => 48000000,
                'discount_price' => 42000000,
                'description' => 'Penggunaan Grand Ballroom eksklusif 6 jam untuk hingga 800 tamu, termasuk fasilitas 2 ruang VIP keluarga, sound system 10.000 watt, dan 20 voucher parkir VIP.',
                'features' => ['Kapasitas hingga 800 tamu', 'Sound system 10.000W & lighting panggung', '2 Ruang rias & transit VIP ber-AC', 'Genset cadangan 150 kVA otomatis'],
            ],
            [
                'name' => 'Royal Heritage Buffet (500 Pax)',
                'price' => 45000000,
                'discount_price' => 39500000,
                'description' => 'Paket prasmanan istimewa untuk 500 porsi dengan 6 menu utama nusantara, 4 macam makanan penutup, sajian wedang tradisional, dan free flow minuman dingin.',
                'features' => ['Menu utama 6 macam pilihan chef', '3 Food stall live cooking', 'Dessert corner & fruit display', 'Pramusaji bersertifikat & peralatan mewah'],
            ],
            [
                'name' => 'Imperial Botanical Floral Stage',
                'price' => 32000000,
                'discount_price' => 28000000,
                'description' => 'Dekorasi pelaminan megah lebar 16 meter dengan instalasi bunga segar impor, pathway jalan bertabur kelopak, gate masuk artistik, dan pergola penerima tamu.',
                'features' => ['Pelaminan 16m full fresh flowers', 'Standing flower jalur karpet merah', 'Mini garden panggung & lighting ambient', 'Gazebo akad nikah / photobooth interaktif'],
            ],
            [
                'name' => 'Cinematic 4K Wedding Story Collection',
                'price' => 16000000,
                'discount_price' => 13500000,
                'description' => 'Liputan foto dan video penuh dari persiapan pagi hingga resepsi malam. Melibatkan 3 fotografer senior, 2 videografer sinematik, pilot drone, dan output album leatherette.',
                'features' => ['Same-Day Edit video 3-5 menit', 'Dokumenter cinematic 15-20 menit', 'Exclusive magnetic leather photo album', 'Semua file resolusi tinggi dalam flashdisk kayu'],
            ],
            [
                'name' => 'Bridal High-End Glow & Hairdo',
                'price' => 7500000,
                'discount_price' => 6500000,
                'description' => 'Riasan pengantin wanita untuk akad dan resepsi dengan complexion tahan hingga 16 jam. Sudah termasuk 1x trial makeup sebelum hari H dan hairdo/hijab do modern.',
                'features' => ['Kosmetik high-end (Dior, Chanel, Charlotte Tilbury)', '1x Sesi trial makeup & konsultasi look', 'Touch-up stand by hingga resepsi selesai', 'Free makeup & hairdo untuk 2 ibu pengantin'],
            ],
            [
                'name' => 'Chamber Orchestra & Modern Pop Band',
                'price' => 11000000,
                'discount_price' => 9500000,
                'description' => 'Ensemble musik 7 personil (vocal, piano, violin, cello, bass, drum, saksofon) membawakan repertoar lagu klasik romantis, jazz ballad, dan Top 40 internasional.',
                'features' => ['Ensemble 7 musisi profesional', 'Sound engineering & monitor panggung', 'MC dwibahasa (Indonesia - Inggris)', 'Custom song request untuk grand entrance'],
            ],
            [
                'name' => 'All-In Full Day Wedding Coordination',
                'price' => 18000000,
                'discount_price' => 15000000,
                'description' => 'Pendampingan persiapan pernikahan selama 3 bulan dan koordinasi total pada hari H dengan tim 10 orang crew berseragam dan berkamera komunikasi radio.',
                'features' => ['10 Crew profesional on the day', 'Technical meeting seluruh vendor & keluarga', 'Penyusunan rundown detail menit-per-menit', 'Handling perijinan & protokol tamu VVIP'],
            ],
            [
                'name' => 'Romantic Destination Pre-Wedding Shoot',
                'price' => 8500000,
                'discount_price' => 7500000,
                'description' => 'Sesi pemotretan pre-wedding outdoor 1 hari penuh di lokasi eksotis. Termasuk konsep moodboard visual, 2 set pakaian casual/formal, dan 40 foto retouch.',
                'features' => ['1 Hari shooting (hingga 8 jam kerja)', '40 Foto retouch profesional resolusi tinggi', '1 Frame kanvas ukuran 60x90 cm', 'Klip video teaser vertikal untuk reels/undangan'],
            ],
            [
                'name' => 'Bespoke Traditional Kebaya & Groom Beskap',
                'price' => 12500000,
                'discount_price' => 11000000,
                'description' => 'Rancangan kebaya brokat berpayet swarovski dan beskap pengantin pria berbahan wool premium. Desain disesuaikan dengan proporsi tubuh pengantin melalui 3x fitting.',
                'features' => ['Material brokat Prancis & payet kristal', '3x Fitting & penyesuaian siluet gaun', 'Termasuk kain batik tulis sarimbit', 'Free sewa veil pengantin & aksesori kepala'],
            ],
            [
                'name' => 'Custom Interactive Web Wedding Invitation',
                'price' => 450000,
                'discount_price' => 350000,
                'description' => 'Website undangan pernikahan eksklusif dengan nama domain khusus, amplop digital terintegrasi QRIS, galeri interaktif, countdown timer, dan buku tamu online.',
                'features' => ['Nama tamu tak terbatas (personalized link)', 'Integrasi peta Google Maps & navigasi GPS', 'Konfirmasi kehadiran RSVP instan ke WhatsApp', 'Masa aktif website hingga 1 tahun ke depan'],
            ],
        ];

        foreach ($vendors as $i => $vendor) {
            $sd = $serviceData[$i % count($serviceData)];
            $service = Service::query()->updateOrCreate(
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
                    'is_featured' => $i < 4,
                    'views_count' => 120 + $i * 35,
                    'bookings_count' => 5 + $i * 2,
                ],
            );

            if ($service->getMedia('cover')->isEmpty()) {
                $catName = strtolower($vendor->category?->name ?? VendorCategory::find($vendor->vendor_category_id)?->name ?? '');
                $imagePath = $this->resolveSeederImagePath($catName, $i);
                if ($imagePath && file_exists($imagePath)) {
                    $service->addMedia($imagePath)->preservingOriginal()->toMediaCollection('cover');
                }
            }
        }
    }

    private function seedBookings(): void
    {
        $couples = User::query()->where('user_type', '!=', 'admin')->where('user_type', '!=', 'vendor')->get();
        if ($couples->isEmpty()) {
            $couples = User::query()->where('user_type', 'vendor')->get();
        }

        $vendors = Vendor::where('status', 'approved')->get();
        $services = Service::where('status', 'published')->get()->groupBy('vendor_id');

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
            $vendorServices = $services->get($vendor->id);
            $service = $vendorServices?->isNotEmpty() ? $vendorServices[$i % $vendorServices->count()] : null;

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
            [
                'title' => '10 Panduan Menentukan Venue Pernikahan Impian Sesuai Kapasitas & Anggaran',
                'excerpt' => 'Menentukan lokasi resepsi adalah langkah pertama yang menentukan jalannya seluruh persiapan pernikahan. Simak parameter krusial seperti ceiling height, load-in vendor, hingga regulasi katering.',
                'content' => '<p>Menemukan venue pernikahan yang tepat bukan sekadar mencocokkan luas ruangan dengan estimasi jumlah undangan. Banyak faktor teknis yang memengaruhi kenyamanan para tamu dan kelancaran alur vendor pendukung.</p><h3>1. Perhatikan Tinggi Langit-Langit (Ceiling Height)</h3><p>Ruangan dengan ceiling minimal 5-7 meter akan memberikan sirkulasi udara yang lebih segar dan memungkinkan dekorasi pelaminan menjulang tanpa terasa sesak.</p><h3>2. Akses Bongkar Muat (Load-In) Vendor</h3><p>Pastikan gedung memiliki loading dock khusus dan lift barang yang memadai agar tim dekorasi, sound system, dan katering dapat bekerja tepat waktu tanpa menghambat operasional lokasi.</p><h3>3. Sistem Pendingin & Cadangan Daya</h3><p>Konfirmasikan kapasitas total pendingin udara (AC sentral maupun standing unit) saat kapasitas tamu terisi 100%, serta kepastian otomatisasi genset cadangan saat terjadi pemadaman listrik.</p>',
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'Evolusi Tren Dekorasi Pernikahan 2026: Botanical Romance & Modern Minimalis',
                'excerpt' => 'Tahun 2026 memperlihatkan pergeseran estetika dari dekorasi masif artifisial menuju instalasi organik alami dengan palet warna champagne, sage green, dan sentuhan kristal elegan.',
                'content' => '<p>Konsep dekorasi pernikahan kini semakin personal dan ramah lingkungan. Pasangan modern lebih memilih arsitektur panggung yang bersih dipadukan dengan instalasi flora segar yang mengalir natural.</p><h3>Penggunaan Material Alami & Bunga Lokal Unggulan</h3><p>Kombinasi antara mawar lokal berkualitas, dedaunan tropis, dan ranting bertekstur memberikan kedalaman visual yang memukau tanpa harus bergantung sepenuhnya pada bunga impor berbiaya tinggi.</p><h3>Pencahayaan Ambient & Architectural Lighting</h3><p>Lampu sorot keras kini digantikan oleh tata cahaya hangat bertingkat (warm amber wash, pinspot accent pada meja jamuan, dan lampu gantung berdimmer lembut).</p>',
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'Etika & Efisiensi Penggunaan Undangan Digital untuk Pernikahan Masa Kini',
                'excerpt' => 'Undangan digital berbasis website interaktif kini menjadi pilihan utama calon pengantin. Pelajari cara personalisasi nama tamu, pengelolaan RSVP, dan etika penyampaian pesan.',
                'content' => '<p>Pemanfaatan undangan digital bukan hanya menghemat biaya cetak dan kertas, namun juga mempermudah penghitungan porsi katering berkat integrasi formulir kehadiran instan.</p><h3>Gunakan Personalized Link</h3><p>Selalu gunakan fitur personalisasi agar nama masing-masing kerabat tertulis rapi di halaman pembuka undangan layaknya kartu cetak eksklusif.</p><h3>Sertakan Panduan Dresscode & Peta Lokasi yang Akurat</h3><p>Fitur navigasi satu klik menuju Google Maps dan panduan tema busana sangat diapresiasi oleh tamu undangan untuk mempermudah perencanaan kehadiran mereka.</p>',
                'status' => 'published',
                'is_featured' => false,
            ],
        ];
        foreach ($blogs as $i => $blog) {
            Blog::query()->updateOrCreate(
                ['slug' => Str::slug($blog['title'])],
                array_merge($blog, [
                    'views_count' => 350 + $i * 120,
                    'published_at' => now()->subDays(14 - $i * 4),
                ]),
            );
        }

        // Testimonials with genuine couple stories
        $testimonials = [
            [
                'name' => 'Dimas & Anindya',
                'role' => 'Pernikahan di The Grand Pavilion, Bandung',
                'content' => 'BrightDor benar-benar memangkas stres kami dalam mencari vendor pernikahan. Transparansi harga paket dan respons vendor yang cepat sangat membantu kami mengunci tanggal impian dalam waktu kurang dari satu minggu.',
                'rating' => 5,
                'wedding_date' => now()->subMonths(3)->toDateString(),
            ],
            [
                'name' => 'Rizky & Clarissa',
                'role' => 'Pernikahan di Plataran Dharmawangsa, Jakarta',
                'content' => 'Sistem booking dan pembayarannya sangat rapi. Kami memesan paket katering dan fotografer dari dua vendor berbeda di BrightDor, dan semuanya berjalan sinkron tanpa kendala koordinasi sedikit pun.',
                'rating' => 5,
                'wedding_date' => now()->subMonths(2)->toDateString(),
            ],
            [
                'name' => 'Farhan & Nadira',
                'role' => 'Pernikahan Botanical di Yogyakarta',
                'content' => 'Undangan digital dari BrightDor sangat praktis dan elegan! Para tamu memuji tampilan interaktifnya dan fitur konfirmasi kehadiran real-time membuat kami bisa memesan porsi prasmanan dengan sangat presisi.',
                'rating' => 5,
                'wedding_date' => now()->subMonths(1)->toDateString(),
            ],
            [
                'name' => 'Adrian & Michelle',
                'role' => 'Pernikahan Intimate di Uluwatu, Bali',
                'content' => 'Koleksi vendor di BrightDor terkurasi dengan standar yang tinggi. Foto portofolio yang ditampilkan sesuai dengan realitas di hari H, terutama dekorasi dan makeup pengantin yang luar biasa memuaskan.',
                'rating' => 5,
                'wedding_date' => now()->subWeeks(3)->toDateString(),
            ],
            [
                'name' => 'Taufik & Safira',
                'role' => 'Pernikahan Adat di Surabaya',
                'content' => 'Sangat mengapresiasi kejelasan rincian item tiap paket layanan. Tidak ada biaya tersembunyi, dan tim customer support BrightDor sangat sigap membantu ketika kami membutuhkan penyesuaian rundown.',
                'rating' => 5,
                'wedding_date' => now()->subWeeks(2)->toDateString(),
            ],
        ];
        foreach ($testimonials as $i => $t) {
            Testimonial::query()->updateOrCreate(
                ['name' => $t['name']],
                array_merge($t, ['is_active' => true, 'sort_order' => $i])
            );
        }

        // Banners
        Banner::query()->updateOrCreate(
            ['title' => 'Wujudkan Pernikahan Impian Bersama BrightDor'],
            [
                'subtitle' => 'Temukan kurasi venue mewah, katering terpercaya, dan vendor pernikahan profesional dalam satu platform terintegrasi.',
                'position' => 'home_hero',
                'is_active' => true,
                'sort_order' => 1,
            ],
        );

        // FAQs
        $faqs = [
            [
                'question' => 'Bagaimana alur pemesanan dan pembayaran vendor di BrightDor?',
                'answer' => 'Pilih vendor dan paket layanan yang Anda inginkan, tentukan tanggal acara, lalu ajukan booking. Setelah ketersediaan dikonfirmasi, lakukan pembayaran melalui Transfer Bank atau QRIS resmi. Dana Anda disimpan secara aman di rekening penampung (escrow) BrightDor hingga layanan selesai diverifikasi.',
                'category' => 'Pemesanan',
            ],
            [
                'question' => 'Metode pembayaran apa saja yang tersedia?',
                'answer' => 'BrightDor menyediakan pembayaran instan melalui QRIS (dapat dipindai dari BCA Mobile, Livin Mandiri, GoPay, OVO, ShopeePay, DANA) serta Transfer Bank langsung ke rekening resmi PT BrightDor Indonesia (BCA, Mandiri, BNI, BRI).',
                'category' => 'Pembayaran',
            ],
            [
                'question' => 'Bagaimana cara mendaftar dan memverifikasi usaha sebagai vendor?',
                'answer' => 'Klik menu "Daftar Vendor", lengkapi informasi profil usaha, izin usaha, portofolio karya, dan nomor rekening penarikan dana. Tim kurasi BrightDor akan melakukan verifikasi data maksimal dalam 1x24 jam kerja sebelum akun Anda aktif.',
                'category' => 'Vendor',
            ],
            [
                'question' => 'Apakah saya bisa mengajukan kustomisasi paket atau penawaran khusus?',
                'answer' => 'Tentu. Anda dapat menggunakan tombol "Ajukan Penawaran" pada halaman vendor untuk menyampaikan kebutuhan spesifik, estimasi tamu, atau permintaan penyesuaian menu/dekorasi langsung kepada vendor bersangkutan.',
                'category' => 'Layanan',
            ],
        ];
        foreach ($faqs as $i => $faq) {
            Faq::query()->updateOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['is_active' => true, 'sort_order' => $i])
            );
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

    private function seedExtendedVendors(): void
    {
        $categoryIds = VendorCategory::pluck('id')->toArray();
        $categoryNames = VendorCategory::pluck('name', 'id')->toArray();

        $prefixes = [
            'Griya', 'Pesona', 'Arunika', 'Cahaya', 'Mutiara',
            'Seruling', 'Wisma', 'Kencana', 'Nirwana', 'Bumi',
            'Adiyasa', 'Kirana', 'Swargaloka', 'Sriwedari', 'Mandala',
            'Puspita', 'Cendana', 'Dewi Sri', 'Garuda', 'Bintang',
            'Asri', 'Indah', 'Lestari', 'Megah', 'Prima',
        ];

        $cities = [
            'Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang',
            'Medan', 'Makassar', 'Palembang', 'Denpasar', 'Malang',
        ];

        $provinces = [
            'DKI Jakarta', 'Jawa Barat', 'Jawa Timur', 'DI Yogyakarta', 'Jawa Tengah',
            'Sumatera Utara', 'Sulawesi Selatan', 'Sumatera Selatan', 'Bali', 'Jawa Timur',
        ];

        $ownerFirst = ['Agus', 'Sri', 'Bambang', 'Endang', 'Slamet', 'Wahyuni', 'Sutrisno', 'Ratna', 'Suparman', 'Indah'];
        $ownerLast = ['Pratama', 'Wijaya', 'Santoso', 'Lestari', 'Kusuma', 'Hidayat', 'Nugroho', 'Rahayu', 'Setiawan', 'Permana'];
        $bankNames = ['BCA', 'Bank Mandiri', 'BNI', 'BRI', 'CIMB Niaga'];

        $catDescriptions = [
            'venue' => 'Menyediakan ballroom megah dan function hall serbaguna dengan fasilitas audio visual mutakhir, pendingin ruangan sentral, dan area parkir luas.',
            'catering' => 'Menyajikan aneka hidangan jamuan pesta pernikahan lezat dengan bahan segar berkualitas tinggi dan presentasi meja prasmanan artistik bintang lima.',
            'dekorasi' => 'Atelier tata ruang dan perancangan panggung pernikahan bertema elegan yang memadukan keindahan bunga segar pilihan dan pencahayaan panggung berkelas.',
            'fotografer' => 'Kolektif fotografer pernikahan berpengalaman yang mengabadikan setiap momen penuh kehangatan, emosi, dan ekspresi tulus pengantin serta keluarga.',
            'videografer' => 'Spesialis video sinematik pernikahan 4K dengan narasi audio emosional dan pengambilan gambar drone profesional untuk mengenang hari bahagia Anda.',
            'mua' => 'Tata rias pengantin profesional dengan produk kecantikan premium internasional, menghadirkan look natural glow yang tahan uji sepanjang hari.',
            'wedding organizer' => 'Konsultan dan perencana pernikahan komprehensif yang siap membantu mengelola anggaran, timeline, koordinasi vendor, dan kelancaran acara.',
            'entertainment' => 'Grup musik dan ensemble akustik pernikahan profesional dengan repertoar lagu romantis dan MC handal untuk kemeriahan resepsi pernikahan.',
            'gaun & jas' => 'Butik perancang kebaya adibusana dan setelan jas pengantin pria bespoke dengan material kain mewah dan fitting presisi sesuai kenyamanan Anda.',
            'undangan digital' => 'Layanan undangan digital interaktif responsif dengan fitur RSVP cepat, integrasi Google Maps, galeri pre-wedding, dan buku tamu online.',
        ];

        for ($i = 0; $i < 50; $i++) {
            $owner = $ownerFirst[$i % 10] . ' ' . $ownerLast[($i + 3) % 10];

            $user = User::query()->updateOrCreate(
                ['email' => 'vendor.ext' . ($i + 1) . '@brightdor.test'],
                [
                    'name' => $owner,
                    'password' => Hash::make('password'),
                    'user_type' => 'vendor',
                    'status' => 'active',
                    'phone' => '081' . str_pad((string) (1000000 + $i), 7, '0', STR_PAD_LEFT),
                ],
            );
            $user->syncRoles(['vendor']);

            $catId = $categoryIds[$i % count($categoryIds)];
            $catName = $categoryNames[$catId] ?? 'Vendor';
            $city = $cities[$i % count($cities)];
            $businessName = $prefixes[$i % count($prefixes)] . ' ' . $catName . ' ' . $city;

            $status = $i % 5 === 0 ? 'pending' : 'approved';
            $isVerified = $status === 'approved' && $i % 3 !== 0;
            $descSnippet = $catDescriptions[strtolower($catName)] ?? "Penyedia layanan {$catName} profesional di {$city}.";

            $vendor = Vendor::query()->updateOrCreate(
                ['slug' => Str::slug($businessName) . '-' . ($i + 100)],
                [
                    'user_id' => $user->id,
                    'vendor_category_id' => $catId,
                    'business_name' => $businessName,
                    'description' => "{$businessName} adalah mitra resmi BrightDor di {$city}. {$descSnippet} Didukung tim berdedikasi dengan pengalaman lebih dari " . (3 + ($i % 8)) . ' tahun di industri pernikahan Indonesia.',
                    'address' => 'Jl. Merdeka No. ' . (10 + $i) . ', ' . $city,
                    'city' => $city,
                    'province' => $provinces[$i % count($provinces)],
                    'phone' => '021-' . (1000000 + $i),
                    'whatsapp' => '0812' . str_pad((string) (100000 + $i), 6, '0', STR_PAD_LEFT),
                    'status' => $status,
                    'is_verified' => $isVerified,
                    'verified_at' => $isVerified ? now()->subDays(30 + $i) : null,
                    'is_featured' => $i % 7 === 0,
                    'rating_avg' => $status === 'approved' ? round(4.5 + (($i % 5) / 10), 2) : 0,
                    'rating_count' => $status === 'approved' ? 8 + ($i % 30) : 0,
                    'bank_name' => $bankNames[$i % count($bankNames)],
                    'bank_account_number' => '1' . str_pad((string) (2340000 + $i), 12, '0', STR_PAD_LEFT),
                    'bank_account_name' => $owner,
                ],
            );

            if ($vendor->getMedia('portfolio')->isEmpty()) {
                $imagePath = $this->resolveSeederImagePath(strtolower($catName), $i);
                if ($imagePath && file_exists($imagePath)) {
                    $vendor->addMedia($imagePath)->preservingOriginal()->toMediaCollection('portfolio');
                }
            }
        }
    }

    private function seedExtendedServices(): void
    {
        $vendors = Vendor::where('status', 'approved')->get();

        if ($vendors->isEmpty()) {
            return;
        }

        $categoryNames = VendorCategory::pluck('name', 'id')->toArray();
        $tiers = ['Silver Elegance', 'Gold Royal', 'Platinum Luxury', 'Diamond Signature', 'Exclusive Atelier'];

        $priceByKeyword = [
            'venue' => 45000000, 'catering' => 35000000, 'dekorasi' => 25000000,
            'fotografer' => 12000000, 'videografer' => 15000000, 'mua' => 8000000,
            'wedding organizer' => 30000000, 'entertainment' => 7000000,
            'gaun & jas' => 6000000, 'undangan digital' => 300000,
        ];

        for ($i = 0; $i < 100; $i++) {
            $vendor = $vendors[$i % $vendors->count()];
            $catName = strtolower($categoryNames[$vendor->vendor_category_id] ?? 'paket');
            $tier = $tiers[$i % count($tiers)];
            $name = 'Paket ' . $tier . ' ' . ucwords($catName);
            $basePrice = $priceByKeyword[$catName] ?? 10000000;
            $price = round($basePrice * (0.7 + ($i % 4) * 0.15), -5);

            $featuresList = [
                ['Layanan tim profesional tersertifikasi', 'Konsultasi konsep & rundown acara', 'Garansi kepuasan & protokol resmi BrightDor', 'Dukungan koordinasi hari H'],
                ['Bahan & material kualitas premium', 'Pengawasan supervisor berpengalaman', 'Free trial / sesi konsultasi pra-acara', 'Laporan dokumentasi & serah terima rapi'],
            ];

            $service = Service::query()->updateOrCreate(
                ['slug' => Str::slug($name) . '-' . $vendor->id . '-' . ($i + 1)],
                [
                    'vendor_id' => $vendor->id,
                    'vendor_category_id' => $vendor->vendor_category_id,
                    'name' => $name,
                    'description' => 'Paket ' . $tier . ' ' . ucwords($catName) . ' persembahan eksklusif dari '
                        . $vendor->business_name . '. Dirancang untuk menghadirkan kenyamanan prima dan kesempurnaan momen pernikahan Anda.',
                    'price' => $price,
                    'price_unit' => $catName === 'catering' ? 'per porsi' : ($catName === 'undangan digital' ? 'per tema' : 'per event'),
                    'capacity' => 200 + ($i % 8) * 100,
                    'features' => $featuresList[$i % 2],
                    'is_active' => true,
                    'status' => 'published',
                    'is_featured' => $i % 8 === 0,
                    'views_count' => 120 + $i * 5,
                    'bookings_count' => 2 + ($i % 12),
                ],
            );

            if ($service->getMedia('cover')->isEmpty()) {
                $imagePath = $this->resolveSeederImagePath($catName, $i);
                if ($imagePath && file_exists($imagePath)) {
                    $service->addMedia($imagePath)->preservingOriginal()->toMediaCollection('cover');
                }
            }
        }
    }

    /**
     * Resolve realistic, royalty-free seed image path for any vendor category.
     * Rotates through 4 curated high-res photos per category.
     */
    private function resolveSeederImagePath(string $categoryName, int $index = 0): ?string
    {
        $cat = strtolower($categoryName);
        $num = ($index % 4) + 1;

        if (str_contains($cat, 'venue')) {
            $path = database_path("seeders/images/venue/venue_{$num}.jpg");
            if (file_exists($path)) return $path;
            return database_path('seeders/images/venue.png');
        }
        if (str_contains($cat, 'catering')) {
            $path = database_path("seeders/images/catering/catering_{$num}.jpg");
            if (file_exists($path)) return $path;
            return database_path('seeders/images/catering.png');
        }
        if (str_contains($cat, 'dekorasi')) {
            $path = database_path("seeders/images/dekorasi/dekorasi_{$num}.jpg");
            if (file_exists($path)) return $path;
            return database_path('seeders/images/decoration.png');
        }
        if (str_contains($cat, 'fotografer')) {
            $path = database_path("seeders/images/fotografer/fotografer_{$num}.jpg");
            if (file_exists($path)) return $path;
            return database_path('seeders/images/photography.png');
        }
        if (str_contains($cat, 'videografer')) {
            $path = database_path("seeders/images/videografer/videografer_{$num}.jpg");
            if (file_exists($path)) return $path;
            return database_path('seeders/images/photography.png');
        }
        if (str_contains($cat, 'mua') || str_contains($cat, 'makeup')) {
            $path = database_path("seeders/images/mua/mua_{$num}.jpg");
            if (file_exists($path)) return $path;
            return database_path('seeders/images/decoration.png');
        }
        if (str_contains($cat, 'wedding organizer') || str_contains($cat, 'organizer')) {
            $path = database_path("seeders/images/wedding_organizer/wo_{$num}.jpg");
            if (file_exists($path)) return $path;
            return database_path('seeders/images/venue.png');
        }
        if (str_contains($cat, 'entertainment') || str_contains($cat, 'musik')) {
            $path = database_path("seeders/images/entertainment/entertainment_{$num}.jpg");
            if (file_exists($path)) return $path;
            return database_path('seeders/images/venue.png');
        }
        if (str_contains($cat, 'gaun') || str_contains($cat, 'jas') || str_contains($cat, 'attire')) {
            $path = database_path("seeders/images/gaun_jas/gaun_jas_{$num}.jpg");
            if (file_exists($path)) return $path;
            return database_path('seeders/images/decoration.png');
        }
        if (str_contains($cat, 'undangan')) {
            $path = database_path("seeders/images/undangan_digital/undangan_{$num}.jpg");
            if (file_exists($path)) return $path;
            return database_path('seeders/images/venue.png');
        }

        return database_path('seeders/images/venue.png');
    }

    private function seedExtendedBookings(): void
    {
        $coupleFirst = ['Dimas', 'Aditya', 'Fikri', 'Gilang', 'Hafiz', 'Iqbal', 'Reza', 'Taufik', 'Yoga', 'Zaki'];
        $coupleSecond = ['Ayu', 'Citra', 'Dina', 'Eka', 'Fitri', 'Gita', 'Hana', 'Intan', 'Lia', 'Nadia'];

        $coupleIds = [];

        for ($c = 0; $c < 40; $c++) {
            $name = $coupleFirst[$c % 10] . ' & ' . $coupleSecond[($c + 4) % 10];
            $user = User::query()->updateOrCreate(
                ['email' => 'couple.ext' . ($c + 1) . '@brightdor.test'],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'user_type' => 'couple',
                    'status' => 'active',
                    'phone' => '082' . str_pad((string) (2000000 + $c), 7, '0', STR_PAD_LEFT),
                ],
            );
            $user->syncRoles(['couple']);
            $coupleIds[] = $user->id;
        }

        $services = Service::where('is_active', true)->get();

        if ($services->isEmpty()) {
            return;
        }

        $statusPattern = [
            'pending', 'confirmed', 'completed', 'cancelled', 'on_progress',
            'completed', 'confirmed', 'pending', 'completed', 'refund',
        ];

        $locations = ['Jakarta Convention Center', 'The Ritz Carlton', 'Fairmont', 'Ballroom Hotel Mulia', 'Gedung Serbaguna'];
        $rateByCategory = VendorCategory::pluck('commission_rate', 'id')->toArray();

        for ($i = 0; $i < 200; $i++) {
            $service = $services[$i % $services->count()];
            $status = $statusPattern[$i % count($statusPattern)];
            $subtotal = (float) $service->price;
            $discount = $i % 8 === 0 ? round($subtotal * 0.05, 2) : 0;
            $rate = (float) ($rateByCategory[$service->vendor_category_id] ?? 10);
            $commission = round(($subtotal - $discount) * $rate / 100, 2);
            $total = round($subtotal - $discount, 2);

            Booking::query()->updateOrCreate(
                ['booking_code' => 'BD-EXT' . str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT)],
                [
                    'user_id' => $coupleIds[$i % count($coupleIds)],
                    'vendor_id' => $service->vendor_id,
                    'service_id' => $service->id,
                    'event_date' => now()->addDays($i - 60)->toDateString(),
                    'event_time' => sprintf('%02d:00', 8 + ($i % 10)),
                    'event_location' => $locations[$i % count($locations)],
                    'guest_count' => 100 + ($i % 9) * 100,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'admin_fee' => 0,
                    'commission_amount' => $commission,
                    'total_amount' => $total,
                    'status' => $status,
                    'confirmed_at' => in_array($status, ['confirmed', 'on_progress', 'completed'], true) ? now()->subDays(20) : null,
                    'completed_at' => $status === 'completed' ? now()->subDays(5) : null,
                    'cancelled_at' => in_array($status, ['cancelled', 'refund'], true) ? now()->subDays(10) : null,
                    'cancellation_reason' => in_array($status, ['cancelled', 'refund'], true) ? 'Perubahan jadwal dari customer' : null,
                ],
            );
        }
    }
}
