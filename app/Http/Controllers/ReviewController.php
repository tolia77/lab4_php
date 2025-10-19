<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Store a new review for a product (authenticated customer)
    public function store(Request $request, Product $product)
    {
        $user = Auth::user();
        if (!$user || !$user->customer) {
            return redirect()->back()->with('error', 'You must be logged in as a customer to post a review.');
        }

        $customerId = $user->customer->id;

        // Prevent duplicate reviews by the same customer for the same product
        $already = Review::where('product_id', $product->id)
            ->where('customer_id', $customerId)
            ->exists();

        if ($already) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'product_id' => $product->id,
            'customer_id' => $customerId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Review submitted.');
    }

    // Admin: list all reviews
    public function index()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $reviews = Review::with(['product', 'customer'])->orderBy('created_at', 'desc')->get();
        return view('reviews.index', compact('reviews'));
    }

    // Admin: show edit form
    public function edit(Review $review)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403);
        }
        return view('reviews.edit', compact('review'));
    }

    // Admin: update review
    public function update(Request $request, Review $review)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        return redirect()->route('products.show', $review->product_id)->with('success', 'Review updated.');
    }

    // Admin: delete review
    public function destroy(Review $review)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $review->delete();
        return redirect()->back()->with('success', 'Review deleted.');
    }
}
