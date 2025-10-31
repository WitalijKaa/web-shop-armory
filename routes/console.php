<?php

use App\Console\Commands\BuyReport;
use Illuminate\Support\Facades\Schedule;
 
Schedule::command(BuyReport::class, ['sub_days' => 1, 'send_email' => config('notifications.admin.email')])->dailyAt('22:01');
