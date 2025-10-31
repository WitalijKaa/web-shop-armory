<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop\Cart\Cart;
use App\Models\Shop\Cart\CartStatusEnum;
use App\Models\Shop\Product\ProductItem;
use Inertia\Inertia;

class AdminProductItemListAction
{
    public function __invoke()
    {
        return Inertia::render('admin/list', [
          'products' => ProductItem::with('product')->get(),

          'carts' => Cart::whereStatus(CartStatusEnum::paid->value)
            ->with(['items'])
            ->orderByDesc('id')->limit(100)->get()
            ->each(function (Cart $model) { $model->append('price_reserved'); }),
        ]);
    }
}
