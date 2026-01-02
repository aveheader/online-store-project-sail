<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Payment;

class EnsurePaymentBelongsToUser
{
    public function handle(Request $request, Closure $next)
    {
        $payment = $request->route('payment');

        if ($payment->order->user_id !== auth()->id()) {
            abort(403, 'Вы не можете работать с платежом чужого заказа.');
        }

        return $next($request);
    }
}
