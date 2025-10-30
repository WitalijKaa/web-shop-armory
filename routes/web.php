<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::group(['as' => 'web.', /* 'middleware' => ['web-auth'] */], function() {

    Route::group(['as' => 'product-item.', 'prefix' => 'product'], function() {
        Route::get('list', App\Http\Controllers\Shop\ProductItem\ProductItemListAction::class)->name('list');
    });
});




Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
