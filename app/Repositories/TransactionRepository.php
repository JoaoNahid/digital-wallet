<?php

namespace App\Repositories;

use App\DTOs\Wallet\DepositDTO;
use App\DTOs\Wallet\TransferDTO;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function findById(string $p_Id): ?Transaction {
        return Transaction::find($p_Id);
    }

    public function createDeposit(Wallet $p_Wallet, DepositDTO $p_Dto): Transaction {
        return Transaction::create([
            'wallet_id' => $p_Wallet->id,
            'type' => TransactionType::Deposit,
            'amount' => $p_Dto->amount,
            'balance_before' => $p_Wallet->balance,
            'balance_after' => $p_Wallet->balance + $p_Dto->amount,
            'status' => TransactionStatus::Pending,
            'description' => $p_Dto->description,
        ]);
    }

    public function createTransferPair(
        Wallet $p_FromWallet,
        Wallet $p_ToWallet,
        TransferDTO $p_Dto
    ): array {
        $v_OutTransaction = Transaction::create([
            'wallet_id' => $p_FromWallet->id,
            'target_wallet_id' => $p_ToWallet->id,
            'type' => TransactionType::TransferOut,
            'amount' => $p_Dto->amount,
            'balance_before' => $p_FromWallet->balance,
            'balance_after' => $p_FromWallet->balance - $p_Dto->amount,
            'status' => TransactionStatus::Pending,
            'description' => $p_Dto->description,
        ]);

        $v_InTransaction = Transaction::create([
            'wallet_id' => $p_ToWallet->id,
            'target_wallet_id' => $p_FromWallet->id,
            'type' => TransactionType::TransferIn,
            'amount' => $p_Dto->amount,
            'balance_before' => $p_ToWallet->balance,
            'balance_after' => $p_ToWallet->balance + $p_Dto->amount,
            'status' => TransactionStatus::Pending,
            'reference_id' => $v_OutTransaction->id,
            'description' => $p_Dto->description,
        ]);

        return [$v_OutTransaction, $v_InTransaction];
    }

    public function createReversal(Transaction $p_Transaction, ?string $p_Reason): Transaction {
        $v_Wallet = $p_Transaction->wallet;
        $v_IsCredit = $p_Transaction->type->isCredit();
        $v_NewBalance = $v_IsCredit
            ? $v_Wallet->balance - $p_Transaction->amount
            : $v_Wallet->balance + $p_Transaction->amount;

        return Transaction::create([
            'wallet_id' => $v_Wallet->id,
            'target_wallet_id' => $p_Transaction->target_wallet_id,
            'type' => TransactionType::Reversal,
            'amount' => $p_Transaction->amount,
            'balance_before' => $v_Wallet->balance,
            'balance_after' => $v_NewBalance,
            'status' => TransactionStatus::Completed,
            'reference_id' => $p_Transaction->id,
            'description' => $p_Reason ?? 'Estorno de transação #' . $p_Transaction->id,
        ]);
    }

    public function updateStatus(Transaction $p_Transaction, TransactionStatus $p_Status): void {
        $p_Transaction->update(['status' => $p_Status]);
    }

    public function getPaginatedForWallet(
        string $p_WalletId,
        int $p_PerPage = 15
    ): LengthAwarePaginator {
        return Transaction::query()
            ->forWallet($p_WalletId)
            ->with('targetWallet.user:id,name,email')
            ->latest()
            ->paginate($p_PerPage);
    }

    public function markAsReversed(Transaction $p_Transaction): void {
        $p_Transaction->update([
            'status' => TransactionStatus::Reversed,
            'reversed_at' => now(),
        ]);
    }

    public function existsTransferInPair(Transaction $p_Transaction): ?Transaction {
        return Transaction::where('reference_id', $p_Transaction->id)
            ->transferIn()
            ->first();
    }

    public function existsTransferOutPair(Transaction $p_Transaction): ?Transaction {
        return Transaction::where('id', $p_Transaction->reference_id)
            ->transferOut()
            ->first();
    }
}