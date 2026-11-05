<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('seller_store_id')
                ->constrained('seller_stores')
                ->cascadeOnDelete();

            $table->foreignId('part_id')
                ->constrained('parts')
                ->cascadeOnDelete();

            $table->string('product_url', 2048);
            $table->string('image_url', 2048)->nullable();

            $table->unsignedBigInteger('click_count')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // هر فروشگاه برای هر قطعه فقط یک ثبت داشته باشد
            $table->unique(
                ['seller_store_id', 'part_id'],
                'seller_product_unique'
            );

            $table->index(['part_id', 'is_active']);
            $table->index(['seller_store_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_products');
    }
};
