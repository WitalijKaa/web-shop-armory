<?php

namespace App\Http\Controllers\Shop\ProductItem;

use App\Interfaces\CartProviderInterface;
use App\Models\Shop\Product\ProductItem;
use App\Request\ProductItemAddToCartRequest;
use Inertia\Inertia;

class ProductItemAddToCartAction
{
    public function __invoke(ProductItemAddToCartRequest $request, CartProviderInterface $cartProvider)
    {
        if (!$model = ProductItem::whereProductId($request->product_id)->first()) {
            // log error
            return redirect()->route('web.product-item.list');
        }

        $cartProvider->cart()->addToCartByProductID($request->product_id);

        return back()->with('status', __('Product ":name" added to cart.', ['name' => $model->product->name]));
    }
}
