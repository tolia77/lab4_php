<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    /**
     * Create an order from validated data.
     *
     * @param array $data Validated order data with customer and items
     * @param int|null $userId Optional authenticated user id
     * @return Order
     */
    public function createOrder(array $data, ?int $userId = null): Order
    {
        return DB::transaction(function () use ($data, $userId) {
            if (!empty($data['customer_id'])) {
                $customer = Customer::findOrFail($data['customer_id']);
            } else {
                $customerPayload = $data['customer'] ?? [];

                $customer = null;
                if (!empty($customerPayload['email'])) {
                    $customer = Customer::where('email', $customerPayload['email'])->lockForUpdate()->first();
                }

                if ($customer) {
                    $customer->fill([
                        'first_name' => $customerPayload['first_name'] ?? $customer->first_name,
                        'last_name' => $customerPayload['last_name'] ?? $customer->last_name,
                        'phone_number' => $customerPayload['phone_number'] ?? $customer->phone_number,
                        'shipping_address' => $customerPayload['shipping_address'] ?? $customer->shipping_address,
                        'billing_address' => $customerPayload['billing_address'] ?? $customer->billing_address,
                    ]);

                    if ($userId && ! $customer->user_id) {
                        $customer->user_id = $userId;
                    }
                    $customer->save();
                } else {
                    if ($userId !== null) {
                        $customerPayload['user_id'] = $userId;
                    }
                    $customer = Customer::create($customerPayload);
                }
            }

            // Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_date' => now(),
                'status' => 'pending',
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];

                if ($product->stock_quantity < $qty) {
                    throw ValidationException::withMessages([
                        'items' => ["Insufficient stock for product: {$product->name}"],
                    ]);
                }

                $subtotal = round($product->price * $qty, 2);

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock_quantity', $qty);
                $total += $subtotal;
            }

            // Return order with relationships loaded
            return $order->fresh('orderItems.product', 'customer');
        });
    }
}
