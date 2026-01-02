<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Попытка оплаты создана, но не завершена',
            self::PAID => 'Оплата завершена успешно',
            self::FAILED => 'Неуспешная оплата',
        };
    }
}
