<?php

namespace App\Console\Commands;

use App\Http\Controllers\Notification\NotificationBillCreated;
use Illuminate\Console\Command;

class CreateUniformCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uniform:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command create uniform notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        info('create notification uniform Running at '. now());

        $notification = new NotificationBillCreated;
        $notification->uniform();
    }
}
