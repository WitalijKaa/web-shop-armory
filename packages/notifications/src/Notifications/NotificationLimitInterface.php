<?php

namespace WebShop\Notifications\Notifications;

interface NotificationLimitInterface {

    public function isAllowedToSend(): bool;
    public function limitRepeatHours(): ?int;

}