<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4">
        @if($orders->isEmpty())
            <div class="bg-white shadow rounded p-8 text-center">
                <p class="text-gray-600 mb-4">You haven't placed any orders yet.</p>
                <a href="{{ route('products.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Start Shopping
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white shadow rounded p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold">Order #{{ $order->id }}</h3>
                                <p class="text-sm text-gray-600">{{ $order->order_date->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="text-right">
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

                        <div class="border-t pt-4">
                            <div class="space-y-2">
                                @php $total = 0; @endphp
                                @foreach($order->orderItems as $item)
                                    <div class="flex justify-between text-sm">
                                        <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                                        <span>${{ number_format($item->subtotal, 2) }}</span>
                                    </div>
                                    @php $total += $item->subtotal; @endphp
                                @endforeach
                                <div class="border-t pt-2 flex justify-between font-semibold">
                                    <span>Total:</span>
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:underline text-sm">
                                View Details →
                            </a>

                            @auth
                                @if(Auth::user()->isAdmin())
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline ml-4" onsubmit="return confirm('Delete this order?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline text-sm">Delete</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
