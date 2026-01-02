<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {}

    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;
            default:
                break;
        }

        return response('OK', 200);
    }

    protected function handleCheckoutSessionCompleted(object $session): void
    {
        $paymentId = $session->metadata->payment_id ?? null;

        if (!$paymentId) {
            return;
        }

        /** @var Payment|null $payment */
        $payment = Payment::find($paymentId);

        if (!$payment) {
            return;
        }

        if (in_array($payment->status, [
            PaymentStatus::PAID->value,
            PaymentStatus::FAILED->value,
        ], true)) {
            return;
        }

        $this->paymentService->completePayment($payment, true);
    }
}
