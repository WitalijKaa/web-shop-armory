<?php

namespace WebShop\Notifications\Events;

use Illuminate\Queue\SerializesModels;

class ProductItemAmountChangedEvent
{
    use SerializesModels;

    public function __construct(public int $productItemID)
    {
    }
}
