<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use  App\Http\Controllers\SilverToolController;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $update_successfully = (new SilverToolController)->update_gestiones_from_silvertool( true );
            if ( $update_successfully ) {
                info("GESTIONES ACTUALIZADAS CORRECTAMENTE");
            } else {
                info("LAS GESTIONES NO SE HAN ACTUALIZADO");
            }
        })
        ->hourly();
        // ->everyMinute();
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
