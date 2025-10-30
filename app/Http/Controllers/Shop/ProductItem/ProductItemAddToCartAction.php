<?php

namespace App\Http\Controllers\Shop\ProductItem;

use App\Models\Shop\Product\ProductItem;
use App\Request\ProductItemAddToCartRequest;
use Inertia\Inertia;

class ProductItemAddToCartAction
{
    public function __invoke(ProductItemAddToCartRequest $request)
    {
        if (!$model = ProductItem::whereProductId($request->product_id)->first()) {
            // log error
            return redirect()->route('web.product-item.list');
        }

        return back()->with('status', __('Product ":name" added to cart.', ['name' => $model->product->name]));
    }
}
