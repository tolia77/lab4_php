<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }} - #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4">
        <div class="bg-white shadow rounded p-6 mb-6">
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
                    <span class="inline-block px-3 py-1 text-sm rounded-full
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
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
                            <td class="px-4 py-3">
                                <a href="{{ route('products.show', $item->product) }}" class="text-blue-600 hover:underline">
                                    {{ $item->product->name }}
                                </a>
                            </td>
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

        <div class="mt-6">
            @auth
                <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">
                    ← Back to My Orders
                </a>
            @else
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">
                    ← Back to Products
                </a>
            @endauth
        </div>
    </div>
</x-app-layout>
