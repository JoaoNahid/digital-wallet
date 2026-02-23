<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    Use HasUlids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    // Relations
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function getFormattedBalanceAttribute(): string {
        return 'R$ ' . number_format($this->balance, 2, ',', '.');
    }

    public function getIsNegativeAttribute(): bool {
        return $this->balance < 0;
    }


    public function hasSufficientBalance(float $p_Amount): bool {
        return $this->balance >= $p_Amount;
    }

    public function credit(float $p_Amount): void {
        $this->increment('balance', $p_Amount);
    }

    public function debit(float $p_Amount): void {
        $this->decrement('balance', $p_Amount);
    }
}
