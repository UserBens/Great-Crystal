<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailController;
use App\Http\Controllers\Notification\NotificationPastDue;
use Illuminate\Console\Command;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReminderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $signature = 'cronReminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder past due';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        info("Reminder past due Job running at ". now());

        $bill = new NotificationPastDue;
        $bill->cronChargePastDue('SPP');
        $bill->cronChargePastDue('Capital Fee');
        $bill->cronChargePastDue('Paket');
        $bill->cronChargePastDue('Book');
        $bill->cronChargePastDue('Uniform');
        $bill->cronChargePastDue('etc');
    }
}