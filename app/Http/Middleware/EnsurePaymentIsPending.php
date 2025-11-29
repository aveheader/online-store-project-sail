<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Payment;

class EnsurePaymentIsPending
{
    public function handle($request, Closure $next)
    {
        $payment = $request->route('payment');

        if ($payment->status !== 'pending') {
            abort(403, 'Этот платёж уже завершён.');
        }

        return $next($request);
    }
}
