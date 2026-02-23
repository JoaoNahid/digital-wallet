<?php

namespace App\DTOs\Wallet;

readonly class ReverseDTO
{
    public function __construct(
        public string $transactionId,
        public ?string $reason = null,
    ) {}

    public static function fromRequest(array $p_Data, string $p_TransactionId): self {
        return new self(
            transactionId: $p_TransactionId,
            reason: $p_Data['reason'] ?? null,
        );
    }
}