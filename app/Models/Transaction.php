<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

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
    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }

    public function targetWallet() {
        return $this->belongsTo(Wallet::class, 'target_wallet_id');
    }

    public function reference() {
        return $this->belongsTo(Transaction::class, 'reference_id');
    }
}
