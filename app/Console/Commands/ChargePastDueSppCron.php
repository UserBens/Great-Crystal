<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailController;
use App\Http\Controllers\Notification\NotificationPastDue;
use Illuminate\Console\Command;
use Exception;


use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChargePastDueSppCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $signature = 'charge_bill:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Charge past due';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Reminder past due charge Job running at ". now());

        $bill = new NotificationPastDue;
        $bill->cronChargePastDue('SPP', true);
        $bill->cronChargePastDue('Capital Fee', true);
        $bill->cronChargePastDue('Paket');
        $bill->cronChargePastDue('Book');
        $bill->cronChargePastDue('Uniform');
        $bill->cronChargePastDue('etc');
    }
}