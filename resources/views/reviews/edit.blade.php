<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Review</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto px-4">
        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('reviews.update', $review) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                    <select name="rating" id="rating" required class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm">
                        @for($i=1;$i<=5;$i++)
                            <option value="{{ $i }}" @selected($review->rating == $i)>{{ $i }}</option>
                        @endfor
                    </select>
                    @error('rating') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700">Comment</label>
                    <textarea name="comment" id="comment" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('comment', $review->comment) }}</textarea>
                    @error('comment') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update Review</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

