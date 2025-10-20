@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">{{ $category->name }}</h1>
        @if($category->description)
            <p class="mb-6 text-gray-600">{{ $category->description }}</p>
        @endif

        @if($products->isEmpty())
            <p>В этой категории пока нет товаров.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <img src="{{ $product->image_url ?? 'https://via.placeholder.com/200x150' }}"
                             alt="{{ $product->name }}" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold mb-2">{{ $product->name }}</h2>
                            <p class="text-gray-600 mb-3">{{ Str::limit($product->description, 70) }}</p>
                            <p class="font-bold mb-3">{{ number_format($product->price, 2) }} ₽</p>
                            <a href="{{ route('products.show', $product->id) }}"
                               class="text-blue-600 hover:underline">Подробнее</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
