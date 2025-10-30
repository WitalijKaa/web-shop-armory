<?php

namespace App\Http\Controllers\Shop\Cart;

use App\Interfaces\CartProviderInterface;
use App\Models\Shop\Product\ProductItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartViewAction
{
    public function __invoke(Request $request, CartProviderInterface $cartProvider)
    {
        $cartReserved = $cartProvider->cartReserved($request)?->loadMissing(['items.productItem.product']);

        if (!$cartProvider->cart()->items->count() && !$cartReserved) {
            // log error
            return redirect()->route('web.product-item.list');
        }

        $models = ProductItem::whereIn('id', $cartProvider->cart()->productsItemsIDs())->with('product')->get();
        $cartProvider->cart()->setInCartAmount($models);

        return Inertia::render('shop/cart', [
          'items' => $models,
          'cart' => $cartProvider->cart(),
          'cart_reserved' => $cartReserved,
        ]);
    }
}
