<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\CommissionSetting;
use App\Models\Transaction;
use App\Models\Vendor;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Hitung komisi BrightDor untuk sebuah subtotal.
     *
     * Prioritas: CommissionSetting aktif milik kategori vendor, lalu
     * commission_rate pada kategori, terakhir default 10%.
     */
    public static function commissionFor(Vendor $vendor, float $subtotal): float
    {
        $setting = CommissionSetting::query()
            ->where('vendor_category_id', $vendor->vendor_category_id)
            ->where('is_active', true)
            ->latest('id')
            ->first();

        $percent = $setting
            ? (float) $setting->rate_percent
            : ((float) ($vendor->category?->commission_rate ?? 10));

        $fixed = $setting ? (float) $setting->rate_fixed : 0.0;

        return round(($subtotal * $percent / 100) + $fixed, 2);
    }

    /**
     * Catat komisi ke booking lalu buat transaksi pembayaran (pending).
     */
    public static function createPaymentTransaction(Booking $booking): Transaction
    {
        $subtotal = round((float) $booking->subtotal, 2);
        $adminFee = round((float) $booking->admin_fee, 2);

        $booking->forceFill([
            'commission_amount' => self::commissionFor($booking->vendor, $subtotal),
            'total_amount' => round($subtotal + $adminFee, 2),
        ])->save();

        return $booking->transactions()->create([
            'user_id' => $booking->user_id,
            'type' => 'payment',
            'amount' => $booking->total_amount,
            'fee' => 0,
            'net_amount' => $booking->total_amount,
            'status' => 'pending',
        ]);
    }

    /**
     * Tandai pembayaran sukses dan konfirmasi booking (validasi semi-auto).
     */
    public static function markAsPaid(Transaction $transaction): void
    {
        if ($transaction->status === 'success') {
            return;
        }

        $transaction->forceFill([
            'status' => 'success',
            'paid_at' => now(),
            'payment_gateway' => $transaction->payment_gateway ?: 'manual',
            'meta' => array_merge($transaction->meta ?? [], ['validated_at' => now()->toISOString()]),
        ])->save();

        $booking = $transaction->payable;

        if ($booking instanceof Booking && $booking->status === 'pending') {
            $booking->forceFill([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ])->save();
        }
    }

    /**
     * Tandai pembayaran gagal (validasi ditolak admin / expired).
     */
    public static function markAsFailed(Transaction $transaction): void
    {
        $transaction->forceFill(['status' => 'failed'])->save();
    }

    public static function generateTransactionCode(): string
    {
        return 'TRX-' . strtoupper(Str::random(10));
    }
}