<?php

namespace WebShop\Notifications\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel as BaseDatabaseChannel;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use WebShop\Notifications\Models\Notification;
use WebShop\Notifications\Notifications\NotificationInterface;
use WebShop\Notifications\Notifications\NotificationLimitInterface;
use WebShop\Notifications\Notifications\ProductItem\ProductItemLowAmountNotification;
use WebShop\Notifications\Notifications\ProductItem\ProductItemVeryLowAmountNotification;

class ShopDatabaseChannel extends BaseDatabaseChannel
{
    public const string CACHE_PREFIX = 'Notif_';

    public const array TYPES = [
        ProductItemLowAmountNotification::class => 1,
        ProductItemVeryLowAmountNotification::class => 2,
    ];

    public function send($notifiable, BaseNotification $notification)
    {
        /** @var \WebShop\Notifications\Notifications\NotificationInterface $notification */

        Log::channel('dev_log')->debug('ShopDatabaseChannel', ['notifiable' => $notifiable, 'notification' => $notification]);

        $model = new Notification();
        $model->uuid = $notification->id;
        $model->type = self::TYPES[$notification::class];
        $model->payload = ['basic' => $notification->payload()];
        $model->action_id = $notification->actionID();
        $model->save();

        if ($notification instanceof NotificationLimitInterface && ($cacheHours = $notification->limitRepeatHours())) {
            Cache::set(self::CACHE_PREFIX . $notification->actionID(), '1', now()->addHours($cacheHours));
        }
    }

    public static function isRecentExists(NotificationInterface $notification, Carbon $recentTime): bool
    {
        /** @var \WebShop\Notifications\Notifications\NotificationInterface $notification */

        if (Cache::get(self::CACHE_PREFIX . $notification->actionID())) {
            return true;
        }

        $model = Notification::whereType(self::TYPES[$notification::class])
            ->whereActionId($notification->actionID())
            ->where('created_at', '>', $recentTime)
            ->select(['action_id', 'created_at'])
            ->first();

        if (!$model) {
            return false;
        }

        if ($notification instanceof NotificationLimitInterface && ($cacheHours = $notification->limitRepeatHours())) {
            Cache::set(self::CACHE_PREFIX . $notification->actionID(), '1', $model->created_at->addHours($cacheHours));
        }

        return true;
    }
}

