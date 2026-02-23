<?php

namespace App\DTOs\Wallet;

readonly class TransferDTO
{
    public function __construct(
        public string $fromWalletId,
        public string $toWalletId,
        public float $amount,
        public ?string $description = null,
    ) {}

    public static function fromRequest(array $p_Data, string $p_FromWalletId): self {
        return new self(
            fromWalletId: $p_FromWalletId,
            toWalletId: $p_Data['to_wallet_id'],
            amount: (float) $p_Data['amount'],
            description: $p_Data['description'] ?? null,
        );
    }
}