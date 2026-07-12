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
        // $schedule->command('inspire')->hourly();
        // Backup otomatis tiap hari jam 01:00, lalu cleanup (retensi 1 minggu)
        // dijalankan setelahnya di jam 02:00 agar tidak tumpang tindih.
        $schedule->command('backup:run')->dailyAt('01:00')->withoutOverlapping();
        $schedule->command('backup:cleanup')->dailyAt('02:00');
        $schedule->command('media:sync')->hourly();
        $schedule->command('publikasi:publish-scheduled')->everyMinute();

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
