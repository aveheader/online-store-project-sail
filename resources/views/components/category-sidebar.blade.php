<div class="bg-white shadow p-3 rounded-xl">
    <h3 class="text-lg font-semibold mb-2">Категории</h3>
    <ul class="space-y-1">
        @foreach($categories as $cat)
            <li>
                <a href="{{ route('categories.show', $cat) }}"
                   class="block px-2 py-1 rounded hover:bg-blue-50 {{ request()->is('categories/'.$cat->id) ? 'bg-blue-100 font-medium' : '' }}">
                    {{ $cat->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
