<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brake_bank_pads', function (Blueprint $table) {
            $table->id();
            $table->string('technical_code')->unique();
            $table->string('name');
            $table->string('position', 20)->nullable()->index(); // front | rear
            $table->string('pad_type', 50)->nullable();
            $table->text('other_compatibility_raw')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->unsignedInteger('carton_quantity')->nullable();
            $table->unsignedInteger('source_row')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('brake_bank_pads'); }
};
