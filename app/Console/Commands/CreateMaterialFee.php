<?php

namespace App\Console\Commands;

use App\Http\Controllers\Notification\NotificationBillCreated;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateMaterialFee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'material-fee:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command create material fee notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Material Fee Notification Running at ' . now());

        $notification = new NotificationBillCreated();
        $notification->materialFee();

        Log::info('Material Fee Notification Completed at ' . now());
    }
}
