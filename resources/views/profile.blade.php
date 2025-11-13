@extends('layouts.app')
@section('title', 'Профиль')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Профиль</h1>

    <div class="bg-white shadow p-4 rounded-xl">
        <p><strong>Имя:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        <p><strong>Дата регистрации:</strong> {{ auth()->user()->created_at->format('d.m.Y') }}</p>
    </div>

    {{-- Заготовка под "лайкнутые" товары или заказы --}}
    <div class="mt-6">
        <h2 class="text-xl font-semibold mb-3">Избранные товары (скоро)</h2>
        <p class="text-gray-500">Здесь появятся лайкнутые товары.</p>
    </div>
@endsection
