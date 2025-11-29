<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use Illuminate\Support\Facades\Log;

class SendOrderPaidNotification
{
    public function handle(OrderPaid $event)
    {
        $orderPayment = $event->payment;

        $data = [
            'order_id' => $orderPayment->order_id,
            'amount' => $orderPayment->amount,
            'status' => $orderPayment->status,
            'gateway_response' => $orderPayment->gateway_response,
        ];

        Log::channel('orders')->info('Order paid', $data);
    }
}
