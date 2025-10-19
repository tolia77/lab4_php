<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
        @endif

        @if(empty($cartItems))
            <div class="bg-white shadow rounded p-8 text-center">
                <p class="text-gray-600 mb-4">Your cart is empty</p>
                <a href="{{ route('products.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="bg-white shadow rounded overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="{{ route('products.show', $item['product']) }}" class="text-blue-600 hover:underline">
                                        {{ $item['product']->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">${{ number_format($item['product']->price, 2) }}</td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                               min="1" max="{{ $item['product']->stock_quantity }}"
                                               class="w-20 px-2 py-1 border rounded">
                                        <button type="submit" class="text-sm text-blue-600 hover:underline">Update</button>
                                    </form>
                                </td>
                                <td class="px-6 py-4">${{ number_format($item['subtotal'], 2) }}</td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-semibold">Total:</td>
                            <td class="px-6 py-4 font-semibold">${{ number_format($total, 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <div class="space-x-4">
                    <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">
                        Continue Shopping
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Clear entire cart?')">
                            Clear Cart
                        </button>
                    </form>
                </div>
                <a href="{{ route('cart.checkout') }}" class="px-6 py-3 bg-green-600 text-white rounded hover:bg-green-700">
                    Proceed to Checkout
                </a>
            </div>
        @endif
    </div>
</x-app-layout>

