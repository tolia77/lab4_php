<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // List all products (all users)
    public function index()
    {
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    // Show a single product (all users)
    public function show(Product $product)
    {
        // load category and reviews with customer to avoid N+1
        $product->load([
            'category',
            'reviews.customer' // ensure customer is eager loaded for each review
        ]);

        return view('products.show', compact('product'));
    }

    // Show create form (admin only)
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // Store new product (admin only)
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        # TODO: Поміняти на шось інакше не помню шо
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);
        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    // Show edit form (admin only)
    public function edit(Product $product)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    // Update product (admin only)
    public function update(Request $request, Product $product)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);
        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    // Delete product (admin only)
    public function destroy(Product $product)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}
