# BrightDor Admin Dashboard

Admin dashboard untuk **BrightDor** — marketplace profesional jasa pernikahan + undangan online di Indonesia.

## Tech Stack

- Laravel 11
- Filament v5
- Spatie Laravel Permission
- Spatie Media Library
- Maatwebsite Excel
- Chart.js (via Filament widgets)
- Midtrans / Xendit ready (settings)

## Layout

- **Top Navigation (Header bar) only**
- **Tidak ada sidebar**
- Theme: soft gold (`#C9A227`) + deep navy (`#1B2A4A`)
- Dark mode support

## Setup

```bash
cd C:\laragon\www\brightdor

# Install (jika belum)
composer install

# Environment
copy .env.example .env
php artisan key:generate

# Database (default SQLite sudah siap; ganti ke MySQL di .env jika perlu)
php artisan migrate --seed

# Storage link (media)
php artisan storage:link

# Jalankan
php artisan serve
```

Buka: **http://127.0.0.1:8000/admin**

### Login Admin

| Field    | Value                 |
|----------|-----------------------|
| Email    | `admin@brightdor.test`|
| Password | `password`            |

## Modul Admin

| Group | Menu |
|-------|------|
| Overview | Dashboard + stats + charts |
| Vendors | Manajemen Vendor, Kategori Vendor |
| Marketplace | Produk/Jasa, Booking & Order, User/Couple |
| Undangan Digital | Kategori Template, Template, Order, Undangan Aktif |
| Keuangan | Transaksi, Payout Vendor, Commission Rate |
| Konten | Blog, Testimonial, Banner, FAQ, Gallery |
| Pengaturan | Settings (general, payment, social, email) |

## Struktur Database Utama

- `users` — admin / vendor / couple
- `vendor_categories`, `vendors`, `services`
- `bookings`
- `invitation_template_categories`, `invitation_templates`, `invitation_orders`, `invitations`, `invitation_rsvps`
- `transactions`, `commission_settings`, `payouts`
- `blogs`, `testimonials`, `banners`, `faqs`, `galleries`
- `settings`, `activity_logs`
- Spatie: `roles`, `permissions`, `media`

## Catatan Navigasi

Konfigurasi panel di `app/Providers/Filament/AdminPanelProvider.php`:

```php
->topNavigation()  // header bar horizontal, no sidebar
->colors([
    'primary' => Color::hex('#C9A227'),
    'secondary' => Color::hex('#1B2A4A'),
])
->darkMode(true)
->databaseNotifications()
```

## Langkah Selanjutnya (opsional)

1. Integrasi Midtrans / Xendit SDK
2. Export Excel/PDF laporan keuangan
3. Notifikasi real-time (Laravel Echo / Reverb)
4. Policy & permission detail per resource
5. Panel terpisah untuk Vendor & Couple
