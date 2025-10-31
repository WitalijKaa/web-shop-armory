<?php

namespace WebShop\Notifications\Notifications;

interface NotificationInterface {

    public function payload(): array;
    public function actionID(): ?string;

}