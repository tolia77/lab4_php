<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">All Reviews</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4">
        <div class="bg-white shadow rounded p-6">
            @if($reviews->isEmpty())
                <p class="text-gray-600">No reviews found.</p>
            @else
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="border rounded p-4 flex justify-between">
                            <div>
                                <div class="font-semibold">
                                    {{ $review->product->name ?? 'Product' }} — {{ $review->customer->first_name ?? 'Customer' }}
                                </div>
                                <div class="text-sm text-gray-600">Rating: {{ $review->rating }} — {{ optional($review->created_at)->format('Y-m-d') }}</div>
                                @if($review->comment)
                                    <div class="mt-2 text-gray-700">{{ $review->comment }}</div>
                                @endif
                            </div>
                            <div class="flex items-start gap-2">
                                <a href="{{ route('reviews.edit', $review) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

