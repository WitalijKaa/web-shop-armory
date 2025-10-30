<?php

namespace WebShop\Notifications;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Factory as MailFactory;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Channels\BroadcastChannel;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use WebShop\Notifications\Channels\ShopBroadcastChannel;
use WebShop\Notifications\Channels\ShopDatabaseChannel;
use WebShop\Notifications\Channels\ShopMailChannel;
use WebShop\Notifications\Events\ProductItemAmountChangedEvent;
use WebShop\Notifications\Listeners\ProductItemLowAmountDetectListener;

class NotificationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MailChannel::class, function ($app) {
            return new ShopMailChannel($app->make(MailFactory::class), $app->make(Markdown::class));
        });

        $this->app->bind(DatabaseChannel::class, static function () {
            return new ShopDatabaseChannel();
        });

        $this->app->bind(BroadcastChannel::class, function ($app) {
            return new ShopBroadcastChannel($app->make(Dispatcher::class));
        });
    }

    public function boot(): void
    {
        Event::listen(ProductItemAmountChangedEvent::class, ProductItemLowAmountDetectListener::class);
    }
}
