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
            $table->foreignUlid('wallet_id')->references('id')->on('wallets')->cascadeOnDelete();
            $table->foreignUlid('target_wallet_id')->nullable()->constrained('wallets')->nullOnDelete();

            $table->string('type', 20);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->string('status', 20)->default(TransactionStatus::Pending->value);
            $table->ulid('reference_id')->nullable();
            $table->string('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('reversed_at')->nullable();
            $table->timestamps();

            // indexes
            $table->index(['wallet_id', 'created_at']);
            $table->index(['wallet_id', 'type']);
            $table->index(['wallet_id', 'status']);
            $table->index('reference_id');
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
