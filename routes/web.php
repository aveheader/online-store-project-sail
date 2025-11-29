<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\FakeGatewayController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Главная — список товаров
Route::get('/', [ProductController::class, 'index'])->name('products.index');

// Товары
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Категории
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Авторизация
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Редактирование профиля (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Корзина
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'delete'])->name('cart.remove');

    // API для AJAX (используем веб-роуты для работы с сессиями)
    Route::prefix('api/cart')->group(function () {
        Route::post('/add', [\App\Http\Controllers\Api\CartController::class, 'add'])->name('api.cart.add');
        Route::delete('/remove', [\App\Http\Controllers\Api\CartController::class, 'remove'])->name('api.cart.remove');
        Route::put('/update', [\App\Http\Controllers\Api\CartController::class, 'updateQuantity'])->name('api.cart.update');
        Route::get('/count', [\App\Http\Controllers\Api\CartController::class, 'getCount'])->name('api.cart.count');
        Route::get('/state', [\App\Http\Controllers\Api\CartController::class, 'getCartState'])->name('api.cart.state');
    });

    //Оформление заказа
    Route::middleware('auth')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });

    //Создание платежа
    Route::get('/payment/start/{order}', [PaymentController::class, 'start'])
        ->middleware('order.owner')
        ->name('payment.start');

    //Фейковый шлюз оплаты
    Route::get('/fake-gateway/{payment}', [FakeGatewayController::class, 'show'])
        ->middleware(['payment.owner', 'payment.pending'])
        ->name('fake-gateway');

    Route::post('/fake-gateway/{payment}/process', [FakeGatewayController::class, 'process'])
        ->middleware(['payment.owner', 'payment.pending'])
        ->name('fake-gateway.process');
});

require __DIR__.'/auth.php';
