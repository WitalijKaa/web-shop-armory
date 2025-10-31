<?php

namespace App\Console\Commands;

use App\Models\Shop\Cart\Cart;
use App\Models\Shop\Cart\CartItem;
use App\Models\Shop\Cart\CartStatusEnum;
use Illuminate\Console\Command;
use Illuminate\Mail\Message;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class BuyReport extends Command
{
    protected $signature = 'product:report {--sub_days=} {--send_email=}';

    protected $description = 'Creates a report of purchased products.';

    private const int CHUNK = 50;

    public function handle()
    {
        $subDays = (int)$this->option('sub_days');
        $sendEmail = $this->option('send_email');

        if ($report = $this->createReportForDay($subDays)) {
            $filePath = $this->saveReportToFile($report);
            
            if ($sendEmail) {
                $this->sendEmailWithReport($filePath, $sendEmail);
            }
        }
    }

    private function createReportForDay(int $subDays): array {
        $from = now()->subDays($subDays)->startOfDay();
        $to = now()->subDays($subDays)->endOfDay();

        $report = [['cart', 'time', 'client_uuid', 'cart_total_price', 'product_name', 'product_amount', 'product_total_price', 'product_price_per_item', 'product_current_price']];

        Cart::whereBetween('paid_at', [$from, $to])
            ->whereStatus(CartStatusEnum::paid->value)
            ->with(['items.productItem.product'])
            ->chunk(self::CHUNK, function (Collection $clt) use (&$report) {
                $clt->each(function (Cart $cart) use (&$report) {

                    $report[] = [
                        $cart->id,
                        $cart->paid_at->format('Y-m-d H:i:s'),
                        $cart->client_uuid,
                        $cart->priceReserved,
                    ];

                    $cart->items->each(function (CartItem $cartItem) use (&$report, $cart) {
                        $report[] = [
                            $cart->id,
                            $cart->paid_at->format('Y-m-d H:i:s'),
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

    private function sendEmailWithReport(string $reportFilePath, string $email): void {
        Mail::raw(
            'The daily report on sold products is attached.',
            function (Message $message) use ($email, $reportFilePath) {
                $message->to($email)
                    ->subject('Daily report on sold products')
                    ->attach($reportFilePath, [
                        'as' => basename($reportFilePath),
                        'mime' => 'text/csv',
                    ]);
            }
        );
    }
}
