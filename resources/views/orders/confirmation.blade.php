<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Confirmation') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow rounded p-6 mb-6">
            <div class="flex items-center mb-4">
                <svg class="w-12 h-12 text-green-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-2xl font-semibold text-green-600">Order Placed Successfully!</h3>
                    <p class="text-gray-600">Thank you for your order.</p>
                </div>
            </div>

            <div class="border-t pt-4">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Order Number:</p>
                        <p class="font-semibold">#{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Order Date:</p>
                        <p class="font-semibold">{{ $order->order_date->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status:</p>
                        <p class="font-semibold capitalize">{{ $order->status }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded p-6 mb-6">
            <h4 class="text-lg font-semibold mb-4">Customer Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Name:</p>
                    <p class="font-semibold">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email:</p>
                    <p class="font-semibold">{{ $order->customer->email }}</p>
                </div>
                @if($order->customer->phone_number)
                <div>
                    <p class="text-sm text-gray-600">Phone:</p>
                    <p class="font-semibold">{{ $order->customer->phone_number }}</p>
                </div>
                @endif
                @if($order->customer->shipping_address)
                <div>
                    <p class="text-sm text-gray-600">Shipping Address:</p>
                    <p class="font-semibold">{{ $order->customer->shipping_address }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white shadow rounded p-6">
            <h4 class="text-lg font-semibold mb-4">Order Items</h4>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Product</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Price</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Quantity</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $total = 0; @endphp
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td class="px-4 py-3">{{ $item->product->name }}</td>
                            <td class="px-4 py-3">${{ number_format($item->product->price, 2) }}</td>
                            <td class="px-4 py-3">{{ $item->quantity }}</td>
                            <td class="px-4 py-3">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @php $total += $item->subtotal; @endphp
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right font-semibold">Total:</td>
                        <td class="px-4 py-3 font-semibold">${{ number_format($total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-6 flex gap-4">
            <a href="{{ route('products.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700">
                Continue Shopping
            </a>
            @auth
                <a href="{{ route('orders.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded hover:bg-gray-700">
                    View My Orders
                </a>
            @endauth
        </div>
    </div>
</x-app-layout>

