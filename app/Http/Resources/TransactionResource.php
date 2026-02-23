<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $p_Request): array {
        return [
            'id' => $this->id,
            'wallet_id' => $this->wallet_id,
            'target_wallet_id' => $this->target_wallet_id,
            'type' => $this->type->value,
            'type_label' => $this->type->label(),
            'amount' => (float) $this->amount,
            'formatted_amount' => $this->formatted_amount,
            'balance_before' => (float) $this->balance_before,
            'balance_after' => (float) $this->balance_after,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'status_badge_variant' => $this->status->badgeVariant(),
            'description' => $this->description,
            'can_be_reversed' => $this->can_be_reversed,
            'reversed_at' => $this->reversed_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'target_user' => $this->when(
                $this->targetWallet,
                fn () => [
                    'id' => $this->targetWallet->user->id,
                    'name' => $this->targetWallet->user->name,
                    'email' => $this->targetWallet->user->email,
                ],
                null
            ),
        ];
    }
}