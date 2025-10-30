<?php

namespace App\Http\Controllers\Shop\ProductItem;

use App\Interfaces\CartProviderInterface;
use App\Models\Shop\Product\ProductItem;
use Inertia\Inertia;

class ProductItemListAction
{
    public function __invoke(CartProviderInterface $cartProvider)
    {
        $models = ProductItem::where('amount', '>', 0)->with('product')->get();

        return Inertia::render('shop/list', [
          'items' => $models,
          'cart' => $cartProvider->cart(),
        ]);
    }
}
