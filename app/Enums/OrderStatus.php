<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case SHIPPED = 'shipped';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Ожидает оплаты',
            self::PAID => 'Оплачен',
            self::SHIPPED => 'Отправлен',
            self::CANCELLED => 'Отменен',
        };
    }
}
