<?php

use App\Http\Middleware\CartUnregisteredMiddleware;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'web.', 'middleware' => [CartUnregisteredMiddleware::class]], function() {

    Route::group(['as' => 'product-item.', 'prefix' => 'product'], function() {
        Route::get('list', App\Http\Controllers\Shop\ProductItem\ProductItemListAction::class)->name('list');
        Route::get('cart', App\Http\Controllers\Shop\Cart\CartViewAction::class)->name('cart');

        Route::post('add-to-cart', App\Http\Controllers\Shop\ProductItem\ProductItemAddToCartAction::class)->name('add-to-cart');
        Route::delete('remove-from-cart', App\Http\Controllers\Shop\ProductItem\ProductItemRemoveFromCartAction::class)->name('remove-from-cart')->withoutMiddleware(CartUnregisteredMiddleware::class);
        Route::post('cart-reservation', App\Http\Controllers\Shop\Cart\CartReservationAction::class)->name('cart-reservation');
        Route::post('cart-payment', App\Http\Controllers\Shop\Cart\CartPaymentAction::class)->name('cart-payment');
    });
});

Route::group(['as' => 'admin.'], function() {
    Route::group(['as' => 'product-item.', 'prefix' => 'admin'], function() {
        Route::get('list', App\Http\Controllers\Admin\AdminProductItemListAction::class)->name('list');
    });
});

Route::get('/', function () {
    return redirect()->route('web.product-item.list');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
