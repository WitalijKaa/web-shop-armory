<?php

namespace WebShop\Notifications\Listeners;

use App\Models\Shop\Product\ProductItem;
use Illuminate\Queue\SerializesModels;
use WebShop\Notifications\Events\ProductItemAmountChangedEvent;

class ProductItemLowAmountDetectListener
{
    use SerializesModels;

    public function handle(ProductItemAmountChangedEvent $event)
    {
        $amount = (int)ProductItem::whereProductId($event->productItemID)->sum('amount');

        if ($amount <= config('shop.product.amount.veryLow')) {
            // dd('veryLow', $amount);
        }
        else if ($amount <= config('shop.product.amount.low')) {
            // dd('low', $amount);
        }
    }
}
