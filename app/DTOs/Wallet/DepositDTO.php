<?php

namespace App\DTOs\Wallet;

readonly class DepositDTO
{
    public function __construct(
        public string $walletId,
        public float $amount,
        public ?string $description = null,
    ) {}

    public static function fromRequest(array $p_Data, string $p_WalletId): self {
        return new self(
            walletId: $p_WalletId,
            amount: (float) $p_Data['amount'],
            description: $p_Data['description'] ?? null,
        );
    }
}