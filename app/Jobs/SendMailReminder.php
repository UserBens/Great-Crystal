<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Mail\SppMail;

class SendMailReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
      
    public $email;
    /**
     * Create a new job instance.
     */
    public function __construct($email)
    {
      $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Mail::to($this->email)->send(new SppMail('coba'));
    }
}