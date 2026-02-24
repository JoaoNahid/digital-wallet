<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Policies\TransactionPolicy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne};

#[UsePolicy(TransactionPolicy::class)]
class Transaction extends Model
{
    Use HasUlids;

    protected $fillable = [
        'wallet_id',
        'target_wallet_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'status',
        'reference_id',
        'description',
        'metadata',
        'reversed_at',
    ];

    protected $casts = [
        'type' => TransactionType::class,
        'status' => TransactionStatus::class,
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
        'reversed_at' => 'datetime',
    ];

    // Relations
    public function wallet(): BelongsTo {
        return $this->belongsTo(Wallet::class);
    }

    public function targetWallet(): BelongsTo {
        return $this->belongsTo(Wallet::class, 'target_wallet_id');
    }

    public function originalTransaction(): BelongsTo {
        return $this->belongsTo(Transaction::class, 'reference_id');
    }

    public function reversalTransaction(): HasOne {
        return $this->hasOne(Transaction::class, 'reference_id');
    }

    // Accessors

    public function getFormattedAmountAttribute(): string {
        $v_Prefix = $this->type->isCredit() ? '+' : '-';
        return $v_Prefix . ' R$ ' . number_format($this->amount, 2, ',', '.');
    }

    public function getCanBeReversedAttribute(): bool {
        return $this->status === TransactionStatus::Completed
            && $this->reversed_at === null
            && $this->type !== TransactionType::Reversal
            && $this->created_at->gt(Carbon::now()->subHours(2));
    }

    public function scopeForWallet(Builder $p_Query, string $p_WalletId): Builder {
        return $p_Query->where('wallet_id', $p_WalletId);
    }

    public function scopeTransferIn(Builder $p_Query): Builder {
        return $p_Query->where('type', TransactionType::TransferIn);
    }
}
