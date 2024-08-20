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
        date_default_timezone_set('Asia/Jakarta');
        $email_logging = env("EMAIL_CRON_LOGGING", "benedictusradyan@gmail.com");

        // create bill notification
       
        $schedule->command('spp:cron')->monthlyOn(1, '06:35')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);
       
        // $schedule->command('capital-fee:cron')->dailyAt('14:52')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);
        $schedule->command('capital-fee:cron')->dailyAt('14:05')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);
       
        $schedule->command('book:cron')->dailyAt('10:19')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);
        // $schedule->command('book:cron')->dailyAt('13:53')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);
       
        $schedule->command('uniform:cron')->dailyAt('15:06')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);
       
        $schedule->command('paket:cron')->dailyAt('15:21')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);
       
        $schedule->command('change-paket:cron')->dailyAt('08:00')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);
        // $schedule->command('other:cron')->dailyAt('15:20')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);
        $schedule->command('other:cron')->dailyAt('08:42')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);

        // every day at 11 th monthly
        $schedule->command('charge_bill:cron')->monthlyOn(14, '09:07')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);

        // every day at 18 & 25 monthly
        $schedule->command('cronReminder:cron')->twiceMonthly(18, 25, '06:30')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);

        // send email payment success eveyday
        $schedule->command('payment:cron')->dailyAt('05:15')->timezone('Asia/Jakarta')->emailOutputOnFailure($email_logging);
        
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