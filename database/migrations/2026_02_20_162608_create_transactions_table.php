<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\{TransactionStatus, TransactionType};
use App\Models\Transaction;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreignUlid('target_wallet_id')->references('id')->on('wallets')->onDelete('cascade');

            $table->enum('type', array_column(TransactionType::cases(), 'value'));
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->enum('status', array_column(TransactionStatus::cases(), 'value'))->default('pending');
            $table->foreignUlid('reference_id')->nullable();
            $table->string('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('reversed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
