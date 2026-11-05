<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brake_bank_pad_vehicle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brake_pad_id')->constrained('brake_bank_pads')->cascadeOnDelete();
            $table->foreignId('brake_pad_vehicle_id')->constrained('brake_bank_vehicles')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['brake_pad_id', 'brake_pad_vehicle_id'], 'brake_bank_pad_vehicle_unique');
            $table->index('brake_pad_vehicle_id', 'brake_bank_vehicle_lookup_idx');
        });
    }
    public function down(): void { Schema::dropIfExists('brake_bank_pad_vehicle'); }
};
