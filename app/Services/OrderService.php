<?php

namespace App\Services;

use App\DTOs\OrderDataDTO;
use App\Enums\OrderStatus;
use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderService
{
    /**
     * @throws Throwable
     */
    public function createOrder(User $user, OrderDataDTO $dto): Order
    {
        $cart = $user->cart()->with('items.product')->firstOrFail();

        if ($cart->items->isEmpty()) {
            throw new \RuntimeException('Корзина пуста.');
        }

        return DB::transaction(function () use ($user, $cart, $dto) {
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $cart->items->sum(fn($i) => $i->product->price * $i->quantity),
                'status' => OrderStatus::PENDING->value,
                'shipping_address' => [
                    'name' => $dto->name,
                    'phone' => $dto->phone,
                    'delivery' => $dto->delivery,
                    'city' => $dto->city,
                    'street' => $dto->street,
                ],
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            $cart->items()->delete();

            if ($dto->save_profile) {
                $user->profile()->updateOrCreate([], [
                    'phone' => $dto->phone,
                    'city' => $dto->city,
                    'street' => $dto->street,
                ]);
            }

            event(new OrderCreated($order));

            return $order;
        });
    }
}
