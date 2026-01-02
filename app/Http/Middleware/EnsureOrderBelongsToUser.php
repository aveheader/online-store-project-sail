<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureOrderBelongsToUser
{
    public function handle(Request $request, Closure $next)
    {
        $order = $request->route('order');

        if ($order->user_id !== auth()->id()) {
            abort(403, 'Вы не можете оплачивать чужой заказ.');
        }

        return $next($request);
    }
}
