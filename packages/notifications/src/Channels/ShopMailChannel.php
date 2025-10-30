<?php

namespace WebShop\Notifications\Channels;

use Illuminate\Notifications\Channels\MailChannel as BaseMailChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ShopMailChannel extends BaseMailChannel
{
    public function send($notifiable, Notification $notification)
    {
        Log::channel('dev_log')->debug('ShopMailChannel', ['notifiable' => $notifiable, 'notification' => $notification]);

        return parent::send($notifiable, $notification);
    }
}

