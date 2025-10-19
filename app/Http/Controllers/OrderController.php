<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }


    public function store(StoreOrderRequest $request)
    {
        $userId = optional($request->user())->id;
        $order = $this->service->createOrder($request->validated(), $userId);

        session()->forget('cart');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order,
            ], 201);
        }

        return redirect()->route('orders.confirmation', $order->id)
            ->with('success', 'Order placed successfully!');
    }

    public function confirmation(Order $order)
    {
        $order->load(['orderItems.product', 'customer']);

        return view('orders.confirmation', compact('order'));
    }

    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->isAdmin()) {
            $orders = Order::with(['orderItems.product', 'customer'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $orders = Order::whereHas('customer', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['orderItems.product', 'customer'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['orderItems.product', 'customer']);

        $user = auth()->user();
        if ($user && $order->customer->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        $user = auth()->user();
        if (! $user || ! $user->isAdmin()) {
            abort(403);
        }

        $order->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Order deleted.'], 200);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }
}
