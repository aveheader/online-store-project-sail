<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Support\Facades\Log;

class LogOrderCreated
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        // Подготовим данные к записи
        $data = [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'total' => $order->total,
            'status' => $order->status->value,
            'created_at' => $order->created_at->toDateTimeString(),
            'shipping_address' => $order->shipping_address,
            'items' => $order->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name ?? null,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            })->toArray(),
        ];

        // Запишем в отдельный лог-файл
        Log::channel('orders')->info('Order created', $data);
    }
}
