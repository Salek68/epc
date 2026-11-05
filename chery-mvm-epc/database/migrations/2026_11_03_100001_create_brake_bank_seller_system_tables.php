
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. جدول اعلام آمادگی فروشنده برای فروش لنت
        |--------------------------------------------------------------------------
        */

        Schema::create('brake_bank_seller_offers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('seller_store_id');
            $table->unsignedBigInteger('brake_pad_id');

            $table->string('product_url', 2048);
            $table->string('image_url', 2048)->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'suspended',
            ])->default('pending');

            $table->text('admin_note')->nullable();

            $table->timestamps();

            $table->unique(
                ['seller_store_id', 'brake_pad_id'],
                'brake_bank_seller_pad_unique'
            );

            $table->index('status');

            $table->foreign('seller_store_id')
                ->references('id')
                ->on('seller_stores')
                ->cascadeOnDelete();

            $table->foreign('brake_pad_id')
                ->references('id')
                ->on('brake_bank_pads')
                ->cascadeOnDelete();
        });


        /*
        |--------------------------------------------------------------------------
        | 2. جدول برندهای لنت
        |--------------------------------------------------------------------------
        */

        Schema::create('brake_bank_brands', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->string('slug')->unique();

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | 3. جدول ارتباط اعلام آمادگی با برندها
        |--------------------------------------------------------------------------
        */

        Schema::create('brake_bank_seller_offer_brand', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('seller_offer_id');
            $table->unsignedBigInteger('brand_id');

            $table->timestamps();

            $table->unique(
                ['seller_offer_id', 'brand_id'],
                'brake_bank_offer_brand_unique'
            );

            $table->foreign('seller_offer_id')
                ->references('id')
                ->on('brake_bank_seller_offers')
                ->cascadeOnDelete();

            $table->foreign('brand_id')
                ->references('id')
                ->on('brake_bank_brands')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brake_bank_seller_offer_brand');
        Schema::dropIfExists('brake_bank_brands');
        Schema::dropIfExists('brake_bank_seller_offers');
    }
};
