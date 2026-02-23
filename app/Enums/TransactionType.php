<?php

namespace App\Enums;

enum TransactionType: string {
    case Deposit = 'deposit';
    case TransferOut = 'transfer_out';
    case TransferIn = 'transfer_in';
    case Reversal = 'reversal';

    public function label(): string {
        return match ($this) {
            self::Deposit => 'Depósito',
            self::TransferOut => 'Transferência Enviada',
            self::TransferIn => 'Transferência Recebida',
            self::Reversal => 'Estorno',
        };
    }

    public function isCredit(): bool {
        return in_array($this, [self::Deposit, self::TransferIn]);
    }

    public function isDebit(): bool {
        return in_array($this, [self::TransferOut]);
    }
}