<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        @auth
            @if(Auth::user()->isAdmin())
                <a href="{{ route('products.create') }}" class="mb-4 inline-block px-4 py-2 bg-blue-600 text-white rounded">Add Product</a>
            @endif
        @endauth

        <ul class="bg-white shadow rounded divide-y">
            @foreach($products as $product)
                <li class="p-4 flex justify-between items-center">
                    <a href="{{ route('products.show', $product) }}" class="text-lg text-blue-700 hover:underline">
                        {{ $product->name }} ({{ $product->category->name ?? 'No Category' }})
                    </a>
                    @auth
                        @if(Auth::user()->isAdmin())
                            <span>
                                <a href="{{ route('products.edit', $product) }}" class="text-sm text-yellow-600 mr-2">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600" onclick="return confirm('Delete this product?')">Delete</button>
                                </form>
                            </span>
                        @endif
                    @endauth
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>

