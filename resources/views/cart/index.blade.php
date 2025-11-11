@extends('layouts.app')
@section('title', 'Корзина')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Моя корзина</h1>

    @if(!$cart || $cart->items->isEmpty())
        <div class="bg-white shadow rounded-xl p-8 text-center">
            <p class="text-gray-600 text-lg">Ваша корзина пуста.</p>
            <a href="{{ route('products.index') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Перейти к товарам
            </a>
        </div>
    @else
        <div class="bg-white shadow rounded-xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left">Товар</th>
                    <th class="p-4 text-left">Цена</th>
                    <th class="p-4 text-left">Количество</th>
                    <th class="p-4 text-left">Итого</th>
                    <th class="p-4 text-right">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cart->items as $item)
                    <tr class="border-b" data-cart-item="{{ $item->product_id }}">
                        <td class="p-4">
                            <div class="flex items-center">
                                @if($item->product->image_url)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" 
                                         class="w-16 h-16 object-cover rounded mr-3">
                                @endif
                                <div>
                                    <a href="{{ route('products.show', $item->product) }}" class="font-medium text-blue-600 hover:underline">
                                        {{ $item->product->name }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="p-4" data-item-price="{{ $item->product->price }}" data-price="{{ number_format($item->product->price, 2) }}">
                            {{ number_format($item->product->price, 2) }} ₽
                        </td>
                        <td class="p-4">
                            <input type="number" 
                                   min="1" 
                                   value="{{ $item->quantity }}" 
                                   data-cart-quantity 
                                   data-product-id="{{ $item->product_id }}"
                                   class="w-20 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </td>
                        <td class="p-4 font-semibold" data-item-total>
                            {{ number_format($item->product->price * $item->quantity, 2) }} ₽
                        </td>
                        <td class="p-4 text-right">
                            <button class="text-red-600 hover:text-red-800 font-medium" 
                                    data-cart-remove 
                                    data-product-id="{{ $item->product_id }}">
                                Удалить
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="bg-gray-50 p-6 flex justify-between items-center">
                <div>
                    <span class="text-lg font-semibold">Итого:</span>
                    <span class="text-2xl font-bold text-blue-600 ml-2" data-cart-total>
                        {{ number_format($cart->items->sum(fn($i) => $i->product->price * $i->quantity), 2) }} ₽
                    </span>
                </div>
                <div>
                    <a href="#" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-medium">
                        Оформить заказ
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
