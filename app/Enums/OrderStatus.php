<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Заказ создан, ожидает оплаты',
            self::PAID => 'Заказ успешно оплачен',
            self::CANCELED => 'Заказ отменён',
        };
    }
}
