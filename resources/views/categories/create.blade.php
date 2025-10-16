<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Category') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block font-medium">Name</label>
                <input type="text" name="name" class="w-full border rounded p-2" required value="{{ old('name') }}">
                @error('name') <div class="text-red-600">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
            <a href="{{ route('categories.index') }}" class="ml-4 text-gray-700">Cancel</a>
        </form>
    </div>
</x-app-layout>
