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
}