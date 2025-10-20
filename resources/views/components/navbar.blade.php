<nav class="bg-white shadow-md py-3">
    <div class="container mx-auto flex justify-between items-center px-4">
        <a href="{{ route('products.index') }}" class="text-xl font-bold text-blue-600">Shop</a>

        <div class="flex space-x-4 items-center">
            <a href="{{ route('products.index') }}" class="hover:text-blue-500">Товары</a>
            <a href="{{ route('categories.index') }}" class="hover:text-blue-500">Категории</a>

            @auth
                <a href="{{ route('cart.index') }}" class="hover:text-blue-500">
                    Корзина ({{ auth()->user()->cart?->items()->count() ?? 0 }})
                </a>
                <a href="{{ route('dashboard') }}" class="hover:text-blue-500">{{ auth()->user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500 hover:underline">Выйти</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-blue-500">Войти</a>
                <a href="{{ route('register') }}" class="hover:text-blue-500">Регистрация</a>
            @endauth
        </div>
    </div>
</nav>
