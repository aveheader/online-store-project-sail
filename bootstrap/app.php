<?php

use App\Http\Middleware\EnsureOrderBelongsToUser;
use App\Http\Middleware\EnsurePaymentBelongsToUser;
use App\Http\Middleware\EnsurePaymentIsPending;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'order.owner' => EnsureOrderBelongsToUser::class,
            'payment.owner' => EnsurePaymentBelongsToUser::class,
            'payment.pending' => EnsurePaymentIsPending::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
