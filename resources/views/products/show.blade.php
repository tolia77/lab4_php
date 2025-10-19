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
            @if($product->image_path && ($product->image_url || $product->image_data_uri))
                <div class="mb-6">
                    <img src="{{ $product->image_url ?? $product->image_data_uri }}" alt="{{ $product->name }}" class="w-full max-h-96 object-cover rounded">
                </div>
            @endif
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

        <!-- Reviews section -->
        <div class="mt-6 bg-white shadow rounded p-6">
            <h3 class="text-lg font-semibold mb-4">Customer Reviews</h3>

            @if($product->reviews->isEmpty())
                <p class="text-gray-600">No reviews yet. Be the first to review this product.</p>
            @else
                <div class="space-y-4">
                    @foreach($product->reviews->sortByDesc('created_at') as $review)
                        <div class="border rounded p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <strong class="text-gray-800">
                                        {{ $review->customer->first_name ?? 'Customer' }}
                                        {{ $review->customer->last_name ?? '' }}
                                    </strong>
                                    <div class="text-sm text-gray-500">Rated: {{ $review->rating }} / 5</div>
                                </div>
                                <div class="text-sm text-gray-400">
                                    {{ optional($review->created_at)->format('Y-m-d') }}
                                </div>
                            </div>
                            @if($review->comment)
                                <div class="mt-2 text-gray-700">{{ $review->comment }}</div>
                            @endif

                            {{-- show edit/delete for admin users or the review owner --}}
                            @if(auth()->user() && (auth()->user()->isAdmin() || (auth()->user()->customer && auth()->user()->customer->id === $review->customer_id)))
                                <div class="mt-2">
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                    <a href="{{ route('reviews.edit', $review) }}" class="ml-4 text-blue-600 hover:underline">Edit</a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Review form for authenticated customers -->
            <div class="mt-6">
                @auth
                    @php
                        $hasReviewed = false;
                        $cust = auth()->user()->customer ?? null;
                        if ($cust) {
                            $hasReviewed = $product->reviews->contains('customer_id', $cust->id);
                        }
                    @endphp

                    @if($hasReviewed)
                        <div class="p-4 bg-yellow-50 text-yellow-800 rounded">
                            You have already reviewed this product.
                        </div>
                    @else
                        <form action="{{ route('products.reviews.store', $product) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                <select name="rating" id="rating" required
                                        class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm">
                                    <option value="">Choose</option>
                                    @for($i=1;$i<=5;$i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('rating') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="comment" class="block text-sm font-medium text-gray-700">Comment (optional)</label>
                                <textarea name="comment" id="comment" rows="4"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('comment') }}</textarea>
                                @error('comment') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    @endif
                @else
                    <p class="text-gray-600">Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">log in</a> to leave a review.</p>
                @endauth
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <a href="{{ route('products.index') }}" class="text-blue-700 hover:underline">← Back to Products</a>
            <a href="{{ route('cart.index') }}" class="text-blue-700 hover:underline">View Cart</a>
        </div>
    </div>
</x-app-layout>
