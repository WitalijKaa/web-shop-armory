<?php

namespace App\Http\Controllers\Shop\Cart;

use App\Interfaces\CartProviderInterface;
use App\Models\Shop\Cart\CartItem;
use App\Models\Shop\Cart\CartStatusEnum;
use Illuminate\Http\Request;
use WebShop\Notifications\Events\ProductItemAmountChangedEvent;

class CartPaymentAction
{
    public function __invoke(CartProviderInterface $cartProvider)
    {
        if (!$cartReserved = $cartProvider->cartReserved()) {
            // log error
            return redirect()->route('web.product-item.list');
        }

        $cartReserved->status = CartStatusEnum::paid;
        $cartReserved->save();

        $cartReserved->items->each(function (CartItem $model) {
            //event(new ProductItemAmountChangedEvent($model->product_item_id));
        });

        return redirect()->route('web.product-item.list');
    }
}
