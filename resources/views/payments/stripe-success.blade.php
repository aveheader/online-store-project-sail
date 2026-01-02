@extends('layouts.app')

@section('title', 'Оплата Stripe — успех')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Оплата через Stripe успешно завершена (UI)</h1>

    <p>Session ID: {{ $sessionId }}</p>

    <p class="mt-4 text-gray-600">
        Важно: реальный статус платежа мы будем подтверждать через webhook от Stripe,
        а не только по этой странице.
    </p>

    <a href="{{ route('orders.show', request()->user()->orders()->latest()->first() ?? '#') }}"
       class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
        Перейти к заказу
    </a>
@endsection
