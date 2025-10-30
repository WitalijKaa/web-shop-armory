<?php

namespace WebShop\Notifications\Listeners;

use App\Models\Shop\Product\ProductItem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use WebShop\Notifications\Events\ProductItemAmountChangedEvent;
use WebShop\Notifications\Notifications\NotificationLimitInterface;
use WebShop\Notifications\Notifications\ProductItem\ProductItemLowAmountNotification;
use WebShop\Notifications\Notifications\ProductItem\ProductItemVeryLowAmountNotification;

class ProductItemLowAmountDetectListener
{
    use SerializesModels;

    public function handle(ProductItemAmountChangedEvent $event)
    {
        if (config('shop.product.amount.low') < ($amount = (int)ProductItem::whereProductId($event->productItemID)->sum('amount'))) {
            return;
        }

        $model = ProductItem::whereProductId($event->productItemID)->first();

        if ($amount <= config('shop.product.amount.veryLow')) {
            $notification = new ProductItemVeryLowAmountNotification($model, $amount);
        }
        else if ($amount <= config('shop.product.amount.low')) {
            $notification = new ProductItemLowAmountNotification($model, $amount);
        }

        if (!empty($notification) && 
            (!$notification instanceof NotificationLimitInterface || $notification->isAllowedToSend())
        ) {
            Log::channel('dev_log')->info(static::class, ['amount' => $amount, 'action_id' => $notification->actionID()]);

            $fakeNotifiable = Notification::route('mail', config('notifications.admin.email'))
                ->route('broadcast', config('notifications.admin.broadcast_channel'));

            $fakeNotifiable->notify($notification);
        }
    }
}
