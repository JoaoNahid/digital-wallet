<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use App\Models\Wallet;

interface WalletRepositoryInterface {
    public function createForUser(User $user): Wallet;

    public function findByUserId(int $userId): ?Wallet;
    
    public function findById(string $id): ?Wallet;

    public function findByTransactionId(string $id): ?Wallet;
    
    public function findByIdWithLock(string $id): ?Wallet;

    public function findByUserEmail(string $email): ?Wallet;

    public function updateBalance(Wallet $wallet, float $amount, string $operation): void;
}