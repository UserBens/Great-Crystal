<?php

namespace App\Console\Commands;

use App\Http\Controllers\Notification\NotificationBillCreated;
use Illuminate\Console\Command;

class CreatePaketCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paket:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notification = new NotificationBillCreated;
        $notification->paket();
    }
}
