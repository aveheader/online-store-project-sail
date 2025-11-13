@extends('layouts.app')

@section('title', 'Мои заказы')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Мои заказы</h1>

    @if($orders->isEmpty())
        <div class="bg-white shadow rounded-xl p-8 text-center">
            <p class="text-gray-600 text-lg mb-4">У вас пока нет заказов.</p>
            <a href="{{ route('products.index') }}"
               class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Перейти к покупкам
            </a>
        </div>
    @else
        <div class="bg-white shadow rounded-xl overflow-hidden">
            <table class="w-full border-t">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">№ заказа</th>
                    <th class="p-3 text-left">Дата</th>
                    <th class="p-3 text-left">Статус</th>
                    <th class="p-3 text-left">Сумма</th>
                    <th class="p-3 text-right">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-semibold text-gray-700">#{{ $order->id }}</td>
                        <td class="p-3 text-gray-600">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td class="p-3">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($order->status->value === 'new') bg-blue-100 text-blue-700
                                @elseif($order->status->value === 'processing') bg-yellow-100 text-yellow-700
                                @else bg-green-100 text-green-700 @endif">
                                {{ $order->status->label() }}
                            </span>
                        </td>
                        <td class="p-3 font-semibold">{{ number_format($order->total, 2) }} ₽</td>
                        <td class="p-3 text-right">
                            <a href="{{ route('orders.show', $order) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                Подробнее
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="p-4 border-t bg-gray-50">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
@endsection
