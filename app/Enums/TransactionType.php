<?php

namespace App\Enums;

enum TransactionType: string {
    case Deposit = 'deposit';
    case TransferOut = 'transfer_out';
    case TransferIn = 'transfer_in';
    case Reversal = 'reversal';
}