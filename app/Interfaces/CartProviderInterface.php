<?php

namespace App\Interfaces;

use App\Models\Shop\Cart\Cart;
use Illuminate\Http\Request;

interface CartProviderInterface
{
    public function initCart(Request $request): void;
    public function cart(): Cart;
    public function cartReserved(Request $request): ?Cart;
}
