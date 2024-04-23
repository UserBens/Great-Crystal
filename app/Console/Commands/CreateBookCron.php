<?php

namespace App\Console\Commands;

use App\Http\Controllers\Notification\NotificationBillCreated;
use Illuminate\Console\Command;

class CreateBookCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command create bill book';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('create notification book Running at '. now());
        
        $notification = new NotificationBillCreated;
        $notification->book();
    }
}
