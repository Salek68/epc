<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
public function up(): void
{
    Schema::create('parts', function (Blueprint $table) {
        $table->id();
        $table->string('technical_code')->index();
        $table->string('logistics_code')->nullable()->index();
        $table->string('name_fa')->index();
        $table->decimal('price', 15, 2)->nullable();
        $table->decimal('price_tax', 15, 2)->nullable();
        $table->text('car_model_raw')->nullable();
        $table->text('replace_raw')->nullable();
        $table->string('category')->nullable()->index();
        $table->timestamps();
    });

    Schema::create('vehicle_models', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->string('normalized_name')->index();
        $table->timestamps();
    });

    Schema::create('part_vehicle_model', function (Blueprint $table) {
        $table->foreignId('part_id')
            ->constrained('parts')->cascadeOnDelete();
        $table->foreignId('vehicle_model_id')
            ->constrained('vehicle_models')->cascadeOnDelete();

        $table->primary(['part_id', 'vehicle_model_id']);
    });

    Schema::create('part_replacements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('part_id')
            ->constrained('parts')->cascadeOnDelete();
        $table->string('replacement_code')->index();
        $table->timestamps();
    });

    Schema::create('inventory', function (Blueprint $table) {
        $table->id();
        $table->foreignId('part_id')
            ->unique()->constrained('parts')->cascadeOnDelete();
        $table->unsignedInteger('quantity')->default(0);
        $table->decimal('purchase_price', 15, 2)->nullable();
        $table->decimal('sale_price', 15, 2)->nullable();
        $table->string('warehouse_location')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('inventory');
    Schema::dropIfExists('part_replacements');
    Schema::dropIfExists('part_vehicle_model');
    Schema::dropIfExists('vehicle_models');
    Schema::dropIfExists('parts');
}
};
