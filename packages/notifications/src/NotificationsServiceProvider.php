<?php

namespace WebShop\Notifications;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use WebShop\Notifications\Events\ProductItemAmountChangedEvent;
use WebShop\Notifications\Listeners\ProductItemLowAmountDetectListener;

class NotificationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
    }

    public function boot(): void
    {
        Event::listen(ProductItemAmountChangedEvent::class, ProductItemLowAmountDetectListener::class);
    }
}
