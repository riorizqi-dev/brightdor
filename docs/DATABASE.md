# BrightDor — Database Schema
_Sync 1 Sep 2026 — 37 tabel (19 migrations) — Laravel 11 + Filament v5 — SQLite_

## ER Overview

```
users ( + vendor_subscription_status/plan/expires_at ) ─┬─ vendors (UNIQUE user_id) ── services (cover+gallery via media) ── bookings (commission) ── reviews (1 booking = 1 review)
       │     │                      │                          │
       │     ├─ vendor_documents    └─ payouts                 └─ transactions (payable morph: bookings / invitation_orders)
       │     │                      │
       │     ├─ invitation_orders ── invitations ── invitation_rsvps
       │     ├─ blogs
       │     └─ transactions
       │     ├─ audit_logs (model, old/new, ip)
       │     └─ notifications (morph notifiable)
vendor_categories ── vendors / services / commission_settings
invitation_template_categories ── invitation_templates (preview+gallery via media) ── invitation_orders
media (polymorphic: model_type/id, collections: vendors.logo/portfolio/documents, services.cover/gallery, invitation_templates.preview/gallery) stored on `public` disk
```

## Tables

### Auth & Users
| Table | Notes |
|-------|-------|
| `users` | + phone, avatar, user_type (admin/vendor/couple), status, last_login_at, **vendor_subscription_status / plan / expires_at** (2026-08-20) |
| `roles` / `permissions` | Spatie Permission (5 tables) |
| `notifications` | Laravel morph `notifiable_type/id`, UUID pk, `type`, `data`, `read_at` — **new 2026-09-01** |
| `audit_logs` | **new 2026-09-01** — `user_id`, `action`, `model`, `old_values`/`new_values` json, `ip_address`, `user_agent`, `created_at` only |
| `activity_logs` | Audit lama — `subject_type/id` polymorphic |
| `media` | Spatie Media Library — `model_type/id` polymorphic, `collection_name` (`vendors.logo(single)/portfolio/documents`, `services.cover(single)/gallery`, `invitation_templates.preview(single)/gallery`), `disk=public` |

### Vendors
| Table | Notes |
|-------|-------|
| `vendor_categories` | Venue, Catering, MUA, dll + commission_rate |
| `vendors` | Profil lengkap, status approval, bank, rating, **UNIQUE(user_id)** per 2026-09-01 (1 user = 1 vendor), media via `media` table |
| `services` | Paket jasa vendor, featured, moderasi, **media: cover(single) + gallery** via Spatie |
| `vendor_documents` | **new 2026-09-01** — `vendor_id` FK, `document_type`, `file_path`, `original_name`, `mime_type`, `status` (pending/approved/rejected), `reviewed_at` |
| `reviews` | **new 2026-08-23** — `vendor_id`, `service_id` nullable, `booking_id` FK, `user_id` FK, `rating` 1-5, `content`, `is_verified`, UNIQUE(booking_id, user_id) |

### Bookings
| Table | Notes |
|-------|-------|
| `bookings` | pending → confirmed → on_progress → completed / cancelled / refund |

### Undangan Digital
| Table | Notes |
|-------|-------|
| `invitation_template_categories` | Kategori template |
| `invitation_templates` | Template + harga + featured |
| `invitation_orders` | Order undangan + subdomain/custom domain |
| `invitations` | Undangan live + views + RSVP counters |
| `invitation_rsvps` | RSVP tamu |

### Keuangan
| Table | Notes |
|-------|-------|
| `transactions` | Polymorphic `payable_type/id` → bookings / invitation_orders, Midtrans/Xendit ready |
| `commission_settings` | Global / per kategori |
| `payouts` | Withdrawal vendor, `processed_by` → users |

### Undangan Digital — Media
| Table | Notes |
|-------|-------|
| `invitation_templates` | **media: preview(single) + gallery** via Spatie Media Library (disk `public`) |

### CMS & Settings
| Table | Notes |
|-------|-------|
| `blogs`, `testimonials`, `banners`, `faqs`, `galleries` | Content management — `galleries.vendor_id` FK nullable, `faqs.category` |
| `settings` | key-value grouped (general, commission, payment, email, social), `type` (string/json/boolean/number/file), cached |
| `cache` / `cache_locks` / `jobs` / `job_batches` / `failed_jobs` | Laravel system tables (SQLite) |
