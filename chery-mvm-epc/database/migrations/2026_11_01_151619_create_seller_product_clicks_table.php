<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_product_clicks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('seller_product_id')
                ->constrained('seller_products')
                ->cascadeOnDelete();

            $table->timestamp('clicked_at')->useCurrent();

            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent', 500)->nullable();

            $table->index(['seller_product_id', 'clicked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_product_clicks');
    }
};
