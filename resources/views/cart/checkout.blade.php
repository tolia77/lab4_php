<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4">
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Order Summary -->
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                <div class="space-y-2">
                    @foreach($cartItems as $item)
                        <div class="flex justify-between text-sm">
                            <span>{{ $item['product']->name }} × {{ $item['quantity'] }}</span>
                            <span>${{ number_format($item['subtotal'], 2) }}</span>
                        </div>
                    @endforeach
                    <div class="border-t pt-2 mt-2 flex justify-between font-semibold">
                        <span>Total:</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Information Form -->
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-semibold mb-4">Customer Information</h3>

                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf

                    @if($customer)
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <div class="mb-4 p-4 bg-blue-50 rounded">
                            <p class="text-sm text-gray-700">Shipping to:</p>
                            <p class="font-semibold">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                            <p class="text-sm">{{ $customer->email }}</p>
                            @if($customer->shipping_address)
                                <p class="text-sm">{{ $customer->shipping_address }}</p>
                            @endif
                        </div>
                    @else
                        <div class="space-y-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                                <input type="text" name="customer[first_name]" id="first_name" required
                                       value="{{ old('customer.first_name') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                                <input type="text" name="customer[last_name]" id="last_name" required
                                       value="{{ old('customer.last_name') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                <input type="email" name="customer[email]" id="email" required
                                       value="{{ old('customer.email', auth()->user()->email ?? '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" name="customer[phone_number]" id="phone_number"
                                       value="{{ old('customer.phone_number') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                                <textarea name="customer[shipping_address]" id="shipping_address" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('customer.shipping_address') }}</textarea>
                            </div>

                            <div>
                                <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
                                <textarea name="customer[billing_address]" id="billing_address" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('customer.billing_address') }}</textarea>
                            </div>
                        </div>
                    @endif

                    <!-- Hidden items input -->
                    @foreach($cartItems as $index => $item)
                        <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item['product']->id }}">
                        <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
                    @endforeach

                    <div class="mt-6 flex gap-4">
                        <a href="{{ route('cart.index') }}" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded text-center hover:bg-gray-400">
                            Back to Cart
                        </a>
                        <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
