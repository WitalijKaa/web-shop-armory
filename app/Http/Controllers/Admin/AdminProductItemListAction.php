<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop\Product\ProductItem;
use Inertia\Inertia;

class AdminProductItemListAction
{
    public function __invoke()
    {
        $models = ProductItem::with('product')->get();

        return Inertia::render('admin/list', [
          'items' => $models,
        ]);
    }
}
