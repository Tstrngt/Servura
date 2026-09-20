<?php

namespace App\Console;

use App\Services\DunningService;
use App\Services\RenewalService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(fn () => app(RenewalService::class)->run())
            ->name('billing:generate-renewals')
            ->dailyAt('02:00')
            ->withoutOverlapping();
        $schedule->call(fn () => app(DunningService::class)->run())
            ->name('billing:process-dunning')
            ->dailyAt('03:00')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
