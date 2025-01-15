<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\BookMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;
use App\Mail\EtcMail;
use App\Mail\FeeRegisMail;
use App\Mail\MaterialFeeMail;
use App\Mail\PaketMail;
use App\Mail\PaymentSuccessMail;
use App\Mail\SppMail;
use App\Mail\UniformMail;
use App\Models\Bill;
use App\Models\Book;
use App\Models\MaterialFeeInstallment;
use App\Models\Payment_grade;
use App\Models\statusInvoiceMail;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotificationBillCreated extends Controller
{


   public function test()
   {
      try {
         //code...
         $details['email'] = 'your_email@gmail.com';

         // dispatch(new SendEmailJob($details));

         info('cron test running at ' . now());
      } catch (Exception $err) {
         //throw $th;
         info('cron error at ' . $err->getMessage());
      }
   }

   // done
   public function spp()
   {

      DB::beginTransaction();

      try {
         //code...
         date_default_timezone_set('Asia/Jakarta');

         $billCreated = [];

         $data = Student::with([
            'relationship',
            'spp_student' => function ($query) {
               $query->where('type', 'SPP')->get();
            },
            'grade' => function ($query) {
               $query->with(['spp' => function ($query) {
                  $query->where('type', 'SPP')->get();
               }]);
            }
         ])->where('is_active', true)->orderBy('id', 'asc')->get();

         foreach ($data as $student) {
            $createBill = Bill::create([
               'student_id' => $student->id,
               'type' => 'SPP',
               'subject' => 'SPP - ' . date('M Y'),
               'amount' => $student->spp_student ? $student->spp_student->amount : $student->grade->spp->amount,
               'paidOf' => false,
               'discount' => $student->spp_student ? ($student->spp_student->discount ? $student->spp_student->discount : null) : null,
               'deadline_invoice' => Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-10'),
               'installment' => 0,
            ]);

            $mailDatas = [
               'student' => $student,
               'bill' => [$createBill],
               'past_due' => false,
               'charge' => false,
               'change' => false,
               'is_paid' => false,
            ];

            array_push($billCreated, $mailDatas);
         }

         foreach ($billCreated as $idx => $mailData) {
            try {
               $array_email = [];
               foreach ($mailData['student']->relationship as $el) {
                  $mailData['name'] = $mailData['student']->relationship[0]->name;
                  array_push($array_email, $el->email);
                  $pdf = app('dompdf.wrapper');
                  // Perhatikan bahwa kita menggunakan $mailData['bill'][0] untuk setiap siswa
                  $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $mailData['bill'][0]])->setPaper('a4', 'portrait');
                  Mail::to($el->email)->send(new SppMail($mailData, "Tagihan Monthly Fee " . $mailData['student']->name .  " bulan " . date('F Y') . " sudah dibuat.", $pdf));
               }
               dispatch(new SendEmailJob($array_email, 'SPP', $mailData, "Pemberitahuan Tagihan Monthly Fee " .  " " . date('F Y') . ".", $mailData['bill'][0]->id));
            } catch (Exception $e) {
               statusInvoiceMail::create([
                  'bill_id' => $mailData['bill'][0]->id,
                  'status' => false,
               ]);
            }
         }

         DB::commit();

         info("Cron Job create spp success at " . date('d-m-Y'));
      } catch (Exception $err) {
         //throw $th;
         DB::rollBack();
         info("Cron Job create spp error: " . $err, []);
         return dd($err);
      }
   }


   // done code awal
   // public function paket()
   // {
   //    try {

   //       $data = Student::with([
   //          'bill' => function ($query) {
   //             $query
   //                ->where('type', "Paket")
   //                ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(9)->format('y-m-d'))
   //                ->where('paidOf', false)
   //                ->where('subject', '!=', 'Paket')
   //                ->where('subject', '!=', '1')
   //                ->orWhere('type', "Paket")
   //                ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
   //                ->where('installment', null)
   //                ->where('subject', 'Paket')
   //                ->where('paidOf', false)
   //                ->get();
   //          },
   //          'relationship'
   //       ])
   //          // muncul 2 notifikasi email dengan total tagihan yang berbeda 

   //          ->whereHas('bill', function ($query) {
   //             $query
   //                ->where('type', "Paket")
   //                ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(9)->format('y-m-d'))
   //                ->where('paidOf', false)
   //                ->where('subject', '!=', 'Paket')
   //                ->where('subject', '!=', '1')
   //                ->orWhere('type', "Paket")
   //                ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
   //                ->where('installment', null)
   //                ->where('subject', 'Paket')
   //                ->where('paidOf', false);
   //          })

   //          ->get();

   //       //   return $data;

   //       foreach ($data as $student) {

   //          foreach ($student->bill as $createBill) {

   //             $mailData = [
   //                'student' => $student,
   //                'bill' => [$createBill],
   //                'past_due' => false,
   //                'charge' => false,
   //                'change' => false,
   //                'is_paid' => false,
   //             ];


   //             $subject = $createBill->installment ? "Pemberitahuan Tagihan Package " . $student->name .  " " . date('F Y') . "." : "Pemberitahuan Tagihan Package " . $student->name . ".";

   //             try {

   //                $array_email = [];

   //                foreach ($student->relationship as $idx => $parent) {
   //                   if ($idx == 0) {
   //                      $mailData['name'] = $parent->name;
   //                   }
   //                   array_push($array_email, $parent->email);
   //                   //  return view('emails.paket-mail')->with('mailData', $mailData);
   //                   $pdf = app('dompdf.wrapper');
   //                   $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $createBill])->setPaper('a4', 'portrait');
   //                   Mail::to($parent->email)->send(new PaketMail($mailData, $subject, $pdf));
   //                }

   //                dispatch(new SendEmailJob($array_email, 'paket', $mailData, $subject, $mailData['bill'][0]->id));
   //             } catch (Exception $err) {

   //                statusInvoiceMail::create([
   //                   'status' => false,
   //                   'bill_id' => $createBill->id,
   //                ]);
   //             }
   //          }
   //       }

   //       info('Cron notification Paket success at ' . now());
   //    } catch (Exception $err) {

   //       info('Cron notification Paket error at ' . now());
   //    }
   // }


   // test from gpt
   public function paket()
   {
      DB::beginTransaction();

      try {
         date_default_timezone_set('Asia/Jakarta');

         $billCreated = [];

         $data = Student::with([
            'relationship',
            'grade.payment_grade' => function ($query) {
               $query->where('type', 'Paket');
            }
         ])->where('is_active', true)->orderBy('id', 'asc')->get();

         foreach ($data as $student) {
            foreach ($student->grade->payment_grade as $paymentGrade) {
               if ($paymentGrade->type == 'Paket') {
                  $createBill = Bill::create([
                     'student_id' => $student->id,
                     'type' => 'Paket',
                     'subject' => 'Paket - ' . date('M Y'),
                     'amount' => $paymentGrade->amount,
                     'paidOf' => false,
                     'discount' => null,
                     'deadline_invoice' => Carbon::now()->setTimezone('Asia/Jakarta')->addDays(9)->format('Y-m-d'),
                     'installment' => null,
                  ]);

                  $mailDatas = [
                     'student' => $student,
                     'bill' => [$createBill],
                     'past_due' => false,
                     'charge' => false,
                     'change' => false,
                     'is_paid' => false,
                  ];

                  array_push($billCreated, $mailDatas);
               }
            }
         }

         // foreach ($billCreated as $idx => $mailData) {
         //    try {
         //       $array_email = [];
         //       foreach ($mailData['student']->relationship as $el) {
         //          $mailData['name'] = $mailData['student']->relationship[0]->name;
         //          array_push($array_email, $el->email);
         //          $pdf = app('dompdf.wrapper');
         //          $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $mailData['bill'][0]])->setPaper('a4', 'portrait');
         //          // Mail::to($el->email)->send(new PaketMail($mailData, "Tagihan Paket " . $mailData['student']->name . " bulan " . date('F Y') . " sudah dibuat.", $pdf));
         //          Mail::to($el->email)->send(new PaketMail($mailData, "Tagihan Biaya Kegiatan " . $mailData['student']->name . " School Year " . date('F Y') . " sudah dibuat.", $pdf));
         //       }
         //       dispatch(new SendEmailJob($array_email, 'Paket', $mailData, "Pemberitahuan Tagihan Paket " . " " . date('F Y') . ".", $mailData['bill'][0]->id));
         //    } catch (Exception $e) {
         //       statusInvoiceMail::create([
         //          'bill_id' => $mailData['bill'][0]->id,
         //          'status' => false,
         //       ]);
         //    }
         // }

         foreach ($billCreated as $idx => $mailData) {
            try {
               $array_email = [];
               $currentYear = date('Y');
               $nextYear = $currentYear + 1;
               $schoolYear = $currentYear . '/' . $nextYear;

               foreach ($mailData['student']->relationship as $el) {
                  $mailData['name'] = $mailData['student']->relationship[0]->name;
                  array_push($array_email, $el->email);
                  $pdf = app('dompdf.wrapper');
                  $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $mailData['bill'][0]])->setPaper('a4', 'portrait');
                  // Mail::to($el->email)->send(new PaketMail($mailData, "Tagihan Paket " . $mailData['student']->name . " bulan " . date('F Y') . " sudah dibuat.", $pdf));
                  Mail::to($el->email)->send(new PaketMail($mailData, "Tagihan Biaya Kegiatan " . $mailData['student']->name . " School Year " . $schoolYear . " sudah dibuat.", $pdf));
               }
               dispatch(new SendEmailJob($array_email, 'Paket', $mailData, "Pemberitahuan Tagihan Paket " . " " . date('F Y') . ".", $mailData['bill'][0]->id));
            } catch (Exception $e) {
               statusInvoiceMail::create([
                  'bill_id' => $mailData['bill'][0]->id,
                  'status' => false,
               ]);
            }
         }

         DB::commit();

         info("Cron Job create paket success at " . date('d-m-Y'));
      } catch (Exception $err) {
         DB::rollBack();
         info("Cron Job create paket error: " . $err, []);
      }
   }




   // done
   public function feeRegister()
   {
      try {

         // return  Carbon::now()->setTimezone('Asia/Jakarta')->addDays(9)->format('y-m-d');

         $data = Student::with([
            'bill' => function ($query) {
               $query
                  ->where('type', "Capital Fee")
                  ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(9)->format('y-m-d'))
                  ->where('paidOf', false)
                  ->where('subject', '!=', 'Capital Fee')
                  ->where('subject', '!=', '1')
                  ->orWhere('type', "Capital Fee")
                  ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('installment', null)
                  ->where('subject', 'Capital Fee')
                  ->where('paidOf', false)
                  ->orWhere('type', "Capital Fee")
                  ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('subject', '1')
                  ->where('paidOf', false)
                  ->get();
            },
            'relationship'
         ])
            // ->whereHas('bill', function ($query) {
            //    $query
            //       ->where('type', "Capital Fee")
            //       ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(9)->format('y-m-d'))
            //       ->where('subject', '!=', 'Capital Fee')
            //       ->where('subject', '!=', '1')
            //       ->where('paidOf', false)
            //       ->orWhere('type', "Capital Fee")
            //       ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
            //       ->where('installment', null)
            //       ->where('subject', 'Capital Fee')
            //       ->where('paidOf', false)
            //       ->orWhere('type', "Capital Fee")
            //       ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
            //       ->where('subject', '1')
            //       ->where('paidOf', false);
            // })
            ->get();

         info('Data fetched: ' . $data->count() . ' students found');



         //   return $data;

         foreach ($data as $student) {

            foreach ($student->bill as $createBill) {

               // return 'nyampe';
               $mailData = [
                  'student' => $student,
                  'bill' => [$createBill],
                  'past_due' => false,
                  'charge' => false,
                  'change' => false,
                  'is_paid' => false,
               ];

               $subject = $createBill->installment ? "Pemberitahuan Tagihan Capital Fee " . $student->name .  " " . date('F Y') . "." : "Tagihan Capital Fee " . $student->name . ".";

               try {

                  $array_email = [];

                  foreach ($student->relationship as $idx => $parent) {
                     if ($idx == 0) {
                        $mailData['name'] = $parent->name;
                     }
                     array_push($array_email, $parent->email);
                     //  return view('emails.fee-regis-mail')->with('mailData', $mailData);
                     $pdf = app('dompdf.wrapper');
                     $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $createBill])->setPaper('a4', 'portrait');

                     // Kirim email
                     Mail::to($parent->email)->send(new FeeRegisMail($mailData, $subject, $pdf));
                  }



                  dispatch(new SendEmailJob($array_email, 'capital fee', $mailData, $subject, $createBill->id));
               } catch (Exception $err) {

                  statusInvoiceMail::create([
                     'status' => false,
                     'bill_id' => $createBill->id,
                  ]);
               }
            }
         }

         info('Cron notification Fee Register success at ' . now());
      } catch (Exception $err) {

         info('Cron notification Fee Register error at ' . now());
         return dd($err);
      }
   }

   // public function feeRegister()
   // {
   //    try {
   //       $data = Student::with([
   //          'bill' => function ($query) {
   //             $query
   //                ->where('type', "Capital Fee")
   //                ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(9)->format('y-m-d'))
   //                ->where('paidOf', false)
   //                ->where('subject', '!=', 'Capital Fee')
   //                ->where('subject', '!=', '1')
   //                ->orWhere('type', "Capital Fee")
   //                ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
   //                ->where('installment', null)
   //                ->where('subject', 'Capital Fee')
   //                ->where('paidOf', false)
   //                ->orWhere('type', "Capital Fee")
   //                ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
   //                ->where('subject', '1')
   //                ->where('paidOf', false)
   //                ->get();
   //          },
   //          'relationship'
   //       ])
   //          ->whereHas('bill', function ($query) {
   //             $query
   //                ->where('type', "Capital Fee")
   //                ->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(9)->format('y-m-d'))
   //                ->where('subject', '!=', 'Capital Fee')
   //                ->where('subject', '!=', '1')
   //                ->where('paidOf', false)
   //                ->orWhere('type', "Capital Fee")
   //                ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
   //                ->where('installment', null)
   //                ->where('subject', 'Capital Fee')
   //                ->where('paidOf', false)
   //                ->orWhere('type', "Capital Fee")
   //                ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
   //                ->where('subject', '1')
   //                ->where('paidOf', false);
   //          })
   //          ->get();

   //       info('Data fetched: ' . $data->count() . ' students found');


   //       foreach ($data as $student) {
   //          foreach ($student->bill as $createBill) {
   //             $mailData = [
   //                'student' => $student,
   //                'bill' => [$createBill],
   //                'past_due' => false,
   //                'charge' => false,
   //                'change' => false,
   //                'is_paid' => false,
   //             ];

   //             $subject = $createBill->installment ? "Pemberitahuan Tagihan Capital Fee " . $student->name .  " " . date('F Y') . "." : "Tagihan Capital Fee " . $student->name . ".";

   //             try {
   //                $array_email = [];

   //                foreach ($student->relationship as $idx => $parent) {
   //                   if ($idx == 0) {
   //                      $mailData['name'] = $parent->name;
   //                   }
   //                   array_push($array_email, $parent->email);
   //                   $pdf = app('dompdf.wrapper');
   //                   $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $createBill])->setPaper('a4', 'portrait');

   //                   // Debug output
   //                   info('Sending email to: ' . $parent->email);
   //                   info('Mail Data: ' . json_encode($mailData));
   //                   info('Subject: ' . $subject);

   //                   Mail::to($parent->email)->send(new FeeRegisMail($mailData, $subject, $pdf));
   //                }

   //                dispatch(new SendEmailJob($array_email, 'capital fee', $mailData, $subject, $createBill->id));
   //             } catch (Exception $err) {
   //                statusInvoiceMail::create([
   //                   'status' => false,
   //                   'bill_id' => $createBill->id,
   //                ]);
   //             }
   //          }
   //       }

   //       info('Cron notification Fee Register success at ' . now());
   //    } catch (Exception $err) {
   //       info('Cron notification Fee Register error at ' . now());
   //       return dd($err);
   //    }
   // }



   // public function book()
   // {
   //    try {
   //       //sementara gabisa kirim email push array dulu

   //       $data = Student::with([
   //          'bill' => function ($query) {
   //             $query
   //                ->with('bill_collection')
   //                ->where('type', "Book")
   //                ->where('paidOf', false)
   //                ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
   //                ->orWhere('date_change_bill', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
   //                ->where('type', "Book")
   //                ->where('paidOf', false)
   //                ->get();
   //          },
   //          'relationship'
   //       ])
   //          // ->whereHas('bill', function ($query) {
   //          //    $query
   //          //       ->where('type', "Book")
   //          //       ->where('paidOf', false)
   //          //       ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
   //          //       ->orWhere('date_change_bill', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
   //          //       ->where('type', "Book")
   //          //       ->where('paidOf', false);
   //          // })
   //          ->get();

   //       foreach ($data as $student) {

   //          foreach ($student->bill as $createBill) {

   //             $mailData = [
   //                'student' => $student,
   //                'bill' => $createBill,
   //                'past_due' => false,
   //                'charge' => false,
   //                'change' => false,
   //                'is_paid' => false,
   //             ];



   //             $is_change = $createBill->date_change_bill ? true : false;
   //             $mailData['change'] = $is_change;

   //             try {

   //                $array_email = [];

   //                foreach ($student->relationship as $idx => $parent) {

   //                   if ($idx == 0) {

   //                      $mailData['name'] = $parent->name;
   //                   }

   //                   array_push($array_email, $parent->email);
   //                   //   return view('emails.book-mail')->with('mailData', $mailData);
   //                   $pdf = app('dompdf.wrapper');
   //                   $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $createBill])->setPaper('a4', 'portrait');
   //                   Mail::to($parent->email)->send(new BookMail($mailData, "Tagihan Buku " . $student->name . " sudah dibuat.", $pdf));
   //                }


   //                dispatch(new SendEmailJob($array_email, 'book', $mailData, "Tagihan Buku " . $student->name . " sudah dibuat.", $createBill->id));
   //             } catch (Exception $err) {
   //                statusInvoiceMail::create([
   //                   'status' => false,
   //                   'bill_id' => $createBill->id,
   //                   'is_change' => $is_change,
   //                ]);
   //             }
   //          }
   //       }

   //       info('Cron notification Books success at ' . now());
   //    } catch (Exception $err) {

   //       info('Cron notification Books error at ' . now());
   //       return dd($err);
   //    }
   // }

   public function book()
   {
      try {
         //sementara gabisa kirim email push array dulu


         $data = Student::with([
            'bill' => function ($query) {
               $query
                  ->with('bill_collection')
                  ->where('type', "Book")
                  ->where('paidOf', false)
                  ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->orWhere('date_change_bill', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('type', "Book")
                  ->where('paidOf', false)
                  ->get();
            },
            'relationship'
         ])
            ->whereHas('bill', function ($query) {
               $query
                  ->where('type', "Book")
                  ->where('paidOf', false)
                  ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->orWhere('date_change_bill', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('type', "Book")
                  ->where('paidOf', false);
            })
            ->get();

         foreach ($data as $student) {

            foreach ($student->bill as $createBill) {

               $mailData = [
                  'student' => $student,
                  'bill' => $createBill,
                  'past_due' => false,
                  'charge' => false,
                  'change' => false,
                  'is_paid' => false,
               ];

               $is_change = $createBill->date_change_bill ? true : false;
               $mailData['change'] = $is_change;

               try {

                  $array_email = [];

                  foreach ($student->relationship as $key => $parent) {

                     if ($key == 0) {

                        $mailData['name'] = $parent->name;
                     }

                     array_push($array_email, $parent->email);
                     //   return view('emails.book-mail')->with('mailData', $mailData);
                     $pdf = app('dompdf.wrapper');
                     $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $createBill])->setPaper('a4', 'portrait');
                     Mail::to($parent->email)->send(new BookMail($mailData, "Tagihan Buku " . $student->name . " sudah dibuat.", $pdf));
                  }

                  dispatch(new SendEmailJob($array_email, 'book', $mailData, "Tagihan Buku " . $student->name . " sudah dibuat.", $createBill->id));
               } catch (Exception $err) {
                  statusInvoiceMail::create([
                     'status' => false,
                     'bill_id' => $createBill->id,
                     'is_change' => $is_change,
                  ]);
               }
            }
         }


         info('Cron notification Books success at ' . now());
      } catch (Exception $err) {

         info('Cron notification Books error at ' . now());
         return dd($err);
      }
   }




   // done
   public function uniform()
   {
      try {
         //sementara gabisa kirim email push array dulu

         $data = Student::with([
            'bill' => function ($query) {
               $query
                  ->where('type', "Uniform")
                  ->where('paidOf', false)
                  ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->orWhere('date_change_bill', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('type', "Uniform")
                  ->where('paidOf', false)
                  ->get();
            },
            'relationship'
         ])
            ->whereHas('bill', function ($query) {
               $query
                  ->where('type', "Uniform")
                  ->where('paidOf', false)
                  ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->orWhere('date_change_bill', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('type', "Uniform")
                  ->where('paidOf', false);
            })
            ->get();

         //   return $data;

         foreach ($data as $student) {

            foreach ($student->bill as $createBill) {

               // return 'nyampe';
               $mailData = [
                  'student' => $student,
                  'bill' => [$createBill],
                  'past_due' => false,
               ];

               $is_change = $createBill->date_change_bill ? true : false;
               $mailData['change'] = $is_change;

               try {

                  $array_email = [];

                  foreach ($student->relationship as $key => $parent) {
                     if ($key == 0) {
                        $mailData['name'] = $parent->name;
                     }

                     array_push($array_email, $parent->email);
                     //   return view('emails.spp-mail')->with('mailData', $mailData);
                     $pdf = app('dompdf.wrapper');
                     $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $createBill])->setPaper('a4', 'portrait');
                     Mail::to($parent->email)->send(new UniformMail($mailData, "Tagihan Uniform " . $student->name . " sudah dibuat.", $pdf));
                  }

                  dispatch(new SendEmailJob($array_email, 'uniform', $mailData, "Tagihan Uniform " . $student->name . " sudah dibuat.", $createBill->id));
               } catch (Exception $err) {

                  statusInvoiceMail::create([
                     'status' => false,
                     'bill_id' => $createBill->id,
                     'is_change' => $is_change,
                  ]);
               }
            }
         }


         info('Cron notification Uniform success at ' . now());
      } catch (Exception $err) {

         info('Cron notification uniform error at ' . now());
         return dd($err);
      }
   }



   public function changePaket()
   {
      try {

         $data = Student::with([
            'bill' => function ($query) {
               $query
                  ->where('type', "Paket")
                  ->where('paidOf', false)
                  ->where('date_change_bill', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('subject', '1')
                  ->get();
            },
            'relationship'
         ])
            ->whereHas('bill', function ($query) {
               $query
                  ->where('type', "Paket")
                  ->where('date_change_bill', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('subject', '1')
                  ->where('paidOf', false);
            })
            ->get();

         // return $data;


         foreach ($data as $student) {

            foreach ($student->bill as $createBill) {


               $past_due = false;

               if (strtotime($createBill->deadline_invoice) < strtotime(date('y-m-d'))) {
                  $past_due = true;
               }

               $mailData = [
                  'student' => $student,
                  'bill' => [$createBill],
                  'change' => true,
                  'past_due' => $past_due,
                  'charge' => false,
                  'is_paid' => false,
               ];

               try {

                  $array_email = [];

                  foreach ($student->relationship as $key => $parent) {
                     if ($key == 0) $mailData['name'] = $parent->name;
                     array_push($array_email, $parent->email);
                     //  return view('emails.paket-mail')->with('mailData', $mailData);
                     $pdf = app('dompdf.wrapper');
                     $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $createBill])->setPaper('a4', 'portrait');
                     Mail::to($parent->email)->send(new PaketMail($mailData, "Tagihan Paket " . $student->name .  " berhasil diubah, pada tanggal " . date('l, d F Y'), $pdf));
                  }

                  dispatch(new SendEmailJob($array_email, 'paket', $mailData, "Tagihan Paket " . $student->name .  " berhasil diubah, pada tanggal " . date('l, d F Y'), $createBill->id));
               } catch (Exception $err) {

                  statusInvoiceMail::create([
                     'status' => false,
                     'bill_id' => $createBill->id,
                     'is_change' => true,
                  ]);
               }
            }
         }

         info('Cron notification Fee Register success at ' . now());
      } catch (Exception $err) {

         info('Cron notification Fee Register error at ' . now());
      }
   }

   // done
   public function etc()
   {
      try {
         //sementara gabisa kirim email push array dulu

         $data = Student::with([
            'bill' => function ($query) {
               $query
                  ->whereNotIn('type', ["SPP", "Capital Fee", "Book", "Uniform", "Paket"])
                  ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('paidOf', false)
                  ->get();
            },
            'relationship'
         ])
            ->whereHas('bill', function ($query) {
               $query
                  ->whereNotIn('type', ["SPP", "Capital Fee", "Book", "Uniform", "Paket"])
                  ->where('created_at', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                  ->where('paidOf', false);
            })
            ->get();

         //   return $data;

         foreach ($data as $student) {

            foreach ($student->bill as $createBill) {

               // return 'nyampe';
               $mailData = [
                  'student' => $student,
                  'bill' => [$createBill],
                  'past_due' => false,
                  'charge' => false,
                  'change' => false,
                  'is_paid' => false,
               ];


               $pdfBill = Bill::with(['student' => function ($query) {
                  $query->with('grade');
               }])
                  ->where('id', $createBill->id)
                  ->first();


               //    $pdf = app('dompdf.wrapper');
               //    $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait'); 

               try {

                  $array_email = [];

                  foreach ($student->relationship as $key => $parent) {
                     if ($key == 0) $mailData['name'] = $parent->name;

                     array_push($array_email, $parent->email);
                     //   return view('emails.spp-mail')->with('mailData', $mailData);
                     $pdf = app('dompdf.wrapper');
                     $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $createBill])->setPaper('a4', 'portrait');
                     Mail::to($parent->email)->send(new EtcMail($mailData, "Tagihan " . $pdfBill->type . " " . $student->name .  " bulan ini, " . date('F Y') . " sudah dibuat.", $pdf));
                     // Mail::to($parent->email)->send(new UniformMail($mailData, "Tagihan Uniform " . $student->name . " sudah dibuat.", $pdf));

                  }

                  dispatch(new SendEmailJob($array_email, $createBill->type, $mailData, "Pemberitahuan Tagihan " . $createBill->type . " " . date('F Y') . ".", $createBill->id));

                  statusInvoiceMail::create([
                     'status' => true,
                     'bill_id' => $createBill->id,
                  ]);
               } catch (Exception $err) {

                  statusInvoiceMail::create([
                     'status' => false,
                     'bill_id' => $createBill->id,
                  ]);
               }
            }
         }


         info('Cron notification etc success at ' . now());
      } catch (Exception $err) {

         info('Cron notification etc error at ' . now());
         return dd($err);
      }
   }

   //material fee
   // public function materialFee()
   // {
   //    try {
   //       // Query untuk mengambil siswa yang memiliki material fee
   //       $query = Student::whereHas('material_fee')  // Pastikan ini sesuai dengan relasi di model Student
   //          ->with(['bill' => function ($query) {
   //             $query->where('type', 'Material Fee')
   //                ->where('deadline_invoice', '=', Carbon::now()->addDays(9)->format('Y-m-d'))
   //                ->where('paidOf', false);
   //          }, 'material_fee', 'relationship']);  // Tambahkan relasi material_fee

   //       // Log query SQL
   //       Log::info('SQL Query: ' . $query->toSql());
   //       Log::info('Query Parameters: ' . json_encode($query->getBindings()));

   //       $students = $query->get();
   //       Log::info('Data fetched: ' . $students->count() . ' students found with material fee.');

   //       foreach ($students as $student) {
   //          // Cek apakah sudah ada bill untuk bulan ini
   //          $existingBill = Bill::where('student_id', $student->id)
   //             ->where('type', 'Material Fee')
   //             ->whereMonth('created_at', now()->month)
   //             ->whereYear('created_at', now()->year)
   //             ->first();

   //          if (!$existingBill) {
   //             // Generate bill baru
   //             $materialFee = $student->material_fee;
   //             if ($materialFee) {
   //                $bill = new Bill();
   //                $bill->student_id = $student->id;
   //                $bill->type = 'Material Fee';
   //                $bill->amount = $materialFee->amount_installment;
   //                $bill->deadline_invoice = Carbon::now()->addDays(9);
   //                $bill->installment = $materialFee->installment;
   //                $bill->amount_installment = $materialFee->amount_installment;
   //                $bill->number_invoice = 'MF-' . date('Ymd') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT);
   //                $bill->save();

   //                Log::info('Created new bill for student: ' . $student->name);

   //                // Kirim email
   //                try {
   //                   $emails = $student->relationship->pluck('email')->toArray();
   //                   $mailData = [
   //                      'student' => $student,
   //                      'bill' => [$bill],
   //                      'past_due' => false,
   //                      'charge' => false,
   //                      'change' => false,
   //                      'is_paid' => false,
   //                   ];

   //                   $subject = "Material Fee Notification for " . $student->name;

   //                   $pdf = app('dompdf.wrapper');
   //                   $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $bill])
   //                      ->setPaper('a4', 'portrait');

   //                   foreach ($emails as $email) {
   //                      Mail::to($email)->send(new MaterialFeeMail($mailData, $subject, $pdf));
   //                      Log::info('Email sent to: ' . $email);
   //                   }
   //                } catch (\Exception $e) {
   //                   Log::error('Failed to send email: ' . $e->getMessage());
   //                }
   //             }
   //          } else {
   //             Log::info('Bill already exists for student: ' . $student->name);
   //          }
   //       }

   //       Log::info('Material Fee Notification process completed successfully.');
   //    } catch (\Exception $e) {
   //       Log::error('Material Fee Notification error: ' . $e->getMessage());
   //    }
   // }


   // yang digunakan tetapi masih belum sesuai untuk mengirimkan tagihan nya
   // public function materialFee()
   // {
   //    try {
   //       $query = Student::whereHas('material_fee')
   //          ->with(['bill' => function ($query) {
   //             $query->where('type', 'Material Fee')
   //                ->where('deadline_invoice', '=', Carbon::now()->addDays(9)->format('Y-m-d'))
   //                ->where('paidOf', false);
   //          }, 'material_fee', 'relationship']);

   //       Log::info('SQL Query: ' . $query->toSql());
   //       Log::info('Query Parameters: ' . json_encode($query->getBindings()));

   //       $students = $query->get();
   //       Log::info('Data fetched: ' . $students->count() . ' students found with material fee.');

   //       foreach ($students as $student) {
   //          // Count existing material fee bills for this student
   //          $existingBillsCount = Bill::where('student_id', $student->id)
   //             ->where('type', 'Material Fee')
   //             ->count();

   //          // Calculate current installment number (existing bills + 1)
   //          $currentInstallmentNumber = $existingBillsCount + 1;

   //          // Cek apakah sudah ada bill untuk bulan ini
   //          $existingBill = Bill::where('student_id', $student->id)
   //             ->where('type', 'Material Fee')
   //             ->whereMonth('created_at', now()->month)
   //             ->whereYear('created_at', now()->year)
   //             ->first();

   //          if (!$existingBill && $currentInstallmentNumber <= $student->material_fee->installment) {
   //             // Generate bill baru
   //             $materialFee = $student->material_fee;
   //             if ($materialFee) {
   //                $bill = new Bill();
   //                $bill->student_id = $student->id;
   //                $bill->type = 'Material Fee';
   //                $bill->amount = $materialFee->amount_installment;
   //                $bill->deadline_invoice = Carbon::now()->addDays(9);
   //                $bill->installment = $materialFee->installment;
   //                $bill->amount_installment = $materialFee->amount_installment;
   //                $bill->number_invoice = 'MF-' . date('Ymd') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT);
   //                $bill->save();

   //                Log::info('Created new bill for student: ' . $student->name . ' (Installment ' . $currentInstallmentNumber . ' of ' . $materialFee->installment . ')');

   //                // Kirim email
   //                try {
   //                   $emails = $student->relationship->pluck('email')->toArray();
   //                   $mailData = [
   //                      'student' => $student,
   //                      'bill' => [$bill],
   //                      'past_due' => false,
   //                      'charge' => false,
   //                      'change' => false,
   //                      'is_paid' => false,
   //                      'installment_info' => [
   //                         'current' => $currentInstallmentNumber,
   //                         'total' => $materialFee->installment
   //                      ]
   //                   ];

   //                   $subject = "Material Fee Notification for " . $student->name;

   //                   $pdf = app('dompdf.wrapper');
   //                   // $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $bill])
   //                   $pdf->loadView('components.student.materialfee.pdf', ['data' => $bill, 'installment_info' => [
   //                      'current' => $currentInstallmentNumber,
   //                      'total' => $student->material_fee->installment
   //                   ]])
   //                      ->setPaper('a4', 'portrait');

   //                   foreach ($emails as $email) {
   //                      Mail::to($email)->send(new MaterialFeeMail($mailData, $subject, $pdf));
   //                      Log::info('Email sent to: ' . $email);
   //                   }
   //                } catch (\Exception $e) {
   //                   Log::error('Failed to send email: ' . $e->getMessage());
   //                }
   //             }
   //          } else {
   //             Log::info('Bill already exists or all installments completed for student: ' . $student->name);
   //          }
   //       }

   //       Log::info('Material Fee Notification process completed successfully.');
   //    } catch (\Exception $e) {
   //       Log::error('Material Fee Notification error: ' . $e->getMessage());
   //    }
   // }


   // public function materialFee()
   // {
   //    try {
   //       $query = Student::whereHas('material_fee')
   //          ->with(['bill' => function ($query) {
   //             $query->where('type', 'Material Fee')
   //                ->where('deadline_invoice', '=', Carbon::now()->addDays(9)->format('Y-m-d'))
   //                ->where('paidOf', false);
   //          }, 'material_fee', 'relationship']);

   //       Log::info('SQL Query: ' . $query->toSql());
   //       Log::info('Query Parameters: ' . json_encode($query->getBindings()));

   //       $students = $query->get();
   //       Log::info('Data fetched: ' . $students->count() . ' students found with material fee.');

   //       foreach ($students as $student) {
   //          // Count existing material fee bills for this student
   //          $existingBillsCount = Bill::where('student_id', $student->id)
   //             ->where('type', 'Material Fee')
   //             ->count();

   //          // Calculate current installment number (existing bills + 1)
   //          $currentInstallmentNumber = $existingBillsCount + 1;

   //          // Cek apakah sudah ada bill untuk bulan ini
   //          $existingBill = Bill::where('student_id', $student->id)
   //             ->where('type', 'Material Fee')
   //             ->whereMonth('created_at', now()->month)
   //             ->whereYear('created_at', now()->year)
   //             ->first();

   //          if (!$existingBill && $currentInstallmentNumber <= $student->material_fee->installment) {
   //             // Generate bill baru
   //             $materialFee = $student->material_fee;
   //             if ($materialFee) {
   //                $bill = new Bill();
   //                $bill->student_id = $student->id;
   //                $bill->type = 'Material Fee';
   //                $bill->amount = $materialFee->amount_installment;
   //                $bill->deadline_invoice = Carbon::now()->addDays(9);
   //                $bill->installment = $materialFee->installment;
   //                $bill->amount_installment = $materialFee->amount_installment;
   //                $bill->number_invoice = 'MF-' . date('Ymd') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT);
   //                $bill->save();

   //                Log::info('Created new bill for student: ' . $student->name . ' (Installment ' . $currentInstallmentNumber . ' of ' . $materialFee->installment . ')');

   //                // Kirim email
   //                try {
   //                   $emails = $student->relationship->pluck('email')->toArray();
   //                   $mailData = [
   //                      'student' => $student,
   //                      'bill' => [$bill],
   //                      'past_due' => false,
   //                      'charge' => false,
   //                      'change' => false,
   //                      'is_paid' => false,
   //                      'installment_info' => [
   //                         'current' => $currentInstallmentNumber,
   //                         'total' => $materialFee->installment
   //                      ]
   //                   ];

   //                   // $subject = "Tagihan Material Fee " . $student->name;
   //                   $subject = "Tagihan Material Fee " . $student->name . " - " .
   //                      ($student->material_fee ? $student->material_fee->type : 'General') . " " .
   //                      $currentInstallmentNumber . "/" . $materialFee->installment;

   //                   $pdf = app('dompdf.wrapper');
   //                   $pdf->loadView('components.student.materialfee.pdf', ['data' => $bill, 'installment_info' => [
   //                      'current' => $currentInstallmentNumber,
   //                      'total' => $student->material_fee->installment
   //                   ]])
   //                      ->setPaper('a4', 'portrait');

   //                   foreach ($emails as $email) {
   //                      Mail::to($email)->send(new MaterialFeeMail($mailData, $subject, $pdf));
   //                      Log::info('Email sent to: ' . $email);
   //                   }
   //                } catch (\Exception $e) {
   //                   Log::error('Failed to send email: ' . $e->getMessage());
   //                }
   //             }
   //          } else {
   //             Log::info('Bill already exists or all installments completed for student: ' . $student->name);
   //          }
   //       }

   //       Log::info('Material Fee Notification process completed successfully.');
   //    } catch (\Exception $e) {
   //       Log::error('Material Fee Notification error: ' . $e->getMessage());
   //    }
   // }





   // bisa dan sesuai yang di inginkan tetapi masih ada yang kurang untuk bagian pengiriman ulang tagihan
   // public function materialFee()
   // {
   //    try {
   //       $students = Student::with(['material_fee', 'relationship'])
   //          ->whereHas('material_fee', function ($query) {
   //             $query->whereNotNull('installment'); // Pastikan hanya siswa dengan installment
   //          })->get();

   //       Log::info('Data fetched: ' . $students->count() . ' students found with material fee.');

   //       foreach ($students as $student) {
   //          $materialFee = $student->material_fee;
   //          if ($materialFee) {
   //             // Loop untuk jumlah installment
   //             for ($installmentNumber = 1; $installmentNumber <= $materialFee->installment; $installmentNumber++) {
   //                // Cek apakah tagihan sudah ada untuk installment ini
   //                $existingBill = Bill::where('student_id', $student->id)
   //                   ->where('type', 'Material Fee')
   //                   ->where('installment', $installmentNumber)
   //                   ->first();

   //                if (!$existingBill) {
   //                   // Generate tagihan baru
   //                   $bill = new Bill();
   //                   $bill->student_id = $student->id;
   //                   $bill->type = 'Material Fee';
   //                   $bill->amount = $materialFee->amount_installment;
   //                   $bill->deadline_invoice = Carbon::now()->addDays(9);
   //                   $bill->installment = $installmentNumber;
   //                   $bill->amount_installment = $materialFee->amount_installment;
   //                   $bill->number_invoice = 'MF-' . date('Ymd') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT) . '-' . $installmentNumber;
   //                   $bill->save();

   //                   Log::info('Generated bill for student: ' . $student->name . ' (Installment ' . $installmentNumber . ' of ' . $materialFee->installment . ')');

   //                   // Kirim email hanya untuk installment pertama
   //                   if ($installmentNumber === 1) {
   //                      try {
   //                         $emails = $student->relationship->pluck('email')->toArray();
   //                         $mailData = [
   //                            'student' => $student,
   //                            'bill' => [$bill],
   //                            'past_due' => false,
   //                            'charge' => false,
   //                            'change' => false,
   //                            'is_paid' => false,
   //                            'installment_info' => [
   //                               'current' => $installmentNumber,
   //                               'total' => $materialFee->installment
   //                            ]
   //                         ];

   //                         $subject = "Tagihan Material Fee " . $student->name . " - " . $installmentNumber . "/" . $materialFee->installment;

   //                         $pdf = app('dompdf.wrapper');
   //                         $pdf->loadView('components.student.materialfee.pdf', ['data' => $bill, 'installment_info' => [
   //                            'current' => $installmentNumber,
   //                            'total' => $materialFee->installment
   //                         ]])
   //                            ->setPaper('a4', 'portrait');

   //                         foreach ($emails as $email) {
   //                            Mail::to($email)->send(new MaterialFeeMail($mailData, $subject, $pdf));
   //                            Log::info('Email sent to: ' . $email);
   //                         }
   //                      } catch (\Exception $e) {
   //                         Log::error('Failed to send email: ' . $e->getMessage());
   //                      }
   //                   }
   //                }
   //             }
   //          } else {
   //             Log::info('No material fee found for student: ' . $student->name);
   //          }
   //       }

   //       Log::info('Material Fee Notification process completed successfully.');
   //    } catch (\Exception $e) {
   //       Log::error('Material Fee Notification error: ' . $e->getMessage());
   //    }
   // }


   // bisa dan digunakan
   // public function materialFee()
   // {
   //    try {
   //       $students = Student::with(['material_fee', 'relationship'])
   //          ->whereHas('material_fee', function ($query) {
   //             $query->whereNotNull('installment');
   //          })->get();

   //       Log::info('Data fetched: ' . $students->count() . ' students found with material fee.');

   //       foreach ($students as $student) {
   //          $materialFee = $student->material_fee;
   //          if ($materialFee) {
   //             // Find the latest unpaid installment
   //             $latestUnpaidBill = Bill::where('student_id', $student->id)
   //                ->where('type', 'Material Fee')
   //                ->where('paidOf', false)
   //                ->orderBy('installment', 'asc')
   //                ->first();

   //             // Get the current installment number to generate
   //             $currentInstallment = 1;
   //             if ($latestUnpaidBill) {
   //                $currentInstallment = $latestUnpaidBill->installment;
   //             } else {
   //                // If no unpaid bills exist, find the highest paid installment
   //                $lastPaidBill = Bill::where('student_id', $student->id)
   //                   ->where('type', 'Material Fee')
   //                   ->where('paidOf', true)
   //                   ->orderBy('installment', 'desc')
   //                   ->first();

   //                if ($lastPaidBill) {
   //                   $currentInstallment = $lastPaidBill->installment + 1;
   //                }
   //             }

   //             // Generate bills if needed
   //             for ($installmentNumber = 1; $installmentNumber <= $materialFee->installment; $installmentNumber++) {
   //                // Check if bill exists for this installment
   //                $existingBill = Bill::where('student_id', $student->id)
   //                   ->where('type', 'Material Fee')
   //                   ->where('installment', $installmentNumber)
   //                   ->first();

   //                if (!$existingBill) {
   //                   // Generate new bill
   //                   $bill = new Bill();
   //                   $bill->student_id = $student->id;
   //                   $bill->type = 'Material Fee';
   //                   $bill->amount = $materialFee->amount_installment;
   //                   $bill->deadline_invoice = Carbon::now()->addDays(9);
   //                   $bill->installment = $installmentNumber;
   //                   $bill->amount_installment = $materialFee->amount_installment;
   //                   $bill->number_invoice = 'MF-' . date('Ymd') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT) . '-' . $installmentNumber;
   //                   $bill->save();

   //                   Log::info('Generated bill for student: ' . $student->name . ' (Installment ' . $installmentNumber . ' of ' . $materialFee->installment . ')');
   //                }
   //             }

   //             // Send email for the current unpaid installment
   //             $billToEmail = Bill::where('student_id', $student->id)
   //                ->where('type', 'Material Fee')
   //                ->where('installment', $currentInstallment)
   //                ->first();

   //             if ($billToEmail && $currentInstallment <= $materialFee->installment) {
   //                try {
   //                   $emails = $student->relationship->pluck('email')->toArray();
   //                   $mailData = [
   //                      'student' => $student,
   //                      'bill' => [$billToEmail],
   //                      'past_due' => false,
   //                      'charge' => false,
   //                      'change' => false,
   //                      'is_paid' => false,
   //                      'installment_info' => [
   //                         'current' => $currentInstallment,
   //                         'total' => $materialFee->installment
   //                      ]
   //                   ];

   //                   // $subject = "Tagihan Material Fee " . $student->name . " - " . $currentInstallment . "/" . $materialFee->installment;

   //                   $subject = "Tagihan Material Fee {$student->name} bulan ini sudah dibuat";

   //                   $pdf = app('dompdf.wrapper');
   //                   $pdf->loadView('components.student.materialfee.pdf', [
   //                      'data' => $billToEmail,
   //                      'installment_info' => [
   //                         'current' => $currentInstallment,
   //                         'total' => $materialFee->installment
   //                      ]
   //                   ])->setPaper('a4', 'portrait');

   //                   foreach ($emails as $email) {
   //                      Mail::to($email)->send(new MaterialFeeMail($mailData, $subject, $pdf));
   //                      Log::info('Email sent to: ' . $email . ' for installment ' . $currentInstallment);
   //                   }
   //                } catch (\Exception $e) {
   //                   Log::error('Failed to send email: ' . $e->getMessage());
   //                }
   //             }
   //          } else {
   //             Log::info('No material fee found for student: ' . $student->name);
   //          }
   //       }

   //       Log::info('Material Fee Notification process completed successfully.');
   //    } catch (\Exception $e) {
   //       Log::error('Material Fee Notification error: ' . $e->getMessage());
   //    }
   // }


   // update terbaru
   // public function materialFee()
   // {
   //    try {
   //       $students = Student::with(['material_fee', 'relationship'])
   //          ->whereHas('material_fee', function ($query) {
   //             $query->whereNotNull('installment');
   //          })->get();

   //       Log::info('Data fetched: ' . $students->count() . ' students found with material fee.');

   //       foreach ($students as $student) {
   //          $materialFee = $student->material_fee;
   //          if ($materialFee) {
   //             // Find the latest unpaid installment
   //             $latestUnpaidBill = Bill::where('student_id', $student->id)
   //                ->where('type', 'Material Fee')
   //                ->where('paidOf', false)
   //                ->orderBy('installment', 'asc')
   //                ->first();

   //             // Get the current installment number to generate
   //             $currentInstallment = 1;
   //             if ($latestUnpaidBill) {
   //                $currentInstallment = $latestUnpaidBill->installment;
   //             } else {
   //                // If no unpaid bills exist, find the highest paid installment
   //                $lastPaidBill = Bill::where('student_id', $student->id)
   //                   ->where('type', 'Material Fee')
   //                   ->where('paidOf', true)
   //                   ->orderBy('installment', 'desc')
   //                   ->first();

   //                if ($lastPaidBill) {
   //                   $currentInstallment = $lastPaidBill->installment + 1;
   //                }
   //             }

   //             // Generate bills if needed
   //             for ($installmentNumber = 1; $installmentNumber <= $materialFee->installment; $installmentNumber++) {
   //                // Check if bill exists for this installment
   //                $existingBill = Bill::where('student_id', $student->id)
   //                   ->where('type', 'Material Fee')
   //                   ->where('installment', $installmentNumber)
   //                   ->first();

   //                if (!$existingBill) {
   //                   // Calculate the deadline date for this installment
   //                   $deadlineDate = Carbon::now()->startOfMonth();
   //                   // Add months based on installment number (subtract 1 since we start from current month)
   //                   $deadlineDate->addMonths($installmentNumber - 1);
   //                   // Set to the 10th of the month
   //                   $deadlineDate->setDay(10);

   //                   // Generate new bill
   //                   $bill = new Bill();
   //                   $bill->student_id = $student->id;
   //                   $bill->type = 'Material Fee';
   //                   $bill->amount = $materialFee->amount_installment;
   //                   $bill->deadline_invoice = $deadlineDate;
   //                   $bill->installment = $installmentNumber;
   //                   $bill->amount_installment = $materialFee->amount_installment;
   //                   $bill->number_invoice = 'MF-' . date('Ymd') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT) . '-' . $installmentNumber;
   //                   $bill->save();

   //                   Log::info('Generated bill for student: ' . $student->name . ' (Installment ' . $installmentNumber . ' of ' . $materialFee->installment . ')');
   //                }
   //             }

   //             // Send email for the current unpaid installment
   //             $billToEmail = Bill::where('student_id', $student->id)
   //                ->where('type', 'Material Fee')
   //                ->where('installment', $currentInstallment)
   //                ->first();

   //             if ($billToEmail && $currentInstallment <= $materialFee->installment) {
   //                try {
   //                   $emails = $student->relationship->pluck('email')->toArray();
   //                   $mailData = [
   //                      'student' => $student,
   //                      'bill' => [$billToEmail],
   //                      'past_due' => false,
   //                      'charge' => false,
   //                      'change' => false,
   //                      'is_paid' => false,
   //                      'installment_info' => [
   //                         'current' => $currentInstallment,
   //                         'total' => $materialFee->installment
   //                      ]
   //                   ];

   //                   $subject = "Tagihan Material Fee {$student->name} bulan ini sudah dibuat";

   //                   $pdf = app('dompdf.wrapper');
   //                   $pdf->loadView('components.student.materialfee.pdf', [
   //                      'data' => $billToEmail,
   //                      'installment_info' => [
   //                         'current' => $currentInstallment,
   //                         'total' => $materialFee->installment
   //                      ]
   //                   ])->setPaper('a4', 'portrait');

   //                   foreach ($emails as $email) {
   //                      Mail::to($email)->send(new MaterialFeeMail($mailData, $subject, $pdf));
   //                      Log::info('Email sent to: ' . $email . ' for installment ' . $currentInstallment);
   //                   }
   //                } catch (\Exception $e) {
   //                   Log::error('Failed to send email: ' . $e->getMessage());
   //                }
   //             }
   //          } else {
   //             Log::info('No material fee found for student: ' . $student->name);
   //          }
   //       }

   //       Log::info('Material Fee Notification process completed successfully.');
   //    } catch (\Exception $e) {
   //       Log::error('Material Fee Notification error: ' . $e->getMessage());
   //    }
   // }

   public function materialFee()
   {
      try {
         $students = Student::with(['material_fee', 'relationship'])
            ->whereHas('material_fee', function ($query) {
               $query->whereNotNull('installment');
            })->get();

         Log::info('Data fetched: ' . $students->count() . ' students found with material fee.');

         foreach ($students as $student) {
            $materialFee = $student->material_fee;
            if ($materialFee) {
               // Find the latest unpaid installment
               $latestUnpaidBill = Bill::where('student_id', $student->id)
                  ->where('type', 'Material Fee')
                  ->where('paidOf', false)
                  ->orderBy('installment', 'asc')
                  ->first();

               // Get the current installment number to generate
               $currentInstallment = 1;
               if ($latestUnpaidBill) {
                  $currentInstallment = $latestUnpaidBill->installment;
               } else {
                  // If no unpaid bills exist, find the highest paid installment
                  $lastPaidBill = Bill::where('student_id', $student->id)
                     ->where('type', 'Material Fee')
                     ->where('paidOf', true)
                     ->orderBy('installment', 'desc')
                     ->first();

                  if ($lastPaidBill) {
                     $currentInstallment = $lastPaidBill->installment + 1;
                  }
               }

               // Generate bills if needed
               for ($installmentNumber = 1; $installmentNumber <= $materialFee->installment; $installmentNumber++) {
                  // Check if bill exists for this installment
                  $existingBill = Bill::where('student_id', $student->id)
                     ->where('type', 'Material Fee')
                     ->where('installment', $installmentNumber)
                     ->first();

                  if (!$existingBill) {
                     DB::beginTransaction();
                     try {
                        // Calculate the deadline date for this installment
                        $deadlineDate = Carbon::now()->startOfMonth();
                        $deadlineDate->addMonths($installmentNumber - 1);
                        $deadlineDate->setDay(10);

                        // Generate new bill
                        $bill = new Bill();
                        $bill->student_id = $student->id;
                        $bill->type = 'Material Fee';
                        $bill->amount = $materialFee->amount_installment;
                        $bill->deadline_invoice = $deadlineDate;
                        $bill->installment = $installmentNumber;
                        $bill->amount_installment = $materialFee->amount_installment;
                        $bill->number_invoice = 'MF-' . date('Ymd') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT) . '-' . $installmentNumber;
                        $bill->save();

                        // Simpan ke table material_fee_installments
                        $materialFeeInstallment = new MaterialFeeInstallment();
                        $materialFeeInstallment->material_fee_id = $materialFee->id;
                        $materialFeeInstallment->bill_id = $bill->id;
                        $materialFeeInstallment->installment_number = $installmentNumber;
                        $materialFeeInstallment->save();

                        DB::commit();

                        Log::info('Generated bill and material fee installment for student: ' . $student->name .
                           ' (Installment ' . $installmentNumber . ' of ' . $materialFee->installment . ')');
                     } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Failed to generate bill and material fee installment: ' . $e->getMessage());
                        continue;
                     }
                  }
               }

               // Send email for the current unpaid installment
               $billToEmail = Bill::where('student_id', $student->id)
                  ->where('type', 'Material Fee')
                  ->where('installment', $currentInstallment)
                  ->first();

               if ($billToEmail && $currentInstallment <= $materialFee->installment) {
                  try {
                     $emails = $student->relationship->pluck('email')->toArray();
                     $mailData = [
                        'student' => $student,
                        'bill' => [$billToEmail],
                        'past_due' => false,
                        'charge' => false,
                        'change' => false,
                        'is_paid' => false,
                        'installment_info' => [
                           'current' => $currentInstallment,
                           'total' => $materialFee->installment
                        ]
                     ];

                     $subject = "Tagihan Material Fee {$student->name} bulan ini sudah dibuat";

                     $pdf = app('dompdf.wrapper');
                     $pdf->loadView('components.student.materialfee.pdf', [
                        'data' => $billToEmail,
                        'installment_info' => [
                           'current' => $currentInstallment,
                           'total' => $materialFee->installment
                        ]
                     ])->setPaper('a4', 'portrait');

                     foreach ($emails as $email) {
                        Mail::to($email)->send(new MaterialFeeMail($mailData, $subject, $pdf));
                        Log::info('Email sent to: ' . $email . ' for installment ' . $currentInstallment);
                     }
                  } catch (\Exception $e) {
                     Log::error('Failed to send email: ' . $e->getMessage());
                  }
               }
            } else {
               Log::info('No material fee found for student: ' . $student->name);
            }
         }

         Log::info('Material Fee Notification process completed successfully.');
      } catch (\Exception $e) {
         Log::error('Material Fee Notification error: ' . $e->getMessage());
      }
   }
}
