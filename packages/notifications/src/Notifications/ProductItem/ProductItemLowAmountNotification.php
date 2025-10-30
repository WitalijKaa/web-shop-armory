<?php

namespace WebShop\Notifications\Notifications\ProductItem;

use App\Models\Shop\Product\ProductItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use WebShop\Notifications\Channels\ShopBroadcastChannel;
use WebShop\Notifications\Channels\ShopDatabaseChannel;
use WebShop\Notifications\Channels\ShopMailChannel;
use WebShop\Notifications\Notifications\NotificationInterface;
use WebShop\Notifications\Notifications\NotificationLimitInterface;

class ProductItemLowAmountNotification extends Notification implements NotificationInterface, NotificationLimitInterface, ShouldQueue
{
    use Queueable;

    public function __construct(protected readonly ProductItem $productItem, protected readonly int $amount) { }

    public function via(): array
    {
        return [ShopMailChannel::class, ShopBroadcastChannel::class, ShopDatabaseChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $level = $this->level();

        return (new MailMessage)
            ->subject("{$this->productItem->name}: {$level} level")
            ->greeting("Hello!")
            ->line("Stock for \"{$this->productItem->name}\" dropped to the {$level} level.")
            ->line("Available quantity: {$this->amount}.")
            ->line("Please restock as soon as possible.");
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->payload());
    }

    public function toArray(object $notifiable): array
    {
        return $this->payload();
    }

    public function payload(): array
    {
        return [
            'product_item_id' => $this->productItem->id,
            'product_id' => $this->productItem->product_id,
            'product_name' => $this->productItem->product->name,
            'amount' => $this->amount,
        ];
    }

    public function actionID(): string
    {
        return 'low_product_amount_' . $this->productItem->product_id;
    }

    protected function level(): string
    {
        if ($this->amount <= config('shop.product.amount.veryLow')) {
            return 'Critical low';
        }
        return 'Low';
    }

    public function isAllowedToSend(): bool
    {
        if (Cache::get(ShopDatabaseChannel::CACHE_PREFIX . $this->actionID())) {
            return false;
        }

        return !ShopDatabaseChannel::isRecentExists($this, now()->subHours($this->limitRepeatHours()));
    }

    public function limitRepeatHours(): ?int
    {
        return 42;
    }
}
