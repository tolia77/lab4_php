<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReviewStoreRequest;
use App\Http\Requests\ReviewUpdateRequest;

class ReviewController extends Controller
{
    public function store(ReviewStoreRequest $request, Product $product)
    {
        $user = Auth::user();
        if (!$user || !$user->customer) {
            return redirect()->back()->with('error', 'You must be logged in as a customer to post a review.');
        }

        $customerId = $user->customer->id;

        $already = Review::where('product_id', $product->id)
            ->where('customer_id', $customerId)
            ->exists();

        if ($already) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        $validated = $request->validated();

        Review::create([
            'product_id' => $product->id,
            'customer_id' => $customerId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Review submitted.');
    }

    public function index()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $reviews = Review::with(['product', 'customer'])->orderBy('created_at', 'desc')->get();
        return view('reviews.index', compact('reviews'));
    }

    public function edit(Review $review)
    {
        $user = Auth::user();
        if (!$user || (! $user->isAdmin() && (! $user->customer || $user->customer->id !== $review->customer_id))) {
            abort(403);
        }
        return view('reviews.edit', compact('review'));
    }

    public function update(ReviewUpdateRequest $request, Review $review)
    {
        $user = Auth::user();
        if (!$user || (! $user->isAdmin() && (! $user->customer || $user->customer->id !== $review->customer_id))) {
            abort(403);
        }

        $validated = $request->validated();

        $review->update($validated);

        return redirect()->route('products.show', $review->product_id)->with('success', 'Review updated.');
    }

    public function destroy(Review $review)
    {
        $user = Auth::user();
        if (!$user || (! $user->isAdmin() && (! $user->customer || $user->customer->id !== $review->customer_id))) {
            abort(403);
        }

        $review->delete();
        return redirect()->back()->with('success', 'Review deleted.');
    }
}
