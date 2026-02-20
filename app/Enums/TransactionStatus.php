<?php

namespace App\Enums;

enum TransactionStatus: string {
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';
    case Reversed = 'reversed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Completed => 'Completo',
            self::Failed => 'Falhou',
            self::Reversed => 'Estornado',
        };
    }
}