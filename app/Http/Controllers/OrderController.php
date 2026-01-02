<?php

namespace App\Http\Controllers;

use App\DTOs\OrderDataDTO;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Throwable;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {
    }

    public function index()
    {
        $user = auth()->user();

        $orders = $user->orders()
            ->with('items.product')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function checkout()
    {
        $user = auth()->user()->load('profile');
        $cart = $user->cart->with('items.product')->first();

        if ($cart === null || $cart->items->isEmpty()) {
            return redirect()->route('products.index')
                ->with('error', 'Корзина пуста');
        }

        $profile = $user->profile ?? (object)[
            'phone' => '',
            'city' => '',
            'street' => '',
        ];

        return view('checkout', [
            'user' => $user,
            'profile' => $profile,
            'cart' => $cart,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $dto = OrderDataDTO::fromRequest($request);

        $order = $this->orderService->createOrder($user, $dto);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Заказ успешно оформлен!');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status === OrderStatus::PAID) {
            return back()->with('error', 'Оплаченные заказы нельзя отменить.');
        }

        if ($order->status === OrderStatus::CANCELED) {
            return back()->with('info', 'Заказ уже отменён.');
        }

        $order->update([
            'status' => OrderStatus::CANCELED->value
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Заказ отменён.');
    }
}
