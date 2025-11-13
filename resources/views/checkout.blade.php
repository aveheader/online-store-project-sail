@extends('layouts.app')
@section('title', 'Оформление заказа')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Оформление заказа</h1>

    <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block font-medium text-gray-700">Имя</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full border rounded-lg px-4 py-2" required>
        </div>

        <div>
            <label class="block font-medium text-gray-700">Телефон</label>
            <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}"
                   class="w-full border rounded-lg px-4 py-2" required>
        </div>

        <div>
            <label class="block font-medium text-gray-700">Способ доставки</label>
            <select name="delivery" class="w-full border rounded-lg px-4 py-2" required>
                <option value="courier" selected>Курьер</option>
                <option value="pickup">Самовывоз</option>
            </select>
        </div>

        <div>
            <label class="block font-medium text-gray-700">Город</label>
            <input type="text" name="city" value="{{ old('city', $profile->city ?? '') }}"
                   class="w-full border rounded-lg px-4 py-2">
        </div>

        <div>
            <label class="block font-medium text-gray-700">Улица, дом</label>
            <input type="text" name="street" value="{{ old('street', $profile->street ?? '') }}"
                   class="w-full border rounded-lg px-4 py-2">
        </div>

        <div class="flex items-center space-x-2">
            <label class="inline-flex items-center">
                <input
                    type="checkbox"
                    name="save_profile"
                    value="1"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    {{ old('save_profile') ? 'checked' : '' }}
                >
                <span class="ml-2 text-gray-700">Сохранить эти данные в профиле</span>
            </label>
        </div>

        <div class="text-right pt-4">
            <button type="submit"
                    class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-medium">
                Подтвердить заказ
            </button>
        </div>
    </form>
@endsection
