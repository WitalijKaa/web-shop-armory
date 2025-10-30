<?php

namespace App\Http\Middleware;

use App\Interfaces\CartProviderInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartUnregisteredMiddleware
{
    public function __construct(private CartProviderInterface $cartProvider) {}

    public function handle(Request $request, Closure $next, ): Response
    {
        $this->cartProvider->initCart($request);
        return $next($request);
    }
}