<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;

class PaymentService
{
    public function createPayment(Order $order): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total,
            'status' => 'pending',
        ]);
    }

    public function completePayment(Payment $payment, bool $success): Payment
    {
        $payment->update([
            'status' => $success ? 'paid' : 'failed',
        ]);

        if ($success === true) {
            $payment->order->updateStatus(PaymentStatus::PAID->value);
        }

        return $payment;
    }
}
