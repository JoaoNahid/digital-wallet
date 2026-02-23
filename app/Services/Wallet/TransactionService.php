<?php

namespace App\Services\Wallet;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Exceptions\Wallet\TransactionNotReversibleException;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
    ) {}

    public function findTransaction(string $id): ?Transaction {
        return $this->transactionRepository->findById($id);
    }

    public function completeTransaction(Transaction $p_Transaction): void {
        $this->transactionRepository->updateStatus($p_Transaction, TransactionStatus::Completed);
    }
}