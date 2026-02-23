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

    public function updateStatus(Transaction $transaction, TransactionStatus $status): void {
        $transaction->update(['status' => $status]);
    }
}