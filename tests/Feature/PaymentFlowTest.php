<?php

namespace Tests\Feature;

use App\Filament\Resources\Transactions\Pages\ListTransactions;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use App\Services\PaymentService;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    private function couple(string $email = 'lima@brightdor.test'): User
    {
        return User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Pasangan Lima',
                'password' => Hash::make('password'),
                'user_type' => 'couple',
                'status' => 'active',
                'phone' => '081300000001',
            ],
        );
    }

    private function approvedVendorAndService(): array
    {
        $vendor = Vendor::query()
            ->where('status', 'approved')
            ->with(['services' => fn ($q) => $q->where('status', 'published')->where('is_active', true)])
            ->firstOrFail();

        $service = $vendor->services->first() ?? Service::query()->create([
            'vendor_id' => $vendor->id,
            'name' => 'Paket Uji Pembayaran',
            'price' => 1_500_000,
            'status' => 'published',
            'is_active' => true,
        ]);

        return [$vendor, $service];
    }

    private function createBooking(User $couple): Booking
    {
        [$vendor, $service] = $this->approvedVendorAndService();

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.booking', $vendor->slug), [
                'name' => $couple->name,
                'email' => $couple->email,
                'phone' => $couple->phone ?? '081300000001',
                'service_id' => $service->id,
                'event_date' => now()->addMonths(2)->toDateString(),
                'guest_count' => 200,
            ])
            ->assertSessionHas('success');

        return Booking::query()->where('user_id', $couple->id)->latest('id')->firstOrFail();
    }

    public function test_booking_creation_registers_pending_payment_transaction(): void
    {
        $couple = $this->couple();
        $booking = $this->createBooking($couple);

        $this->assertSame('pending', $booking->status);
        $this->assertGreaterThan(0.0, (float) $booking->total_amount);

        $transaction = $booking->transactions->first();
        $this->assertNotNull($transaction, 'Payment transaction should be auto-created.');
        $this->assertSame('payment', $transaction->type);
        $this->assertSame('pending', $transaction->status);
        $this->assertEquals((float) $booking->total_amount, (float) $transaction->amount);
        $this->assertSame((int) $booking->user_id, (int) $transaction->user_id);
    }

    public function test_booking_creation_computes_commission_for_vendor_category(): void
    {
        $couple = $this->couple('gita@brightdor.test');
        $booking = $this->createBooking($couple);

        $this->assertGreaterThanOrEqual(0.0, (float) $booking->commission_amount);
        $this->assertSame('pending', $booking->status);
    }

    public function test_couple_can_open_and_submit_payment_form(): void
    {
        Storage::fake('public');

        $couple = $this->couple();
        $booking = $this->createBooking($couple);
        $transaction = $booking->transactions->firstOrFail();

        $this->actingAs($couple)
            ->get(route('my-bookings.payment', $booking))
            ->assertOk()
            ->assertSee('Bayar Booking')
            ->assertSee($transaction->transaction_code);

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.payment.store', $booking), [
                'payment_method' => 'bank_transfer',
                'payment_reference' => 'TRX-BANK-20260701-001',
                'payment_proof' => UploadedFile::fake()->image('bukti.jpg'),
            ])
            ->assertRedirect(route('my-bookings.index'))
            ->assertSessionHas('success');

        $transaction->refresh();

        $this->assertSame('bank_transfer', $transaction->payment_method);
        $this->assertSame('manual', $transaction->payment_gateway);
        $this->assertSame('TRX-BANK-20260701-001', $transaction->gateway_reference);
        $this->assertNotNull($transaction->payment_proof);
        $this->assertSame('pending', $transaction->status);

        Storage::disk('public')->assertExists($transaction->payment_proof);
    }

    public function test_non_owner_cannot_access_payment_page_or_submit_payment(): void
    {
        $couple = $this->couple();
        $booking = $this->createBooking($couple);

        $otherCouple = $this->couple('orang-lain@brightdor.test');

        $this->actingAs($otherCouple)
            ->get(route('my-bookings.payment', $booking))
            ->assertForbidden();

        $this->actingAs($otherCouple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.payment.store', $booking), [
                'payment_method' => 'ewallet',
                'payment_reference' => 'EW-999',
            ])
            ->assertForbidden();

        $this->assertSame('pending', $booking->transactions->firstOrFail()->status);
    }

    public function test_payment_rejected_when_booking_can_no_longer_be_paid(): void
    {
        $couple = $this->couple('sela@brightdor.test');
        $booking = $this->createBooking($couple);

        $booking->forceFill([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ])->save();
        $booking->forceFill(['status' => 'on_progress'])->save();
        $booking->forceFill([
            'status' => 'completed',
            'completed_at' => now(),
        ])->save();

        $this->actingAs($couple)
            ->get(route('my-bookings.payment', $booking))
            ->assertForbidden();
    }

    public function test_admin_validation_marks_paid_and_confirms_booking(): void
    {
        $couple = $this->couple();
        $booking = $this->createBooking($couple);
        $transaction = $booking->transactions->firstOrFail();

        PaymentService::markAsPaid($transaction);

        $transaction->refresh();
        $booking->refresh();

        $this->assertSame('success', $transaction->status);
        $this->assertNotNull($transaction->paid_at);
        $this->assertSame('confirmed', $booking->status);
        $this->assertNotNull($booking->confirmed_at);
    }

    public function test_webhook_marks_payment_paid_and_confirms_booking_automatically(): void
    {
        config()->set('services.brightdor.webhook_key', 'test-webhook-key');

        $couple = $this->couple('wuri@brightdor.test');
        $booking = $this->createBooking($couple);
        $transaction = $booking->transactions->firstOrFail();

        $this->postJson(
            '/webhook/payment',
            ['transaction_code' => $transaction->transaction_code],
            ['X-BrightDor-Key' => 'test-webhook-key'],
        )
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('booking_code', $booking->booking_code);

        $transaction->refresh();
        $booking->refresh();

        $this->assertSame('success', $transaction->status);
        $this->assertNotNull($transaction->paid_at);
        $this->assertSame('confirmed', $booking->status);
        $this->assertNotNull($booking->confirmed_at);
    }

    public function test_webhook_rejects_requests_without_valid_key(): void
    {
        config()->set('services.brightdor.webhook_key', 'test-webhook-key');

        $couple = $this->couple('ayu@brightdor.test');
        $booking = $this->createBooking($couple);
        $transaction = $booking->transactions->firstOrFail();

        $this->postJson(
            '/webhook/payment',
            ['transaction_code' => $transaction->transaction_code],
            ['X-BrightDor-Key' => 'salah-key'],
        )->assertUnauthorized();

        $this->assertSame('pending', $transaction->refresh()->status);
    }

    public function test_webhook_returns_404_for_unknown_transaction(): void
    {
        config()->set('services.brightdor.webhook_key', 'test-webhook-key');

        $this->postJson(
            '/webhook/payment',
            ['transaction_code' => 'TRX-NOT-EXISTS'],
            ['X-BrightDor-Key' => 'test-webhook-key'],
        )->assertNotFound();
    }

    public function test_booking_index_shows_pay_button_for_pending_booking(): void
    {
        $couple = $this->couple();
        $booking = $this->createBooking($couple);

        $this->actingAs($couple)
            ->get(route('my-bookings.index'))
            ->assertOk()
            ->assertSee('Bayar Sekarang')
            ->assertSee(route('my-bookings.payment', $booking), false);
    }

    public function test_transaction_model_keeps_proof_path_and_meta(): void
    {
        $couple = $this->couple('dila@brightdor.test');
        $booking = $this->createBooking($couple);
        $transaction = $booking->transactions->firstOrFail();

        $transaction->forceFill([
            'payment_proof' => 'payments/bukti.png',
            'meta' => ['submit_count' => 2],
        ])->save();

        $transaction->refresh();

        $this->assertSame('payments/bukti.png', $transaction->payment_proof);
        $this->assertSame(2, $transaction->meta['submit_count']);
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertSame('BD-', substr((string) $booking->booking_code, 0, 3));
    }

    public function test_couple_can_still_pay_after_vendor_confirms_booking(): void
    {
        $couple = $this->couple('nadya@brightdor.test');
        $booking = $this->createBooking($couple);
        $transaction = $booking->transactions->firstOrFail();

        $booking->forceFill([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ])->save();

        $this->actingAs($couple)
            ->get(route('my-bookings.payment', $booking))
            ->assertOk()
            ->assertSee('Bayar Booking');

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.payment.store', $booking), [
                'payment_method' => 'bank_transfer',
                'payment_reference' => 'TRX-AFTER-VENDOR-CONFIRM',
            ])
            ->assertRedirect(route('my-bookings.index'))
            ->assertSessionHas('success');

        $transaction->refresh();
        $this->assertSame('pending', $transaction->status);
        $this->assertSame('TRX-AFTER-VENDOR-CONFIRM', $transaction->gateway_reference);
        $this->assertSame('confirmed', $booking->refresh()->status);

        PaymentService::markAsPaid($transaction);

        $transaction->refresh();
        $booking->refresh();

        $this->assertSame('success', $transaction->status);
        $this->assertNotNull($transaction->paid_at);
        $this->assertSame('confirmed', $booking->status);
    }

    public function test_couple_can_submit_qris_payment(): void
    {
        $couple = $this->couple('qris-user@brightdor.test');
        $booking = $this->createBooking($couple);
        $transaction = $booking->transactions->firstOrFail();

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.payment.store', $booking), [
                'payment_method' => 'qris',
                'payment_reference' => 'RRN-QRIS-20260906-0099',
            ])
            ->assertRedirect(route('my-bookings.index'))
            ->assertSessionHas('success');

        $transaction->refresh();
        $this->assertSame('qris', $transaction->payment_method);
        $this->assertSame('RRN-QRIS-20260906-0099', $transaction->gateway_reference);
        $this->assertSame('pending', $transaction->status);
    }

    public function test_admin_can_render_transactions_list(): void
    {
        $couple = $this->couple('devina@brightdor.test');
        $this->createBooking($couple);

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@brightdor.test'],
            [
                'name' => 'BrightDor Admin',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'status' => 'active',
                'phone' => '081234567890',
            ],
        );

        $this->actingAs($admin);

        Livewire::test(ListTransactions::class)
            ->assertSuccessful();
    }

    public function test_new_payment_transaction_has_24_hour_expiry(): void
    {
        $couple = $this->couple('expiry@brightdor.test');
        $booking = $this->createBooking($couple);

        $transaction = $booking->transactions()->where('type', 'payment')->latest('id')->firstOrFail();

        $this->assertNotNull($transaction->expires_at);
        // Batas waktu sekitar 24 jam dari sekarang (toleransi beberapa detik eksekusi).
        $this->assertTrue($transaction->expires_at->greaterThan(now()->addHours(23)));
        $this->assertTrue($transaction->expires_at->lessThan(now()->addHours(25)));
    }

    public function test_overdue_pending_transaction_auto_expires_on_payment_page(): void
    {
        $couple = $this->couple('overdue@brightdor.test');
        $booking = $this->createBooking($couple);

        $transaction = $booking->transactions()->where('type', 'payment')->latest('id')->firstOrFail();

        // Paksa transaksi seolah sudah lewat batas 24 jam.
        $transaction->forceFill(['expires_at' => now()->subHour()])->save();

        $this->actingAs($couple)
            ->get(route('my-bookings.payment', $booking))
            ->assertOk()
            ->assertSee('Kedaluwarsa')
            ->assertSee('Batas Waktu Pembayaran Habis');

        $this->assertSame('expired', $transaction->fresh()->status);
    }

    public function test_expired_transaction_cannot_be_submitted(): void
    {
        $couple = $this->couple('expiredsubmit@brightdor.test');
        $booking = $this->createBooking($couple);

        $transaction = $booking->transactions()->where('type', 'payment')->latest('id')->firstOrFail();
        $transaction->forceFill(['expires_at' => now()->subHour()])->save();

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.payment.store', $booking), [
                'payment_method' => 'qris',
                'payment_reference' => 'RRN-EXPIRED-001',
            ])
            ->assertSessionHasErrors('booking');

        $this->assertSame('expired', $transaction->fresh()->status);
    }

    public function test_vendor_payout_uses_subtotal_minus_commission_not_total(): void
    {
        // Vendor baru yang bersih agar tidak terpengaruh booking lain.
        $vendorUser = User::query()->create([
            'name' => 'Vendor Payout Calc',
            'email' => 'vendor-payout-calc@example.test',
            'phone' => '0812999000111',
            'password' => Hash::make('password'),
            'user_type' => 'vendor',
            'status' => 'active',
        ]);
        $vendor = Vendor::query()->create([
            'user_id' => $vendorUser->id,
            'business_name' => 'Vendor Payout Calc',
            'city' => 'Jakarta',
            'status' => 'approved',
        ]);
        $service = Service::query()->create([
            'vendor_id' => $vendor->id,
            'name' => 'Paket Payout Calc',
            'price' => 2_000_000,
            'status' => 'published',
            'is_active' => true,
        ]);

        $couple = $this->couple('payoutcalc@brightdor.test');

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.booking', $vendor->slug), [
                'name' => $couple->name,
                'email' => $couple->email,
                'phone' => $couple->phone ?? '081300000001',
                'service_id' => $service->id,
                'event_date' => now()->addMonths(2)->toDateString(),
            ])
            ->assertSessionHas('success');

        $booking = Booking::query()->where('user_id', $couple->id)->latest('id')->firstOrFail();

        // Simulasikan booking selesai dengan admin_fee > 0 untuk memastikan
        // admin_fee TIDAK ikut ke saldo vendor. Lewati transisi status yang valid.
        $booking->forceFill(['status' => 'confirmed', 'confirmed_at' => now()])->save();
        $booking->forceFill(['status' => 'on_progress'])->save();
        $booking->forceFill([
            'status' => 'completed',
            'completed_at' => now(),
            'admin_fee' => 50_000,
            'total_amount' => (float) $booking->subtotal + 50_000,
        ])->save();

        $expected = (float) $booking->subtotal - (float) $booking->commission_amount;

        $this->assertSame(round($expected, 2), $vendor->payoutsAvailable());
    }

    public function test_guest_booking_creates_loginable_account_with_temporary_password(): void
    {
        [$vendor, $service] = $this->approvedVendorAndService();

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.booking', $vendor->slug), [
                'name' => 'Tamu Baru',
                'email' => 'tamu-baru@example.test',
                'phone' => '0812000111222',
                'service_id' => $service->id,
                'event_date' => now()->addMonths(3)->toDateString(),
            ])
            ->assertSessionHas('success');

        $user = User::query()->where('email', 'tamu-baru@example.test')->firstOrFail();

        // Akun guest harus bisa login (password-nya diketahui dari flash message).
        $this->assertSame('couple', $user->user_type);
        $this->assertNotNull($user->password);
    }

    /**
     * Simulasi siklus uang lengkap ujung-ke-ujung:
     * booking -> bayar -> validasi admin -> vendor proses -> selesai -> saldo payout.
     */
    public function test_full_money_cycle_from_booking_to_vendor_payout_balance(): void
    {
        // Vendor bersih agar saldo payout tidak terpengaruh booking lain.
        $vendorUser = User::query()->create([
            'name' => 'Vendor Full Cycle',
            'email' => 'vendor-fullcycle@example.test',
            'phone' => '0812999000222',
            'password' => Hash::make('password'),
            'user_type' => 'vendor',
            'status' => 'active',
        ]);
        $vendor = Vendor::query()->create([
            'user_id' => $vendorUser->id,
            'business_name' => 'Vendor Full Cycle',
            'city' => 'Jakarta',
            'status' => 'approved',
        ]);
        $service = Service::query()->create([
            'vendor_id' => $vendor->id,
            'name' => 'Paket Full Cycle',
            'price' => 2_000_000,
            'status' => 'published',
            'is_active' => true,
        ]);

        $couple = $this->couple('fullcycle@brightdor.test');

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.booking', $vendor->slug), [
                'name' => $couple->name,
                'email' => $couple->email,
                'phone' => $couple->phone ?? '081300000001',
                'service_id' => $service->id,
                'event_date' => now()->addMonths(2)->toDateString(),
            ])
            ->assertSessionHas('success');

        $booking = Booking::query()->where('user_id', $couple->id)->latest('id')->firstOrFail();

        // 1. Booking dibuat -> transaksi pending dengan batas 24 jam.
        $transaction = $booking->transactions()->where('type', 'payment')->latest('id')->firstOrFail();
        $this->assertSame('pending', $transaction->status);
        $this->assertNotNull($transaction->expires_at);

        // 2. Couple kirim bukti pembayaran.
        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('my-bookings.payment.store', $booking), [
                'payment_method' => 'qris',
                'payment_reference' => 'RRN-FULLCYCLE-001',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('qris', $transaction->fresh()->payment_method);

        // 3. Admin validasi -> transaksi sukses, booking terkonfirmasi.
        PaymentService::markAsPaid($transaction->fresh());
        $this->assertSame('success', $transaction->fresh()->status);
        $this->assertSame('confirmed', $booking->fresh()->status);

        // 4. Vendor jalankan acara sampai selesai (refresh agar status terkini).
        $booking->refresh()->forceFill(['status' => 'on_progress'])->save();
        $booking->refresh()->forceFill(['status' => 'completed', 'completed_at' => now()])->save();

        // 5. Saldo payout vendor = subtotal - komisi (admin_fee tidak ikut).
        $expected = (float) $booking->subtotal - (float) $booking->commission_amount;
        $this->assertSame(round($expected, 2), $vendor->fresh()->payoutsAvailable());
    }
}