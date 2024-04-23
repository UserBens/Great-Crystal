<?php

namespace App\Console\Commands;

use App\Http\Controllers\Notification\NotificationBillCreated;
use Illuminate\Console\Command;

class CreateOtherBillCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'other:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'NOtification create bill orthers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('create notification other Running at '. now());

        $notification = new NotificationBillCreated;
        $notification->etc();
    }
}
