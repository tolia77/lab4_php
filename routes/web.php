<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController; // added import
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Cart routes - public access for all users
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

// Order routes - public order creation, authenticated viewing
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/confirmation/{order}', [OrderController::class, 'confirmation'])->name('orders.confirmation');
Route::get('/orders', [OrderController::class, 'index'])->middleware('auth')->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    // Review routes:
    // - store (authenticated customers) — named so blade's route('products.reviews.store', $product) resolves
    // - admin resource routes for index/edit/update/destroy (controller enforces isAdmin)
    Route::post('products/{product}/reviews', [ReviewController::class, 'store'])->name('products.reviews.store');
    Route::resource('reviews', ReviewController::class)->only(['index','edit','update','destroy']);

    // Admin dashboard route (named) — checks the user's role at runtime and returns a simple view.
    Route::get('/admin', function () {
        $user = auth()->user();
        if (! $user || ! $user->isAdmin()) {
            abort(403);
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php';
