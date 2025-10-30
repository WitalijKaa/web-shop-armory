<?php

namespace App\Http\Controllers\Shop\ProductItem;

use App\Interfaces\CartProviderInterface;
use App\Models\Shop\Product\ProductItem;
use App\Request\ProductItemAddToCartRequest;
use WebShop\Notifications\Events\ProductItemAmountChangedEvent;

class ProductItemAddToCartAction
{
    public function __invoke(ProductItemAddToCartRequest $request, CartProviderInterface $cartProvider)
    {
        if (!$model = ProductItem::whereProductId($request->product_id)->first()) {
            // log error
            return redirect()->route('web.product-item.list');
        }

        $cartProvider->cart()->addToCartByProductID($request->product_id);

        return back();
    }
}
