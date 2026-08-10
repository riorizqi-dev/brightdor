<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->comment('Couple / customer');
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->date('event_date')->nullable();
            $table->time('event_time')->nullable();
            $table->string('event_location')->nullable();
            $table->unsignedInteger('guest_count')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('admin_fee', 15, 2)->default(0);
            $table->decimal('commission_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', [
                'pending',
                'confirmed',
                'on_progress',
                'completed',
                'cancelled',
                'refund',
            ])->default('pending');
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'event_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
