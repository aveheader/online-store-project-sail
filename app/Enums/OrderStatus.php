<?php

namespace App\Enums;

enum OrderStatus: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case SHIPPED = 'shipped';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Новый',
            self::PAID => 'Оплачен',
            self::SHIPPED => 'Отправлен',
            self::CANCELLED => 'Отменен',
        };
    }
}
