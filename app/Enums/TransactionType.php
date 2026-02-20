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
}