@extends('layouts.app')
@section('title', 'Корзина')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Моя корзина</h1>

    @if(!$cart || $cart->items->isEmpty())
        <p>Ваша корзина пуста.</p>
    @else
        <table class="w-full text-left bg-white shadow rounded-xl">
            <thead class="bg-gray-100">
            <tr>
                <th class="p-3">Товар</th>
                <th class="p-3">Цена</th>
                <th class="p-3">Кол-во</th>
                <th class="p-3">Итого</th>
                <th class="p-3"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($cart->items as $item)
                <tr class="border-b">
                    <td class="p-3">{{ $item->product->name }}</td>
                    <td class="p-3">{{ number_format($item->product->price, 2) }} ₽</td>
                    <td class="p-3">{{ $item->quantity }}</td>
                    <td class="p-3">{{ number_format($item->product->price * $item->quantity, 2) }} ₽</td>
                    <td class="p-3 text-right">
                        <button class="js-remove-from-cart text-red-500 hover:underline"
                                data-product-id="{{ $item->product_id }}">
                            Удалить
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-right mt-4">
        <span class="text-xl font-semibold">
            Итого: {{ number_format($cart->items->sum(fn($i) => $i->product->price * $i->quantity), 2) }} ₽
        </span>
            <a href="#" class="bg-green-600 text-white px-4 py-2 rounded ml-3 hover:bg-green-700">Оформить заказ</a>
        </div>
    @endif
@endsection
