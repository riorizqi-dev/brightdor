# BrightDor — Database Schema

## ER Overview

```
users ─┬─ vendors ── services ── bookings
       │     │                      │
       │     └─ payouts             └─ transactions (morph)
       ├─ invitation_orders ── invitations ── invitation_rsvps
       ├─ blogs
       └─ transactions

vendor_categories ── vendors / services / commission_settings
invitation_template_categories ── invitation_templates ── invitation_orders
```

## Tables

### Auth & Users
| Table | Notes |
|-------|-------|
| `users` | + phone, avatar, user_type (admin/vendor/couple), status, last_login_at |
| `roles` / `permissions` | Spatie Permission |

### Vendors
| Table | Notes |
|-------|-------|
| `vendor_categories` | Venue, Catering, MUA, dll + commission_rate |
| `vendors` | Profil lengkap, status approval, bank, rating |
| `services` | Paket jasa vendor, featured, moderasi |

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
| `transactions` | Polymorphic payable, Midtrans/Xendit ready |
| `commission_settings` | Global / per kategori |
| `payouts` | Withdrawal vendor |

### CMS & Settings
| Table | Notes |
|-------|-------|
| `blogs`, `testimonials`, `banners`, `faqs`, `galleries` | Content management |
| `settings` | key-value grouped (general, payment, social, email) |
| `activity_logs` | Audit trail sederhana |
| `media` | Spatie Media Library |
