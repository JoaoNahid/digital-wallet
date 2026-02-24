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

    public function validateCanReverse(Transaction $p_Transaction): void {
        if ($p_Transaction->status !== TransactionStatus::Completed) {
            throw new TransactionNotReversibleException(
                'Apenas transações concluídas podem ser revertidas.'
            );
        }

        if ($p_Transaction->reversed_at !== null) {
            throw new TransactionNotReversibleException(
                'Esta transação já foi revertida.'
            );
        }

        if ($p_Transaction->type === TransactionType::Reversal) {
            throw new TransactionNotReversibleException(
                'Não é possível reverter um estorno.'
            );
        }
    }

    public function getPaginatedTransactions(
        string $p_WalletId,
        int $p_PerPage = 5
    ): LengthAwarePaginator {
        return $this->transactionRepository->getPaginatedForWallet(
            $p_WalletId,
            $p_PerPage
        );
    }

    public function completeTransaction(Transaction $p_Transaction): void {
        $this->transactionRepository->updateStatus($p_Transaction, TransactionStatus::Completed);
    }

    public function markAsReversed(Transaction $p_Transaction): void {
        $this->transactionRepository->markAsReversed($p_Transaction);
    }

    public function getPairIfExists(Transaction $p_Transaction): Transaction|null {
        if ($p_Transaction->type === TransactionType::TransferOut) {
            return $this->transactionRepository->existsTransferInPair($p_Transaction);
        }

        if ($p_Transaction->type === TransactionType::TransferIn) {
            return $this->transactionRepository->existsTransferOutPair($p_Transaction);
        }

        return null;
        
    }
}