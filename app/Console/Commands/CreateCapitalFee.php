<?php

namespace App\Console\Commands;

use App\Http\Controllers\Notification\NotificationBillCreated;
use Illuminate\Console\Command;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateCapitalFee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $signature = 'capital-fee:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command create capital fee notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        info('create notification capital fee Running at ' . now());

        $notification = new NotificationBillCreated();
        $notification->feeRegister();
    }
}
