@extends('layouts.app')
@section('title', 'Оплата заказа')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-4">Фейковый шлюз оплаты</h1>
<p class="text-lg text-gray-600 mb-2">Платеж для заказа #{{ $payment->order_id }}</p>
<p class="text-lg text-gray-600 mb-6">Сумма: {{ number_format($payment->amount, 2) }} ₽</p>

<form action="{{ route('fake-gateway.process', $payment) }}" method="POST" class="space-y-4">
    @csrf
    <button type="submit" name="success" value="1"
            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 focus:outline-none">
        Оплатить
    </button>
    <button type="submit" name="success" value="0"
            class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 focus:outline-none">
        Отменить
    </button>
</form>
@endsection
