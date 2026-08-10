<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitation_template_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('invitation_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_template_category_id')
                ->nullable()
                ->constrained('invitation_template_categories')
                ->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->string('preview_image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('demo_url')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sales_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('invitation_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invitation_template_id')->constrained()->cascadeOnDelete();
            $table->string('bride_name')->nullable();
            $table->string('groom_name')->nullable();
            $table->date('wedding_date')->nullable();
            $table->string('wedding_venue')->nullable();
            $table->string('subdomain')->nullable()->unique();
            $table->string('custom_domain')->nullable()->unique();
            $table->decimal('price', 15, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'active', 'expired', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invitation_template_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('subdomain')->nullable()->unique();
            $table->string('custom_domain')->nullable()->unique();
            $table->json('content')->nullable();
            $table->json('theme_settings')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('rsvp_yes')->default(0);
            $table->unsignedInteger('rsvp_no')->default(0);
            $table->unsignedInteger('rsvp_maybe')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('invitation_rsvps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->string('guest_name');
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->enum('attendance', ['yes', 'no', 'maybe'])->default('yes');
            $table->unsignedInteger('guest_count')->default(1);
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitation_rsvps');
        Schema::dropIfExists('invitations');
        Schema::dropIfExists('invitation_orders');
        Schema::dropIfExists('invitation_templates');
        Schema::dropIfExists('invitation_template_categories');
    }
};
