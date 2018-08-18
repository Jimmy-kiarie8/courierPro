<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ScheduledCommand;
use agoalofalife\Reports\Console\ParseReportsCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

    //This is the line of code added, at the end, we the have class name of ScheduledCommand.php inside app\console\commands
        '\App\Console\Commands\ScheduledCommand',
        '\App\Console\Commands\ReportCommand',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //insert name and signature of you command and define the time of excusion
        $schedule->command('notifications:ScheduledCommand')
                 ->everyMinute();
        $schedule->command('Mailreports:ReportMail')
                ->everyMinute();
        $schedule->command(ParseReportsCommand::class)->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
