<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="mb-4">
            <strong>Products in this category</strong>
            <span class="text-sm text-gray-500">({{ $category->products->count() }})</span>
            <ul class="list-disc ml-6 mt-2">
                @forelse($category->products as $product)
                    <li>
                        <a href="{{ route('products.show', $product) }}" class="text-blue-700 hover:underline">
                            {{ $product->name }}
                        </a>
                    </li>
                @empty
                    <li>No products in this category.</li>
                @endforelse
            </ul>
        </div>

        <a href="{{ route('categories.index') }}" class="inline-block mt-4 text-blue-700 hover:underline">Back to Categories</a>
    </div>
</x-app-layout>
