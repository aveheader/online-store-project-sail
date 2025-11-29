<?php

namespace App\Services;

use App\DTOs\PaymentDataDTO;
use App\Enums\PaymentStatus;
use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\Payment;

class PaymentService
{
    public function createPayment(Order $order): Payment
    {
        $data = new PaymentDataDTO(
            orderId: $order->id,
            amount: $order->total,
        );

        return Payment::create([
            'order_id' => $data->orderId,
            'amount' => $data->amount,
            'status' => $data->status,
        ]);
    }

    public function completePayment(Payment $payment, bool $success): Payment
    {
        $payment->status = $success ? 'paid' : 'failed';
        $payment->gateway_response = json_encode(['success' => $success]);
        $payment->save();

        if ($success) {
            $payment->order->updateStatus(PaymentStatus::PAID->value);
            event(new OrderPaid($payment));
        }

        return $payment;
    }
}
