<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div>
            <strong>Products in this category:</strong>
            <ul class="list-disc ml-6">
                @forelse($category->products as $product)
                    <li>{{ $product->name }}</li>
                @empty
                    <li>No products in this category.</li>
                @endforelse
            </ul>
        </div>
        <a href="{{ route('categories.index') }}" class="inline-block mt-4 text-blue-700 hover:underline">Back to Categories</a>
    </div>
</x-app-layout>
