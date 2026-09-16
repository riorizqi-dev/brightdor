<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function create(Booking $booking): View
    {
        $this->authorizeAccess($booking);

        $transaction = $this->paymentTransaction($booking);

        if (! $transaction) {
            abort(404, 'Transaksi pembayaran untuk booking ini tidak ditemukan.');
        }

        // Auto-expire: tandai transaksi pending yang sudah lewat batas 24 jam.
        $transaction->expireIfOverdue();

        return view('frontend.bookings.pay', [
            'booking' => $booking->load(['vendor.category', 'service']),
            'transaction' => $transaction->fresh(),
        ]);
    }

    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorizeAccess($booking);

        $transaction = $this->paymentTransaction($booking);

        if (! $transaction) {
            abort(404, 'Transaksi pembayaran untuk booking ini tidak ditemukan.');
        }

        // Auto-expire sebelum validasi status.
        $transaction->expireIfOverdue();

        if ($transaction->status === 'expired') {
            return back()->withErrors([
                'booking' => 'Batas waktu pembayaran (24 jam) sudah lewat. Silakan hubungi admin untuk membuka pembayaran baru.',
            ]);
        }

        if ($transaction->status !== 'pending') {
            return back()->withErrors([
                'booking' => 'Pembayaran untuk booking ini sudah divalidasi atau berstatus tidak valid.',
            ]);
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:bank_transfer,ewallet,virtual_account,qris'],
            'payment_reference' => ['required', 'string', 'max:100'],
            'payment_proof' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $proofPath = $transaction->payment_proof;

        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payments', 'public');
        }

        $meta = $transaction->meta ?? [];

        $transaction->forceFill([
            'payment_method' => $validated['payment_method'],
            'payment_gateway' => 'manual',
            'gateway_reference' => $validated['payment_reference'],
            'payment_proof' => $proofPath,
            'meta' => array_merge($meta, [
                'submitted_at' => now()->toISOString(),
                'submit_count' => (int) ($meta['submit_count'] ?? 0) + 1,
            ]),
        ])->save();

        return redirect()
            ->route('my-bookings.index')
            ->with('success', 'Bukti pembayaran terkirim. Menunggu validasi admin (semi-auto).');
    }

    private function paymentTransaction(Booking $booking): ?Transaction
    {
        return $booking->transactions()
            ->where('type', 'payment')
            ->latest('id')
            ->first();
    }

    private function authorizeAccess(Booking $booking): void
    {
        if ((int) $booking->user_id !== (int) Auth::id()) {
            abort(403);
        }

        // Booking masih bisa dibayar selama berstatus pending, atau sudah
        // dikonfirmasi vendor tetapi pembayarannya belum lunas.
        if (! in_array($booking->status, ['pending', 'confirmed'], true)) {
            abort(403, 'Booking ini tidak dapat dibayar lagi.');
        }
    }
}