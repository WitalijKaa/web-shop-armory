<?php

namespace App\Http\Controllers\Shop\ProductItem;

use App\Models\Shop\Cart\CartItem;
use App\Request\ProductItemRemoveFromCartRequest;

class ProductItemRemoveFromCartAction
{
    public function __invoke(ProductItemRemoveFromCartRequest $request)
    {
        if (!$cartItems = CartItem::whereId($request->cart_item_id)->first()) {
            // log error
            return redirect()->route('web.product-item.list');
        }

        $cartItems->removeFromCart();

        return back();
    }
}
