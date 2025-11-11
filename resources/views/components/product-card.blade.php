<div class="bg-white shadow rounded-xl p-3 flex flex-col">
    <a href="{{ route('products.show', $product) }}">
        <img src="{{ $product->image_url ?? '/images/no-image.png' }}"
             alt="{{ $product->name }}"
             class="w-full h-48 object-cover rounded-lg mb-2">
        <h2 class="text-lg font-medium">{{ $product->name }}</h2>
    </a>

    <p class="text-gray-600 text-sm flex-1">{{ Str::limit($product->description, 80) }}</p>

    <div class="mt-3 flex justify-between items-center">
        <span class="font-bold text-blue-600">{{ number_format($product->price, 2) }} ₽</span>
        @auth
            <button
                data-cart-add
                data-product-id="{{ $product->id }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                В корзину
            </button>
        @else
            <a href="{{ route('login') }}" class="text-blue-500 text-sm">Войти</a>
        @endauth
    </div>
</div>
