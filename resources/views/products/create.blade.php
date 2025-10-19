<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Product') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block font-medium">Name</label>
                <input type="text" name="name" class="w-full border rounded p-2" required value="{{ old('name') }}">
                @error('name') <div class="text-red-600">{{ $message }}</div> @enderror
            </div>
            <div class="mb-4">
                <label class="block font-medium">Description</label>
                <textarea name="description" class="w-full border rounded p-2">{{ old('description') }}</textarea>
                @error('description') <div class="text-red-600">{{ $message }}</div> @enderror
            </div>
            <div class="mb-4">
                <label class="block font-medium">Price</label>
                <input type="number" step="0.01" name="price" class="w-full border rounded p-2" required value="{{ old('price') }}">
                @error('price') <div class="text-red-600">{{ $message }}</div> @enderror
            </div>
            <div class="mb-4">
                <label class="block font-medium">Stock Quantity</label>
                <input type="number" name="stock_quantity" class="w-full border rounded p-2" required value="{{ old('stock_quantity') }}">
                @error('stock_quantity') <div class="text-red-600">{{ $message }}</div> @enderror
            </div>
            <div class="mb-4">
                <label class="block font-medium">Category</label>
                <select name="category_id" class="w-full border rounded p-2" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if(old('category_id') == $category->id) selected @endif>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <div class="text-red-600">{{ $message }}</div> @enderror
            </div>
            <div class="mb-6">
                <label class="block font-medium">Image</label>
                <input type="file" name="image" accept="image/*" class="w-full border rounded p-2">
                @error('image') <div class="text-red-600">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
            <a href="{{ route('products.index') }}" class="ml-4 text-gray-700">Cancel</a>
        </form>
    </div>
</x-app-layout>
