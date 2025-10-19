<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a new order.
     * Works for both authenticated and guest users.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $userId = optional($request->user())->id;
        $order = $this->service->createOrder($request->validated(), $userId);

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
        ], 201);
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|exists:customers,id',
            'customer' => 'required_without:customer_id|array',
            'customer.first_name' => 'required_without:customer_id|string|max:255',
            'customer.last_name' => 'required_without:customer_id|string|max:255',
            'customer.email' => 'required_without:customer_id|email|max:255',
            'customer.phone_number' => 'nullable|string|max:255',
            'customer.shipping_address' => 'nullable|string',
            'customer.billing_address' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }
}

