<?php

namespace App\Http\Controllers\Shop\Cart;

use App\Interfaces\CartProviderInterface;
use App\Models\Shop\Product\ProductItem;
use Inertia\Inertia;

class CartViewAction
{
    public function __invoke(CartProviderInterface $cartProvider)
    {
        $cartReserved = $cartProvider->cartReserved()?->loadMissing(['items.productItem.product'])
            ->append(['mayReserve', 'mayPay', 'priceReserved']);

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
