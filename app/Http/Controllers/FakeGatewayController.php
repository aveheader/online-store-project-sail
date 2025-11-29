<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FakeGatewayController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
    ) {
    }

    /**
     * Отобразить интерфейс фейкового шлюза.
     *
     * @param Payment $payment
     * @return View
     */
    public function show(Payment $payment): View
    {
        return view('fake-gateway', ['payment' => $payment]);
    }

    /**
     * Обработать решение пользователя (оплатить или отменить).
     *
     * @param Request $request
     * @param Payment $payment
     * @return RedirectResponse
     */
    public function process(Request $request, Payment $payment): RedirectResponse
    {
        // Проверяем успешность оплаты
        $success = $request->input('success') === '1';

        // Обновляем логики через сервис оплаты
        $this->paymentService->completePayment($payment, $success);

        // Перенаправляем пользователя обратно в заказ
        return redirect()->route('orders.show', ['order' => $payment->order_id])
            ->with('status', $success ? 'Оплата прошла успешно!' : 'Оплата завершена неудачно.');
    }
}
