<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\SendAbandonedCartEmails;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\StockCron::class,
    ];


    protected function schedule(Schedule $schedule)
    {
        $schedule->command('stock:cron')->dailyAt('00:00');
        $schedule->call(new SendAbandonedCartEmails)->daily();
    }


    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
