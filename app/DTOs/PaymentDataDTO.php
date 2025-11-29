<?php

namespace App\DTOs;

use App\Enums\PaymentStatus;

class PaymentDataDTO
{
    public function __construct(
        public int $orderId,
        public float $amount,
        public string $status = PaymentStatus::PENDING->value,
        public array $gatewayResponse = [],
    ) {
    }
}
