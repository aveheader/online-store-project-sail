<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
    ) {
    }

    public function start(Order $order): RedirectResponse
    {
        if ($order->status === OrderStatus::PAID) {
            return redirect()->route('orders.show', $order);
        }

        if ($order->payments()->where('status', PaymentStatus::PENDING)->exists()) {
            $payment = $order->payments()->where('status', PaymentStatus::PENDING)->first();
            return redirect()->route('fake-gateway', ['payment' => $payment->id]);
        }

        $payment = $this->paymentService->createPayment($order);

        return redirect()->route('fake-gateway', ['payment' => $payment->id]);
    }

    public function startStripe(Order $order): RedirectResponse
    {
        if ($order->status === OrderStatus::PAID) {
            return redirect()->route('orders.show', $order);
        }

        $data = $this->paymentService->createStripePayment($order);

        return redirect()->away($data['redirect_url']);
    }
}
