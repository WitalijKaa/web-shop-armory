<?php

namespace App\Console\Commands;

use App\Models\Shop\Cart\Cart;
use App\Models\Shop\Cart\CartItem;
use App\Models\Shop\Cart\CartStatusEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class BuyReport extends Command
{
    protected $signature = 'product:report {--sub_days=}';

    protected $description = 'Creates a report of purchased products.';

    private const int CHUNK = 50;

    public function handle()
    {
        $subDays = (int)$this->option('sub_days');

        if ($report = $this->createReportForDay($subDays)) {
            $filePath = $this->saveReportToFile($report);
            echo $filePath . "\n";
        }
    }

    private function createReportForDay(int $subDays): array {
        $from = now()->subDays($subDays)->startOfDay();
        $to = now()->subDays($subDays)->endOfDay();

        $report = [['cart', 'client_uuid', 'cart_total_price', 'product_name', 'product_amount', 'product_total_price', 'product_price_per_item', 'product_current_price']];

        Cart::whereBetween('paid_at', [$from, $to])
            ->whereStatus(CartStatusEnum::paid->value)
            ->with(['items.productItem.product'])
            ->chunk(self::CHUNK, function (Collection $clt) use (&$report) {
                $clt->each(function (Cart $cart) use (&$report) {

                    $report[] = [
                        $cart->id,
                        $cart->client_uuid,
                        $cart->priceReserved,
                    ];

                    $cart->items->each(function (CartItem $cartItem) use (&$report, $cart) {
                        $report[] = [
                            $cart->id,
                            $cart->client_uuid,
                            $cart->priceReserved,
                            $cartItem->productItem->product->name,
                            $cartItem->amount,
                            $cartItem->amount * $cartItem->price,
                            $cartItem->price,
                            $cartItem->productItem->product->price,
                        ];
                    });
                });
            });

        return count($report) > 1 ? $report : [];
    }

    private function saveReportToFile(array $report): string {
        $filePath = storage_path('logs/day_report_' . date('Y-m-d_H-i-s') . '.csv');
        $stream = fopen($filePath, 'w');

        foreach ($report as $line) {
            fputcsv($stream, $line, ';');
        }

        fclose($stream);
        return $filePath;
    }
}
