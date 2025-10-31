<?php

namespace App\Http\Controllers\Shop\ProductItem;

use App\Interfaces\CartProviderInterface;
use App\Models\Shop\Product\ProductItem;
use Inertia\Inertia;

class ProductItemListAction
{
    public function __invoke(CartProviderInterface $cartProvider)
    {
        $models = ProductItem::where('amount', '>', 0)
            ->orWhereIn('id', $cartProvider->cart()->productsItemsIDs())
            ->with('product')->get();
        $cartProvider->cart()->setInCartAmount($models);

        return Inertia::render('shop/list', [
          'items' => $models,
          'cart_uuid' => $cartProvider->cart()->items->count() || $cartProvider->cartReserved() ? $cartProvider->cart()->client_uuid : null,
        ]);
    }
}
