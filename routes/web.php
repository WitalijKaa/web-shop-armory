<?php

use App\Http\Middleware\CartUnregisteredMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['as' => 'web.', 'middleware' => [CartUnregisteredMiddleware::class]], function() {

    Route::group(['as' => 'product-item.', 'prefix' => 'product'], function() {
        Route::get('list', App\Http\Controllers\Shop\ProductItem\ProductItemListAction::class)->name('list');
        Route::post('add-to-cart', App\Http\Controllers\Shop\ProductItem\ProductItemAddToCartAction::class)->name('add-to-cart');
    });
});




Route::get('/', function () {
    return redirect()->route('web.product-item.list');

    // return Inertia::render('welcome', [
    //     'canRegister' => Features::enabled(Features::registration()),
    // ]);
});

/*
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});
*/

require __DIR__.'/settings.php';
