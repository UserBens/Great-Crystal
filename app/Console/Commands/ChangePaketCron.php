<?php

namespace App\Console\Commands;

use App\Http\Controllers\Notification\NotificationBillCreated;
use Illuminate\Console\Command;

class ChangePaketCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change-paket:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create notification change bill for parents';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        info('create notification change paket Running at '. now());
        $notification = new NotificationBillCreated;
        $notification->changePaket();
    }
}
