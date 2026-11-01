<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_verification_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('seller_store_id')
                ->constrained('seller_stores')
                ->cascadeOnDelete();

            $table->string('document_type');
            $table->string('file_path');

            $table->timestamps();

            $table->index('seller_store_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_verification_documents');
    }
};
