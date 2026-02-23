<?php

namespace App\Exceptions\Wallet;

use Exception;
use Illuminate\Http\{JsonResponse, Request};

class TransactionNotReversibleException extends Exception
{
    public function __construct(string $reason = 'Esta transação não pode ser revertida.') {
        parent::__construct($reason);
    }

    public function render(Request $request): JsonResponse {
        return response()->json([
            'message' => $this->getMessage(),
        ], 422);
    }
}
