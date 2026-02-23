<?php

namespace App\Repositories\Contracts;

use App\DTOs\Wallet\DepositDTO;
use App\DTOs\Wallet\TransferDTO;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface {
    public function findById(string $id): ?Transaction;

    public function createDeposit(Wallet $wallet, DepositDTO $dto): Transaction;

    public function createTransferPair(
        Wallet $fromWallet,
        Wallet $toWallet,
        TransferDTO $dto
    ): array;

    public function updateStatus(Transaction $transaction, TransactionStatus $status): void;
}