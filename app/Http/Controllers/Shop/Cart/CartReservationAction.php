<?php

namespace App\Http\Controllers\Shop\Cart;

use App\Interfaces\CartProviderInterface;
use App\Models\Shop\Cart\CartItem;
use WebShop\Notifications\Events\ProductItemAmountChangedEvent;

class CartReservationAction
{
    public function __invoke(CartProviderInterface $cartProvider)
    {
        $cartProvider->cart()->reserveCartItems();

        $cartProvider->cart()->items->each(function (CartItem $model) {
            event(new ProductItemAmountChangedEvent($model->product_item_id));
        });

        return back();
    }
}
