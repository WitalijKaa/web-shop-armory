<?php

namespace App\Providers;

use App\Interfaces\CartProviderInterface;
use App\Services\CartUnregisteredService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(CartProviderInterface::class, function () {
            return new CartUnregisteredService();
        });
    }

    public function boot(): void
    {
        //
    }
}
