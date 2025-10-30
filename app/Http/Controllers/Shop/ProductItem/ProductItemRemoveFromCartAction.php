<?php

namespace App\Http\Controllers\Shop\ProductItem;

use App\Models\Shop\Cart\CartItem;
use App\Models\Shop\Product\ProductItem;
use App\Request\ProductItemRemoveFromCartRequest;
use Illuminate\Support\Facades\DB;

class ProductItemRemoveFromCartAction
{
    public function __invoke(ProductItemRemoveFromCartRequest $request)
    {
        if (!$cartItems = CartItem::whereId($request->cart_item_id)->first()) {
            // log error
            return redirect()->route('web.product-item.list');
        }

        DB::transaction(function () use ($cartItems) {
            $items = ProductItem::whereProductId($cartItems->product_item_id)
                ->lockForUpdate()
                ->first();

            $items->amount += 1;
            $items->amount_reserved -= 1;

            $cartItems->amount -= 1;

            $items->save();

            if ($cartItems->amount > 0) {
                $cartItems->save();
            } else {
                $cartItems->delete();
            }
        });

        return back();
    }
}
