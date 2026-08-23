<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('vendor_subscription_status')->default('inactive')->after('status');
            $table->string('vendor_subscription_plan')->nullable()->after('vendor_subscription_status');
            $table->timestamp('vendor_subscription_expires_at')->nullable()->after('vendor_subscription_plan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'vendor_subscription_status',
                'vendor_subscription_plan',
                'vendor_subscription_expires_at',
            ]);
        });
    }
};
