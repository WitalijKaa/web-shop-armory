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

        $cartItems = $cartProvider->cart()->addToCartByProductID($request->product_id);
        event(new ProductItemAmountChangedEvent($cartItems->product_item_id));

        return back(); //->with('status', __('Product ":name" added to cart.', ['name' => $model->product->name]));
    }
}
