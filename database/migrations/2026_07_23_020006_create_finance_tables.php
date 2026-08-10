<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->nullableMorphs('payable');
            $table->enum('type', ['payment', 'refund', 'commission', 'payout'])->default('payment');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('fee', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->enum('payment_gateway', ['midtrans', 'xendit', 'manual', 'other'])->nullable();
            $table->string('gateway_reference')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'expired', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['status', 'type']);
        });

        Schema::create('commission_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_category_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('rate_percent', 5, 2)->default(10.00);
            $table->decimal('rate_fixed', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_code')->unique();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->enum('status', ['pending', 'processing', 'paid', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('commission_settings');
        Schema::dropIfExists('transactions');
    }
};
