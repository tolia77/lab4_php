<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Models\User;
use Illuminate\Http\Request;
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


Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('products.reviews.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', function () {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            abort(403);
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

        Route::get('/users', function () {
            $user = auth()->user();
            if (!$user || !$user->isAdmin()) {
                abort(403);
            }
            $users = User::paginate(20);
            return view('admin.users.index', compact('users'));
        })->name('users.index');

        Route::get('/users/{user}/edit', function (User $user) {
            $auth = auth()->user();
            if (!$auth || !$auth->isAdmin()) {
                abort(403);
            }
            return view('admin.users.edit', compact('user'));
        })->name('users.edit');

        Route::patch('/users/{user}', function (Request $request, User $user) {
            $auth = auth()->user();
            if (!$auth || !$auth->isAdmin()) {
                abort(403);
            }
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
            ]);
            $user->update($data);
            return redirect()->route('admin.users.index');
        })->name('users.update');

        Route::delete('/users/{user}', function (User $user) {
            $auth = auth()->user();
            if (!$auth || !$auth->isAdmin()) {
                abort(403);
            }
            $user->delete();
            return redirect()->route('admin.users.index');
        })->name('users.destroy');
    });
});

Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('reviews', ReviewController::class)->only(['edit', 'update', 'destroy']);
require __DIR__ . '/auth.php';
