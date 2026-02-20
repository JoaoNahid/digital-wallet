<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    Use HasUlids;
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
