<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule the contract expiration command to run daily (non-interactive)
        $schedule->command('contracts:expire --no-interaction --force')->daily();
        
        // Cache dashboard statistics every hour for better performance
        $schedule->command('dashboard:cache')->hourly();
        
        // Clear expired cache entries daily
        $schedule->command('cache:prune-stale-tags')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
} 