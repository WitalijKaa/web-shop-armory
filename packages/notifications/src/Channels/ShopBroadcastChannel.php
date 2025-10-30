<?php

namespace WebShop\Notifications\Channels;

use Illuminate\Notifications\Channels\BroadcastChannel as BaseBroadcastChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ShopBroadcastChannel extends BaseBroadcastChannel
{
    public function send($notifiable, Notification $notification)
    {
        Log::channel('dev_log')->debug('ShopBroadcastChannel', ['notifiable' => $notifiable, 'notification' => $notification]);

        return parent::send($notifiable, $notification);
    }
}

