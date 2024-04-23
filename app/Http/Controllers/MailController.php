<?php
  
namespace App\Http\Controllers;

use App\Mail\BookMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;
use App\Mail\FeeRegisMail;
use App\Mail\PaymentSuccessMail;
use App\Mail\SppMail;
use App\Models\Bill;
use App\Models\Book;
use App\Models\statusInvoiceMail;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Carbon;


// use Illuminate\Bus\Queueable;
// use Illuminate\Foundation\Bus\Dispatchable;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Queue\SerializesModels;

class MailController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */

   // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function index()
    {
      try {
         //code...

         $student = Student::with(
            ['bill' => function($query){
               $query->where('type', 'Capital Fee')->get();
            }, 
             'relationship' => function($query){
               $query->get(['email']);
            },])->whereHas('bill', function($query) {
               $query->where('type', 'Capital Fee')->where('paidOf', false)->where('deadline_invoice', '>', date('y-m-d'));
            })->where('id', 10)->first();
         
            // return $student;
         $mailData = [
            'student' => $student,
            'bill' => $student->bill,
            'past_due' => false
        ];

     
           
        return dd("Email is sent successfully.");
      } catch (Exception $err) {
         //throw $th;
         return dd($err);
      }
        
    }

    public function cobaTemplate($type='SPP')
    {
      try {
         date_default_timezone_set('Asia/Jakarta');

         // Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s');

         $students = Student::with([
            'bill' => function($query) use ($type) {
               $query
               ->where('type', $type)
               ->where('paid_date', '>', Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s'))
               ->where('paidOf', true)
               ->get();
         },
            'relationship'
         ])
         ->whereHas('bill', function($query) use ($type) {
               $query
               ->where('type', $type)
               ->where('paid_date', '>', Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s'))
               ->where('paidOf', true);
         })->get();

         foreach ($students as $student) {

            foreach ($student->bill as $bill) {
               # code...
               $mailData = [
                  'student' => $student,
                  'bill' => [$bill],
                  'past_due' => true,
               ];
               
               $pdfBill = Bill::with(['student' => function ($query) {
                  $query->with('grade');
               }, 'bill_collection', 'bill_installments'])
               ->where('id', $bill->id)
               ->first();
               
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');
               
                foreach ($student->relationship as $relationship) {
                  $mailData['name'] = $relationship->name;
                  return view('emails.payment-success')->with('mailData', $mailData);

                  
                  
                  Mail::to($relationship->email)->send(new PaymentSuccessMail($mailData, "Payment " . $type . " has confirmed!", $pdf));
               }
            }

            
         }

         
     } catch (Exception $err) {
        info("Cron Job reminder Error at: " . $err);
        return dd($err);
     }
    }

    public function cronCreateSpp()
   {
      DB::beginTransaction();

      try {
         //code...
         date_default_timezone_set('Asia/Jakarta');

         $billCreated = [];
         
         $data = Student::with(['relationship', 'spp_student' => function($query) {
            $query->where('type', 'SPP')->get();
         }, 
         'grade' => function($query) {
            $query->with(['spp' => function($query) {
               $query->where('type', 'SPP')->get();
            }]);
         }])->where('is_active', true)->orderBy('id', 'asc')->get();
         
         foreach($data as $student)
         {
            $createBill = Bill::create([
               'student_id' => $student->id,
               'type' => 'SPP',
               'subject' => 'SPP - ' . date('M Y'),
               'amount' => $student->spp_student? $student->spp_student->amount : $student->grade->spp->amount,
               'paidOf' => false,
               'discount' => $student->spp_student ? ($student->spp_student->discount? $student->spp_student->discount : null) : null,
               'deadline_invoice' => Carbon::now()->setTimezone('Asia/Jakarta')->addDays(10)->format('Y-m-d'),
               'installment' => 0,
            ]);
            
            $mailDatas = [
               'student' => $student,
               'bill' => [$createBill],
               'past_due' => false,
            ];

            array_push($billCreated, $mailDatas);          
         }
         
         DB::commit();
         
         foreach($billCreated as $idx => $mailData) {

               $pdfBill = Bill::with(['student' => function ($query) {
                  $query->with('grade');
               }, 'bill_collection', 'bill_installments'])
               ->where('id', $mailData['bill'][0]->id)
               ->first();
                
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');         
               
            try {
               foreach($data[$idx]->relationship as $el)
               {
                     //code...
                     $mailData['name'] = $el->name;
                     Mail::to($el->email)->send(new SppMail($mailData, "Tagihan SPP " . $data[$idx]->name.  " bulan ini, ". date('l, d F Y') ." sudah dibuat.", $pdf));
               
               }
               statusInvoiceMail::create([
                     'bill_id' => $pdfBill->id,
                  ]);

            } catch (Exception) {
                     
               statusInvoiceMail::create([
                  'bill_id' => $pdfBill->id,
                  'status' => false,
               ]);
            }
         }

         info("Cron Job create spp success at ". date('d-m-Y'));
      } catch (Exception $err) {
         //throw $th;
         DB::rollBack();
         info("Cron Job create spp error: ". $err, []);
         return dd($err);
      }
   }


   public function createNotificationFeeRegister()
   {
      try {
         //sementara gabisa kirim email push array dulu

         $data = Student::with([
            'bill' => function($query)  {
               $query
               ->where('type', "Capital Fee")
               ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(9)->format('y-m-d'))
               ->where('paidOf', false)
               ->get();
         },
            'relationship'
         ])
         ->whereHas('bill', function($query) {
               $query
               ->where('type', "Capital Fee")
               ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(9)->format('y-m-d'))
               ->where('paidOf', false);
         })
         ->get();

         
         foreach ($data as $student) {
            
            foreach ($student->bill as $createBill) {
               
               // return 'nyampe';
               $mailData = [
                  'student' => $student,
                  'bill' => [$createBill],
                  'past_due' => false,
               ];


               $pdfBill = Bill::with(['student' => function ($query) {
                  $query->with('grade');
               }, 'bill_installments'])
               ->where('id', $createBill->id)
               ->first();

                
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait'); 

                $pdfReport = null;

               if($createBill->installment){
                  
                  $pdfReport = app('dompdf.wrapper');
                  $pdfReport->loadView('components.bill.pdf.installment-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait'); 
               }

               try {

                  foreach($student->relationship as $parent)
               {
                  $mailData['name'] = $parent->name;
                  // return view('emails.fee-regis-mail')->with('mailData', $mailData);
                  Mail::to($parent->email)->send(new FeeRegisMail($mailData, "Tagihan Capital Fee " . $student->name.  " bulan ini, ". date('l, d F Y') ." sudah dibuat.", $pdf, $pdfReport));
                  
               }

               statusInvoiceMail::create([
                  'status' =>true,
                  'bill_id' => $createBill->id,
               ]);

               } catch (Exception $err) {

                  statusInvoiceMail::create([
                  'status' =>false,
                  'bill_id' => $createBill->id,
                  ]);
               }
               
            }
         }


         info('Cron notification Fee Register success at ' . now());
         
      } catch (Exception $err) {
         
         return dd($err);
         info('Cron notification Fee Register error at ' . now());
      }
   }


   public function cronChargePastDue($type = "SPP")
   {
      try {
         date_default_timezone_set('Asia/Jakarta');


         $billCharge = Bill::where('paidOf', false)->where('deadline_invoice', '<', date('Y-m-d'))->where('type', $type)->get(['id', 'amount', 'charge', 'installment', 'amount_installment']);

         foreach ($billCharge as $bill) {
            # code...
            Bill::where('id', $bill->id)->update([
               'amount'=> $bill->amount + 100_000,
               'charge'=> $bill->charge + 100_000,
               'amount_installment' => $bill->installment? $bill->amount_installment + 100_000 : $bill->amount_installment,
            ]);
         }
         
         $data = Student::with(['bill' => function($query) use ($type){
            $query
            ->where('type', $type)
            ->where('deadline_invoice', '<', date('Y-m-d'))
            ->where('paidOf', false)
            ->get();
         }, 'relationship'])->whereHas('bill', function($query) use ($type) {
            $query
            ->where('type', $type)
            ->where('paidOf', false)
            ->where('deadline_invoice', '<', date('Y-m-d'));
         })->get();
             
         // return $data;

         foreach ($data as $student) {

            foreach ($student->bill as $bill) {
               # code...
               $mailData = [
                  'student' => $student,
                  'bill' => [$bill],
                  'past_due' => true,
               ];

               $pdfBill = Bill::with(['student' => function ($query) {
                  $query->with('grade');
               }, 'bill_installments'])
               ->where('id', $bill->id)
               ->first();
                
               $pdf = app('dompdf.wrapper');
               $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');
               $pdfReport = null;
               
               if($pdfBill->installment)
               {
                  $pdfReport = app('dompdf.wrapper');
                  $pdfReport->loadView('components.bill.pdf.installment-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');
               }

               try {
                  //code...
                  foreach ($student->relationship as $relationship) {
                     $mailData['name'] = $relationship->name;

                     if($type == 'SPP') {

                        Mail::to($relationship->email)->send(new SppMail($mailData, "Charge " . $type . " tagihan anda yang sudah jatuh tempo", $pdf, $pdfReport));
                     } else {

                        // return view('emails.fee-regis-mail')->with('mailData', $mailData);
                        Mail::to($relationship->email)->send(new FeeRegisMail($mailData, "Charge " . $type . " tagihan anda yang sudah jatuh tempo", $pdf, $pdfReport));
                     }
                  }

                  statusInvoiceMail::create([
                     'bill_id' => $bill->id,
                     'charge' => true,
                     'past_due' => true,
                  ]);

               } catch (Exception) {

                  statusInvoiceMail::create([
                     'bill_id' => $bill->id,
                     'status' => false,
                     'charge' => true,
                     'past_due' => true,
                  ]);
               }
            }

            
         }
         info("Cron Job charge success at ". date('d-m-Y'));
         
     } catch (Exception $err) {
        info("Cron Job reminder Error at: " . $err);
        return dd($err);
     }
   }



   public function  cronReminder($type = "SPP")
   {
      try {
         //code...
         $data = Student::with(['bill' => function($query) use ($type){
            $query
            ->where('type', $type)
            ->where('deadline_invoice', '<', date('Y-m-d'))
            ->where('paidOf', false)
            ->get();
         }, 'relationship'])->whereHas('bill', function($query) use ($type) {
            $query
            ->where('type', $type)
            ->where('paidOf', false)
            ->where('deadline_invoice', '<', date('Y-m-d'));
         })->get();
             
         // return $data;

         foreach ($data as $student) {

            foreach ($student->bill as $bill) {
               # code...
               $mailData = [
                  'student' => $student,
                  'bill' => [$bill],
                  'past_due' => true,
               ];

               $pdfBill = Bill::with(['student' => function ($query) {
                  $query->with('grade');
               }, 'bill_collection', 'bill_installments'])
               ->where('id', $bill->id)
               ->first();
                
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');


               try {
                  //code...
                  foreach ($student->relationship as $relationship) {
                     
                     Mail::to($relationship->email)->send(new SppMail($mailData, "Charge " . $type . " tagihan anda yang sudah jatuh tempo", $pdf));
                  }

                  statusInvoiceMail::create([
                     'bill_id' => $bill->id,
                     'charge' => false,
                     'past_due' => true,
                  ]);

               } catch (Exception) {


                  statusInvoiceMail::create([
                     'bill_id' => $bill->id,
                     'status' => false,
                     'charge' => false,
                     'past_due' => true,
                  ]);
               }
            }

            
         }
         info("Cron Job reminder success at ". date('d-m-Y'));

      } catch (Exception $err) {
         Info("Cron Job reminder H-7 error: ". $err);
         
         return dd($err);
      }
   }

   public function cronReminderMinusOneDay($type = "SPP")
   {
      try {
         //code...
         $data = Student::with([
            'bill' => function($query) use ($type) {
               $query
               ->where('type', $type)
               ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(1)->format('y-m-d'))
               ->where('paidOf', false)
               ->get();
         },
            'relationship'
         ])
         ->whereHas('bill', function($query) use ($type) {
               $query
               ->where('type', $type)
               ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(1)->format('y-m-d'))
               ->where('paidOf', false);
         })
         ->where('is_active', true)
         ->get();

      
         foreach ($data as  $student)
         {
            $mailData = [
               'student' => $student,
               'bill' => $student->bill,
               'past_due' => 'H-1',
            ];

            foreach($student->relationship as $parent)
            {
               // Mail::to($parent->email)->send(new SppMail($mailData, 'Reminder h-1 pembayaran '. strtolower($student->bill[0]->subject) .' sebelum jatuh tempo'));
            }
         }

         Info("Cron Job reminder H-1 success at ". date('d-m-Y'));
         
         return 'success';
      } catch (Exception $err) {
         Info("Cron Job reminder H-1 error: ". $err);
         
         return dd($err);
      }
   }


   public function createNotificationBook()
   {
      try {
         //sementara gabisa kirim email push array dulu

         $data = Student::with([
            'bill' => function($query)  {
               $query
               ->with('bill_collection')
               ->where('type', "Book")
               ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
               ->where('paidOf', false)
               ->get();
         },
            'relationship'
         ])
         ->whereHas('bill', function($query) {
               $query
               ->where('type', "Book")
               ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
               ->where('paidOf', false);
         })
         ->get();
         
         foreach ($data as $student) {
            
            foreach ($student->bill as $createBill) {
               
               $mailData = [
                  'student' => $student,
                  'bill' => $createBill,
                  'past_due' => false,
               ];


               $pdfBill = Bill::with(['student' => function ($query) {
                  $query->with('grade');
               }, 'bill_installments', 'bill_collection'])
               ->where('id', $createBill->id)
               ->first();

                
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait'); 


               
               try {

                  foreach($student->relationship as $parent)
               {
                  $mailData['name'] = $parent->name;
                  Mail::to($parent->email)->send(new BookMail($mailData, "Tagihan Buku " . $student->name.  " ". date('l, d F Y') ." sudah dibuat.", $pdf));
               }

               statusInvoiceMail::create([
                  'status' =>true,
                  'bill_id' => $createBill->id,
               ]);

               } catch (Exception $err) {
                  statusInvoiceMail::create([
                  'status' =>false,
                  'bill_id' => $createBill->id,
                  ]);
               }
               
            }
         }


         info('Cron notification Books success at ' . now());
         
      } catch (Exception $err) {
          
         return dd($err);
         info('Cron notification Books error at ' . now());
      }
   }

   public function createNotificationUniform()
   {
      try {
         //sementara gabisa kirim email push array dulu

         $data = Student::with([
            'bill' => function($query)  {
               $query
               ->where('type', "Uniform")
               ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
               ->where('paidOf', false)
               ->get();
         },
            'relationship'
         ])
         ->whereHas('bill', function($query) {
               $query
               ->where('type', "Uniform")
               ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
               ->where('paidOf', false);
         })
         ->get();

         
         foreach ($data as $student) {
            
            foreach ($student->bill as $createBill) {
               
               // return 'nyampe';
               $mailData = [
                  'student' => $student,
                  'bill' => [$createBill],
                  'past_due' => false,
               ];


               $pdfBill = Bill::with(['student' => function ($query) {
                  $query->with('grade');
               }])
               ->where('id', $createBill->id)
               ->first();

                
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait'); 

               try {

                  foreach($student->relationship as $parent)
               {
                  $mailData['name'] = $parent->name;
                  // return view('emails.spp-mail')->with('mailData', $mailData);
                  Mail::to($parent->email)->send(new SppMail($mailData, "Tagihan Uniform " . $student->name.  " bulan ini, ". date('l, d F Y') ." sudah dibuat.", $pdf));
                  
               }

               statusInvoiceMail::create([
                  'status' =>true,
                  'bill_id' => $createBill->id,
               ]);

               } catch (Exception $err) {

                  statusInvoiceMail::create([
                  'status' =>false,
                  'bill_id' => $createBill->id,
                  ]);
               }
               
            }
         }


         info('Cron notification Fee Register success at ' . now());
         
      } catch (Exception $err) {
         
         return dd($err);
         info('Cron notification Fee Register error at ' . now());
      }
   }

   public function cronCreatePaketAfterGraduate($array =  [])
   {

      DB::beginTransaction();
      try {
         //code...

         
         // return $students;
         $idx = 0;

         foreach($array as $studentId)
         {

            $idx++;

            $student = Student::with(['relationship', 'grade' => function($query) {
               $query->with(['bundle' => function($query) {
                  $query->where('type', 'Paket')->get();
               }, 'uniform' => function($query) {
                  $query->where('type', 'Uniform')->get();
               }]);
            }])
            ->where('id', (int)$studentId)
            ->where('is_active', true)
            ->first();
            //lakukan validasi ketika admin lupa memasukkan static payment paket disini;
            
            
            
            $amount = $student->grade->bundle? $student->grade->bundle->amount : null;
            $uniformAmount = $student->grade->uniform? $student->grade->uniform->amount : 0; 

            if(!$amount)
            {
               $bookAmount = Book::where('grade_id', (int)$student->grade_id)->get();
               $amountTotal = sizeof($bookAmount) > 0 ? $bookAmount->sum('amount') : 0;
               $amount = $uniformAmount + $amountTotal;
            }
            
            Bill::create([
               'student_id' => $student->id,
               'type' => 'Paket',
               'subject' => 'Paket '. $student->grade->name. ' ' .$student->grade->class,
               'amount' => $amount,
               'discount' => null,
               'installment' => null,
               'paidOf' => false,
               'deadline_invoice' => Carbon::now()->setTimezone('Asia/Jakarta')->addMonths($idx)->format('Y-m-10'),
            ]);

         }


         DB::commit();
         info('Create paket success at '. date('y-m-d'));
         // return redirect('/admin/bills');
         
      } catch (Exception $err) {
         DB::rollBack();
         info('Create paket error at '. date('y-m-d'));
         // return dd($err);
      }
   }

   public function notificationPaidSuccess($type='SPP')
   {
      try {
         //code...
         $students = Student::with([
            'bill' => function($query) use ($type) {
               $query
               ->where('type', $type)
               ->where('paid_date', '>= ', Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s'))
               ->where('paidOf', true)
               ->get();
         },
            'relationship'
         ])
         ->whereHas('bill', function($query) use ($type) {
               $query
               ->where('type', $type)
               ->where('paid_date', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s'))
               ->where('paidOf', true);
         })->get();

         foreach ($students as $student) {

            foreach ($student->bill as $bill) {
               # code...
               $mailData = [
                  'student' => $student,
                  'bill' => [$bill],
                  'past_due' => true,
               ];
               
               $pdfBill = Bill::with(['student' => function ($query) {
                  $query->with('grade');
               }, 'bill_collection', 'bill_installments'])
               ->where('id', $bill->id)
               ->first();
               
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');

                foreach ($student->relationship as $relationship) {
                  $mailData['name'] = $relationship->name;
                  // return view('emails.payment-success')->with('mailData', $mailData);
                  
                  Mail::to($relationship->email)->send(new PaymentSuccessMail($mailData, "Payment " . $type . " has confirmed!", $pdf));
               }
            }
         } 
         
      } catch (Exception $err) {
         
         info('Cron job send notification payment success error at ' . $err);
      }
   }
}