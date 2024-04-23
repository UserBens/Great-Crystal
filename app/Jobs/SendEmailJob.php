<?php
  
namespace App\Jobs;

use App\Mail\BookMail;
use App\Mail\FeeRegisMail;
use App\Mail\PaketMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendEmailTest;
use App\Mail\SppMail;
use App\Models\Bill;
use App\Models\statusInvoiceMail;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
  
class SendEmailJob implements ShouldQueue, ShouldBeUnique, ShouldBeUniqueUntilProcessing
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $email, $type, $mailData, $subject, $bill_id, $product;
    public $tries = 5;
  
    /**
     * Create a new job instance.
     */
    public function __construct($email, $type, $mailData, $subject, $bill_id)
    {
        $this->email = $email;
        $this->type = $type;
        $this->mailData = $mailData;
        $this->subject = $subject;
        $this->bill_id = $bill_id;
    }
  
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // info('queue email running');
        $bcc = 'donny@great.sch.id';
        $pdfBill = Bill::with(['student' => function ($query) {
            $query->with('grade');
        }, 'bill_collection', 'bill_installments'])
        ->where('id', $this->bill_id)
        ->first();
          
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait'); 

        $pdfReport = null;

        if($pdfBill->installment){
            
            $pdfReport = app('dompdf.wrapper');
            $pdfReport->loadView('components.bill.pdf.installment-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait'); 
        }



        //check type

        if(strtolower($this->type) === 'paket') {
            $target = new PaketMail($this->mailData, $this->subject, $pdf, $pdfReport);
        } else if (strtolower($this->type) === 'capital fee') {
            $target = new FeeRegisMail($this->mailData, $this->subject, $pdf, $pdfReport);
        } else if (strtolower($this->type) === 'book'){
            $target = new BookMail($this->mailData, $this->subject, $pdf);
        } else {
            $target = new SppMail($this->mailData, $this->subject, $pdf);
        }

        
        Mail::to($this->email[0])->cc($this->email[1], 'parents')->bcc($bcc, 'owners')->send($target);

        statusInvoiceMail::create([
            'status' => true,
            'bill_id' => $this->bill_id,
            'past_due' => $this->mailData['past_due'],
            'charge' => $this->mailData['charge'],
            'is_change' => $this->mailData['change'],
            'is_paid' => $this->mailData['is_paid'],
        ]);
    }

    public function failed(\Exception $exception) :void
    {
       info('Queue job '. $this->type .' failed at ' . date('Y-m-d H:i:s'));
        statusInvoiceMail::create([
            'status' => false,
            'bill_id' => $this->bill_id,
            'past_due' => $this->mailData['past_due'],
            'charge' => $this->mailData['charge'],
            'is_change' => $this->mailData['change'],
            'is_paid' => $this->mailData['is_paid'],
        ]);
    }

    public function retryUntil(): DateTime
    {
        return now()->addMinutes(10);
    }


    public function uniqueId(): string
    {
        return $this->bill_id;
    }
}