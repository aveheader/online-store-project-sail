@props(['messages' => null])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif

@if ($errors->has($field))
    <span class="text-red-500 text-sm">{{ $errors->first($field) }}</span>
@endif
