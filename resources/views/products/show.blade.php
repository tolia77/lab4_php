<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="mb-2">
            <strong>Category:</strong> {{ $product->category->name ?? 'No Category' }}
        </div>
        <div class="mb-2">
            <strong>Price:</strong> ${{ $product->price }}
        </div>
        <div class="mb-2">
            <strong>Stock:</strong> {{ $product->stock_quantity }}
        </div>
        <div class="mb-2">
            <strong>Description:</strong>
            <div>{{ $product->description }}</div>
        </div>
        <a href="{{ route('products.index') }}" class="inline-block mt-4 text-blue-700 hover:underline">Back to Products</a>
    </div>
</x-app-layout>

