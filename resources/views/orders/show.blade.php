@extends('layouts.app')
@section('title', 'Заказ №' . $order->id)

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Заказ №{{ $order->id }}</h1>

    <div class="bg-white shadow rounded-xl p-8 space-y-6">
        <div>
            <h2 class="text-lg font-semibold mb-2">Статус заказа</h2>
            <span class="px-3 py-1 rounded-full text-sm font-medium
                @if($order->status->value === 'new') bg-blue-100 text-blue-700
                @elseif($order->status->value === 'processing') bg-yellow-100 text-yellow-700
                @else bg-green-100 text-green-700 @endif">
                {{ $order->status->label() }}
            </span>
        </div>

        <div>
            <h2 class="text-lg font-semibold mb-2">Данные доставки</h2>
            <p><strong>Телефон:</strong> {{ $order->shipping_address['phone'] ?? '—' }}</p>
            <p><strong>Город:</strong> {{ $order->shipping_address['city'] ?? '—' }}</p>
            <p><strong>Адрес:</strong> {{ $order->shipping_address['street'] ?? '—' }}</p>
        </div>

        <div>
            <h2 class="text-lg font-semibold mb-2">Состав заказа</h2>
            <table class="w-full border-t">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Товар</th>
                    <th class="p-3 text-left">Кол-во</th>
                    <th class="p-3 text-left">Цена</th>
                    <th class="p-3 text-left">Сумма</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->items as $item)
                    <tr class="border-b">
                        <td class="p-3">{{ $item->product->name }}</td>
                        <td class="p-3">{{ $item->quantity }}</td>
                        <td class="p-3">{{ number_format($item->price, 2) }} ₽</td>
                        <td class="p-3 font-semibold">{{ number_format($item->price * $item->quantity, 2) }} ₽</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center border-t pt-6">
            <div class="text-lg font-semibold">Итого:</div>
            <div class="text-2xl font-bold text-blue-600">
                {{ number_format($order->total, 2) }} ₽
            </div>
        </div>

        <div class="text-right">
            <a href="{{ route('products.index') }}"
               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Продолжить покупки
            </a>
        </div>
    </div>
@endsection
