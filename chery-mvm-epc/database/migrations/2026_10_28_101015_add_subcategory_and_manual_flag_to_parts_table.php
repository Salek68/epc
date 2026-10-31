<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('parts', function (Blueprint $table) {
        $table->string('subcategory')->nullable()->index();
        $table->boolean('category_manual')->default(false);
    });
}

public function down(): void
{
    Schema::table('parts', function (Blueprint $table) {
        $table->dropColumn(['subcategory', 'category_manual']);
    });
}
};
