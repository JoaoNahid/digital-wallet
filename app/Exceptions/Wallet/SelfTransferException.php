<?php

namespace App\Exceptions\Wallet;

use Exception;
use Illuminate\Http\{JsonResponse, Request};


class SelfTransferException extends Exception
{
    public function __construct() {
        parent::__construct('Não é possível transferir para si mesmo.');
    }

    public function render(Request $request): JsonResponse {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => [
                'recipient_email' => [$this->getMessage()],
            ],
        ], 422);
    }
}
