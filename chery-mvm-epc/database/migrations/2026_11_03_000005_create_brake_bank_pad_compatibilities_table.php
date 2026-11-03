<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brake_bank_pad_compatibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brake_pad_id')->constrained('brake_bank_pads')->cascadeOnDelete();
            $table->string('vehicle_name');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['brake_pad_id', 'vehicle_name'], 'brake_bank_compatibility_unique');
        });
    }
    public function down(): void { Schema::dropIfExists('brake_bank_pad_compatibilities'); }
};
