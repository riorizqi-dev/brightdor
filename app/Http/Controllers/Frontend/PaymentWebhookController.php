<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    /**
     * Simulasi callback payment gateway: menandai transaksi lunas dan
     * otomatis mengkonfirmasi booking (validasi OTOMATIS).
     */
    public function markPaid(Request $request): JsonResponse
    {
        $expectedKey = (string) config('services.brightdor.webhook_key', 'brightdor-demo-key');
        $providedKey = (string) $request->header('X-BrightDor-Key', '');

        if (! hash_equals($expectedKey, $providedKey)) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $validated = $request->validate([
            'transaction_code' => ['required', 'string'],
        ]);

        $transaction = Transaction::query()
            ->where('transaction_code', $validated['transaction_code'])
            ->first();

        if (! $transaction) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }

        if ($transaction->status === 'success') {
            return response()->json(['message' => 'Already paid.', 'status' => 'success']);
        }

        if (! in_array($transaction->status, ['pending', 'failed', 'expired'], true)) {
            return response()->json(['message' => 'Transaction is not payable.'], 422);
        }

        PaymentService::markAsPaid($transaction);

        return response()->json([
            'message' => 'Payment marked as paid.',
            'status' => 'success',
            'booking_code' => $transaction->payable?->booking_code,
        ]);
    }
}