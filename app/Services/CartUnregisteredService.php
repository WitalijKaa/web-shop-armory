<?php

namespace App\Services;

use App\Interfaces\CartProviderInterface;
use App\Models\Shop\Cart\Cart;
use App\Models\Shop\Cart\CartStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class CartUnregisteredService implements CartProviderInterface
{
    public const string COOKIE_NAME = 'cart_unregistered';
    private const int COOKIE_TTL_MINUTES = 60 * 24 * 180; // 180 days

    private Cart $cart;

    public function cart(): Cart {
        return $this->cart;
    }

    public function initCart(Request $request): void
    {
        $uuid = $request->cookie(self::COOKIE_NAME) ?? Str::uuid()->toString();

        $this->cart = Cart::whereClientUuid($uuid)
            ->whereStatus(CartStatusEnum::potential)
            ->with(['items'])
            ->first() ?? new Cart();

        $this->cart->client_uuid = $uuid;
        $this->cart->status = CartStatusEnum::potential;

        Cookie::queue(Cookie::make(
            name: self::COOKIE_NAME,
            value: $uuid,
            minutes: self::COOKIE_TTL_MINUTES,
        ));
    }
}
