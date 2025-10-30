<?php

namespace App\Http\Controllers\Shop\Cart;

use App\Interfaces\CartProviderInterface;
use App\Models\Shop\Product\ProductItem;
use App\Request\ProductItemAddToCartRequest;
use Inertia\Inertia;

class CartViewAction
{
    public function __invoke(CartProviderInterface $cartProvider)
    {
        if (!$cartProvider->cart()->items->count()) {
            // log error
            return redirect()->route('web.product-item.list');
        }

        $models = ProductItem::whereIn('id', $cartProvider->cart()->productsItemsIDs())->with('product')->get();
        $cartProvider->cart()->setInCartAmount($models);

        return Inertia::render('shop/cart', [
          'items' => $models,
          'cart_uuid' => $cartProvider->cart()->client_uuid,
        ]);
    }
}
