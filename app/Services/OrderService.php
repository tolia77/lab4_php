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
            // Find or create customer
            if (!empty($data['customer_id'])) {
                $customer = Customer::findOrFail($data['customer_id']);
            } else {
                $customerData = $data['customer'] ?? [];
                if ($userId !== null) {
                    $customerData['user_id'] = $userId;
                }
                $customer = Customer::create($customerData);
            }

            // Create the order
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_date' => now(),
                'status' => 'pending',
            ]);

            $total = 0;

            // Process each order item
            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];

                // Check stock availability
                if ($product->stock_quantity < $qty) {
                    throw ValidationException::withMessages([
                        'items' => ["Insufficient stock for product: {$product->name}"],
                    ]);
                }

                $subtotal = round($product->price * $qty, 2);

                // Create order item
                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);

                // Decrement stock
                $product->stock_quantity -= $qty;
                $product->save();

                $total += $subtotal;
            }

            // Return order with relationships loaded
            return $order->fresh('orderItems.product', 'customer');
        });
    }
}

