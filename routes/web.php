<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public order route - allows both authenticated and guest users to place orders
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

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
