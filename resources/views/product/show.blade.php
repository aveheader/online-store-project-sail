@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="max-w-4xl mx-auto bg-white shadow p-6 rounded-xl">
        <div class="grid grid-cols-2 gap-6">
            <img src="{{ $product->image_url ?? '/images/no-image.png' }}"
                 alt="{{ $product->name }}"
                 class="w-full h-96 object-cover rounded-xl">

            <div>
                <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>
                <p class="text-gray-600 mb-4">{{ $product->description }}</p>

                <div class="text-xl font-semibold text-blue-600 mb-4">
                    {{ number_format($product->price, 2) }} ₽
                </div>

                @auth
                    <button
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors"
                        data-cart-add
                        data-product-id="{{ $product->id }}"
                        data-quantity="1">
                        В корзину
                    </button>
                @else
                    <a href="{{ route('login') }}" class="text-blue-500 underline">Войдите, чтобы добавить в корзину</a>
                @endauth
            </div>
        </div>
    </div>
@endsection
