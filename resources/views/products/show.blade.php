<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
        @endif

        <div class="bg-white shadow rounded p-6">
            <div class="mb-4">
                <strong class="text-gray-700">Category:</strong>
                <span class="text-gray-900">{{ $product->category->name ?? 'No Category' }}</span>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Price:</strong>
                <span class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Stock:</strong>
                <span class="text-gray-900">
                    {{ $product->stock_quantity }}
                    @if($product->stock_quantity > 0)
                        <span class="text-green-600">In Stock</span>
                    @else
                        <span class="text-red-600">Out of Stock</span>
                    @endif
                </span>
            </div>
            <div class="mb-6">
                <strong class="text-gray-700">Description:</strong>
                <div class="text-gray-900 mt-2">{{ $product->description }}</div>
            </div>

            @if($product->stock_quantity > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-center gap-4">
                    @csrf
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" value="1"
                               min="1" max="{{ $product->stock_quantity }}"
                               class="w-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="submit" class="mt-6 px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Add to Cart
                    </button>
                </form>
            @else
                <div class="p-4 bg-gray-100 text-gray-600 rounded">
                    This product is currently out of stock.
                </div>
            @endif
        </div>

        <div class="mt-6 flex gap-4">
            <a href="{{ route('products.index') }}" class="text-blue-700 hover:underline">← Back to Products</a>
            <a href="{{ route('cart.index') }}" class="text-blue-700 hover:underline">View Cart</a>
        </div>
    </div>
</x-app-layout>
