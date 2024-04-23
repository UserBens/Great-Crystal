<?php

namespace App\Console\Commands;

use App\Http\Controllers\Notification\NotificationPaymentSuccess;
use Illuminate\Console\Command;

class PaymentSuccessCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command send notification payment success';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notification = new NotificationPaymentSuccess;
        $notification->paymentSuccess('SPP');
        $notification->paymentSuccess('Paket');
        $notification->paymentSuccess('Capital Fee');
        $notification->paymentSuccess('Book');
        $notification->paymentSuccess('Uniform');
        $notification->paymentSuccess('etc');
    }
}
