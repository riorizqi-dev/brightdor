<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('discount_price', 15, 2)->nullable();
            $table->string('price_unit')->nullable()->comment('per pax, per event, per day, etc');
            $table->unsignedInteger('capacity')->nullable();
            $table->string('duration')->nullable();
            $table->string('location')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['draft', 'published', 'moderated', 'rejected'])->default('draft');
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('bookings_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['vendor_id', 'slug']);
            $table->index(['status', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
