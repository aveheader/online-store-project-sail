<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use Illuminate\Support\Facades\Log;

class SendOrderPaidNotification
{
    public function handle(OrderPaid $event): void
    {
        $order = $event->order;

        $payment = $order->payments()->latest('id')->first();

        $data = [
            'order_id' => $order->id,
            'order_status' => $order->status->value,
        ];

        if ($payment) {
            $data = array_merge($data, [
                'payment_id'       => $payment->id,
                'amount'           => $payment->amount,
                'status'           => $payment->status,
                'gateway_response' => $payment->gateway_response,
                'provider'         => $payment->provider,
            ]);
        }

        Log::channel('orders')->info('Order paid', $data);
    }
}
