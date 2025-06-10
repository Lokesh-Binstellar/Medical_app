<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands.
     */
    protected $commands = [
        \App\Console\Commands\DeleteQuote::class,
        \App\Console\Commands\FireMyEventCommand::class, // ← Make sure to include this
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run quote deletion every minute
        $schedule->command('delete:quote')->everyMinute();

        // Run quote request event every minute
        $schedule->command('auto:request-quotes')->everyMinute();

        // Log test every minute
        $schedule->call(function () {
            \Log::info('✅ Schedule running: inline closure fired.');
        })->everyMinute();
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
