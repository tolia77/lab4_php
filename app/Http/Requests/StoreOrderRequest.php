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

