<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load([
            'category',
            'reviews.customer'
        ]);

        return view('products.show', compact('product'));
    }

    public function create()
    {
        if (! (auth()->check() && auth()->user()->isAdmin())) {
            abort(403);
        }
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        if (! (auth()->check() && auth()->user()->isAdmin())) {
            abort(403);
        }
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }
        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        if (! (auth()->check() && auth()->user()->isAdmin())) {
            abort(403);
        }
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        if (! (auth()->check() && auth()->user()->isAdmin())) {
            abort(403);
        }
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }
        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if (! (auth()->check() && auth()->user()->isAdmin())) {
            abort(403);
        }
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}
