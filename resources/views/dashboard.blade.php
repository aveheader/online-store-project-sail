@extends('layouts.app')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Личный кабинет</h1>

        <p class="text-gray-600 mb-6">
            Добро пожаловать, <span class="font-semibold">{{ auth()->user()->name }}</span>!
        </p>

        <div class="space-y-4">
            <a href="{{ route('profile.edit') }}"
               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                ✏️ Редактировать профиль
            </a>

            <a href="{{ route('cart.index') }}"
               class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                🛒 Моя корзина
            </a>

            <a href="{{ route('products.index') }}"
               class="inline-block px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                🏪 Перейти к товарам
            </a>
        </div>
    </div>
@endsection
