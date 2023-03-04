<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\DossierEvaluationController;

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
        $schedule->command('peru:update_eval_day_count')->everyMinute();  //daily defaults to midnight, or use dailyAt('13:00')
        $schedule->command('peru:nmfa_units_report_reminder')->daily();
        $schedule->command('peru:qc_report_reminder')->daily();
        $schedule->command('peru:applicant_query_response_reminder')->everyMinute();

        $schedule->command('peru:update_application_daycount')->everyMinute();
        $schedule->command('peru:update_application_payment_daycount')->everyMinute();
        $schedule->command('peru:preliminary_screening_daycount')->daily();
        //daily defaults to midnight, or use dailyAt('13:00')
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
