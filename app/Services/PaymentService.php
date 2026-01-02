<?php

namespace App\Services;

use App\DTOs\PaymentDataDTO;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\Payment;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

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
            'provider' => 'fake',
        ]);
    }

    public function completePayment(Payment $payment, bool $success): Payment
    {
        if (in_array($payment->status, [
            PaymentStatus::PAID->value,
            PaymentStatus::FAILED->value,
        ], true)) {
            return $payment;
        }

        $payment->status = $success
            ? PaymentStatus::PAID->value
            : PaymentStatus::FAILED->value;

        $payment->gateway_response = json_encode(['success' => $success]);
        $payment->save();

        if ($success === true) {
            $order = $payment->order;
            if ($order && $order->status === OrderStatus::PENDING) {
                $order->updateStatus(OrderStatus::PAID);
                event(new OrderPaid($order));
            }
        }

        return $payment;
    }

    /**
     * @throws ApiErrorException
     */
    public function createStripePayment(Order $order): array
    {
        if ($order->status !== OrderStatus::PENDING) {
            throw new \LogicException('Нельзя создать оплату для заказа не в статусе pending.');
        }

        $payment = Payment::create([
            'order_id'            => $order->id,
            'amount'              => $order->total,
            'status'              => PaymentStatus::PENDING->value,
            'provider'            => 'stripe',
            'provider_payment_id' => null,
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'mode'                 => 'payment',
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'price_data' => [
                    'currency'     => 'usd', // или нужная тебе валюта
                    'product_data' => [
                        'name' => 'Order #' . $order->id,
                    ],
                    'unit_amount' => (int) ($order->total * 100), // в центах/копейках
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'payment_id' => $payment->id,
                'order_id'   => $order->id,
            ],
            'success_url' => route('payment.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('payment.stripe.cancel') . '?session_id={CHECKOUT_SESSION_ID}',
        ]);

        $payment->update([
            'provider_payment_id' => $session->id,
        ]);

        return [
            'payment'      => $payment,
            'redirect_url' => $session->url,
        ];
    }
}
