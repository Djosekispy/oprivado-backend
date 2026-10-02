<?php

namespace App\Console;

use App\Jobs\DeleteMedia;
use App\Jobs\SalesRefund;
use App\Jobs\RebillWallet;
use App\Jobs\PostScheduled;
use App\Jobs\RebillCardinity;
use App\Jobs\ExpiredAdvertising;
use App\Jobs\DeleteInactiveUsers;
use App\Jobs\LiveStreamingPrivateExpired;
use App\Jobs\ExpireProUsers;
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
        //
    ];
	
	 /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
	 protected function schedule(Schedule $schedule)
    {
        // ❌ REMOVIDO: queue:work (isso estava a causar o problema)
		//  $schedule->command('queue:work --tries=3 --timeout=8600')
       //     ->cron('* * * * *')
         //   ->withoutOverlapping();

        // Limpeza de cache semanal
        $schedule->command('cache:clear')
            ->weekly()
            ->withoutOverlapping();
		 
		 $schedule->command('featured:reset')
             ->dailyAt('08:00');

        // Jobs
        $schedule->job(new DeleteMedia)->everySixHours();
        $schedule->job(new RebillWallet)->hourly();
        $schedule->job(new RebillCardinity)->hourly();
        $schedule->job(new PostScheduled)->everyMinute();
        $schedule->job(new SalesRefund)->daily();
        $schedule->job(new DeleteInactiveUsers)->everySixHours();
        $schedule->job(new ExpiredAdvertising)->hourly();
        $schedule->job(new ExpireProUsers)->hourly();
        $schedule->job(new LiveStreamingPrivateExpired)->everySixHours();
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

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