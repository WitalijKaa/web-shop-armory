<?php

namespace App\Http\Controllers\Shop\ProductItem;

use App\Models\Shop\Product\ProductItem;

class ProductItemListAction
{
    public function __invoke()
    {
        $models = ProductItem::where('amount', '>', 0)->with('product')->get();

        dd($models); // https://armory.local/product/list
    }
}
