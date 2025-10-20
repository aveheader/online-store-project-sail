@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="grid grid-cols-12 gap-6">
        {{-- Сайдбар категорий --}}
        <aside class="col-span-3">
            @include('components.category-sidebar', ['categories' => $categories])
        </aside>

        {{-- Список товаров --}}
        <section class="col-span-9">
            <h1 class="text-2xl font-semibold mb-4">Товары</h1>
            <div class="grid grid-cols-3 gap-4">
                @foreach($products as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </section>
    </div>
@endsection
