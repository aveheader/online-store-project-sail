@extends('layouts.app')

@section('title', 'Категории товаров')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Категории</h1>

        @if($categories->isEmpty())
            <p class="text-gray-600">Категории пока не добавлены.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->id) }}"
                       class="block bg-white shadow rounded-lg p-4 hover:shadow-lg transition">
                        <h2 class="text-lg font-semibold">{{ $category->name }}</h2>
                        @if($category->description)
                            <p class="text-sm text-gray-500 mt-2">{{ Str::limit($category->description, 80) }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
