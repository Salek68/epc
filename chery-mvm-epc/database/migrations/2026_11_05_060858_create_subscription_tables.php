<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->enum('role', ['user', 'seller']);

            $table->unsignedInteger('duration_days');

            // مبلغ به تومان
            $table->unsignedBigInteger('amount');

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'cancelled',
                'failed'
            ])->default('pending');

            // مسیر تصویر فیش واریزی
            $table->string('document_path')->nullable();

            $table->text('admin_note')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'role', 'status']);
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('transaction_id')
                ->unique()
                ->constrained('subscription_transactions')
                ->restrictOnDelete();

            $table->enum('role', ['user', 'seller']);

            $table->unsignedInteger('duration_days');

     $table->dateTime('starts_at');
$table->dateTime('expires_at');

            $table->enum('status', [
                'active',
                'expired',
                'cancelled'
            ])->default('active');

            $table->timestamps();

            $table->index(['user_id', 'role', 'status', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_transactions');
    }
};
