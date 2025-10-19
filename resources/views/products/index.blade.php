<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
        @endif

        @auth
            @if(Auth::user()->isAdmin())
                <a href="{{ route('products.create') }}" class="mb-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Product</a>
            @endif
        @endauth

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white shadow rounded overflow-hidden hover:shadow-lg transition">
                    @if($product->image_path)
                        <img src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}" class="w-full h-40 object-cover">
                    @endif
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">
                            <a href="{{ route('products.show', $product) }}" class="text-gray-900 hover:text-blue-600">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $product->category->name ?? 'No Category' }}</p>
                        <p class="text-2xl font-bold text-green-600 mb-2">${{ number_format($product->price, 2) }}</p>
                        <p class="text-sm text-gray-600 mb-4">
                            Stock: {{ $product->stock_quantity }}
                            @if($product->stock_quantity > 0)
                                <span class="text-green-600">✓</span>
                            @else
                                <span class="text-red-600">Out of Stock</span>
                            @endif
                        </p>

                        <div class="flex gap-2">
                            <a href="{{ route('products.show', $product) }}" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded text-center hover:bg-gray-300 text-sm">
                                View Details
                            </a>
                            @if($product->stock_quantity > 0)
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                        Add to Cart
                                    </button>
                                </form>
                            @endif
                        </div>

                        @auth
                            @if(Auth::user()->isAdmin())
                                <div class="mt-4 pt-4 border-t flex gap-2">
                                    <a href="{{ route('products.edit', $product) }}" class="text-sm text-yellow-600 hover:underline">Edit</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:underline" onclick="return confirm('Delete this product?')">Delete</button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
