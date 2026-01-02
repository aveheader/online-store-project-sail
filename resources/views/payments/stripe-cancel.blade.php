@extends('layouts.app')

@section('title', 'Оплата Stripe — отмена')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Оплата через Stripe отменена</h1>

    <p>Session ID: {{ $sessionId }}</p>

    <a href="{{ route('products.index') }}"
       class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
        Вернуться к покупкам
    </a>
@endsection
