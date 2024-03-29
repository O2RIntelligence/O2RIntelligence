<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\HourlyDataUpdate::class,
        Commands\DailyDataUpdate::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //todo: at live server, after enabling google ads module, uncomment these lines
        $schedule->command('hourlyData:update')
            ->everyTenMinutes();
        $schedule->command('dailyData:update')
            ->everyThirtyMinutes();
        $schedule->command('pixalate:update')
            ->everyThirtyMinutes();

//        $schedule->command('pixalate:update')
//            ->dailyAt('07:00')
//            ->timezone('Asia/Jerusalem');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
