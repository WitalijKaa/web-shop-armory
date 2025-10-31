<?php

namespace App\Http\Controllers\Shop\Cart;

use App\Interfaces\CartProviderInterface;

class CartPaymentAction
{
    public function __invoke(CartProviderInterface $cartProvider)
    {
        if (!$cartReserved = $cartProvider->cartReserved()) {
            // log error
            return redirect()->route('web.product-item.list');
        }

        $cartReserved->payCartItems();

        return redirect()->route('web.product-item.list');
    }
}
