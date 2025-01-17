<?php

namespace App\Http\Controllers\Notification;

use Exception;
use App\Models\Bill;
use App\Models\Book;
use App\Mail\SppMail;
use App\Mail\BookMail;
use App\Mail\DemoMail;
use App\Models\Student;
use App\Mail\FeeRegisMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\PaymentSuccessMail;
use App\Jobs\SendPaymentReceived;
use App\Models\statusInvoiceMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\View\Components\Alert;

class NotificationPaymentSuccess extends Controller
{

   public function paymentSuccess($type = 'SPP')
   {
      DB::beginTransaction();
      date_default_timezone_set('Asia/Jakarta');
      try {
         //code...

         if ($type == 'etc') {

            $students = Student::with([
               'bill' => function ($query) {
                  $query
                     ->whereNotIn('type', ["SPP", "Capital Fee", "Paket", "Book", "Uniform"])
                     ->where('paid_date', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                     ->where('paidOf', true)
                     ->get();
               },
               'relationship'
            ])
               ->whereHas('bill', function ($query) {
                  $query
                     ->whereNotIn('type', ["SPP", "Capital Fee", "Paket", "Book", "Uniform"])
                     ->where('paid_date', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                     ->where('paidOf', true);
               })->get();
         } else {

            $students = Student::with([
               'bill' => function ($query) use ($type) {
                  $query
                     ->where('type', $type)
                     ->where('paid_date', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                     ->where('paidOf', true)
                     ->get();
               },
               'relationship'
            ])
               ->whereHas('bill', function ($query) use ($type) {
                  $query
                     ->where('type', $type)
                     ->where('paid_date', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'))
                     ->where('paidOf', true);
               })->get();
         }

         foreach ($students as $student) {

            foreach ($student->bill as $bill) {
               # code...
               $mailData = [
                  'student' => $student,
                  'bill' => [$bill],
               ];

               $pdfBill = Bill::with(['student' => function ($query) {
                  $query->with('grade');
               }, 'bill_collection', 'bill_installments'])
                  ->where('id', $bill->id)
                  ->first();

               $pdf = app('dompdf.wrapper');
               $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');

               $pdfReport = null;

               if ($pdfBill->installment) {

                  $pdfReport = app('dompdf.wrapper');
                  $pdfReport->loadView('components.bill.pdf.installment-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');
               }

               // return view('emails.payment-success')->with('mailData', $mailData);
               try {
                  //code...
                  foreach ($student->relationship as $relationship) {
                     $mailData['name'] = $relationship->name;
                     Mail::to($relationship->email)->send(new PaymentSuccessMail($mailData, "Payment for School Fee - " . date('F Y') . $type . " " . $student->name . " has confirmed!", $pdf, $pdfReport));
                     statusInvoiceMail::create([
                        'bill_id' => $pdfBill->id,
                        'status' => false,
                        'is_paid' => true,
                     ]);
                  }
               } catch (Exception) {

                  statusInvoiceMail::create([
                     'bill_id' => $pdfBill->id,
                     'status' => false,
                     'is_paid' => true,
                  ]);
               }
            }
         }

         DB::commit();
         info('Cron job send notification payment success at ' . now());
      } catch (Exception $err) {

         DB::rollBack();
         info('Cron job send notification payment error at ' . $err);
      }
   }


   // public function successClicked($bill_id)
   // {
   //    DB::beginTransaction();
   //    date_default_timezone_set('Asia/Jakarta');

   //    Log::info('=== Starting Payment Success Process ===');
   //    Log::info("Processing bill ID: {$bill_id}");

   //    try {
   //       $student = Student::with([
   //          'bill' => function ($query) use ($bill_id) {
   //             $query->where('id', $bill_id);
   //          },
   //          'relationship',
   //          'material_fee'
   //       ])
   //          ->whereHas('bill', function ($query) use ($bill_id) {
   //             $query->where('id', $bill_id);
   //          })
   //          ->first();

   //       if (!$student) {
   //          Log::error("Student not found for bill ID: {$bill_id}");
   //          throw new Exception("Student not found");
   //       }

   //       Log::info("Found student: {$student->name}");
   //       Log::info("Number of bills: " . $student->bill->count());

   //       foreach ($student->bill as $bill) {
   //          if (!empty($bill->type)) {
   //             Log::info("Processing bill - Type: {$bill->type}, Amount: {$bill->amount}");

   //             $installmentInfo = null;

   //             if ($bill->type === 'Material Fee') {
   //                $existingBillsCount = Bill::where('student_id', $student->id)
   //                   ->where('type', 'Material Fee')
   //                   ->where('id', '<=', $bill->id)
   //                   ->count();

   //                $installmentInfo = [
   //                   'current' => $existingBillsCount,
   //                   'total' => $student->material_fee ? $student->material_fee->installment : 0
   //                ];
   //             }

   //             $mailData = [
   //                'student' => $student,
   //                'bill' => [$bill],
   //                'installment_info' => $installmentInfo
   //             ];

   //             $pdfBill = Bill::with(['student' => function ($query) {
   //                $query->with(['grade', 'material_fee']);
   //             }, 'bill_collection', 'bill_installments'])
   //                ->where('id', $bill->id)
   //                ->first();

   //             try {
   //                $array_email = [];
   //                foreach ($student->relationship as $idx => $parent) {
   //                   if ($idx == 0) $mailData['name'] = $parent->name;
   //                   array_push($array_email, $parent->email);
   //                   Log::info("Added recipient email: {$parent->email}");
   //                }

   //                if (empty($array_email)) {
   //                   Log::error("No recipient emails found for student: {$student->name}");
   //                   throw new Exception("No recipient emails found");
   //                }

   //                $pdf = app('dompdf.wrapper');
   //                $pdf->loadView('components.student.materialfee.paid-pdf', [
   //                   'data' => $pdfBill,
   //                   'installment_info' => $installmentInfo
   //                ])->setPaper('a4', 'portrait');

   //                Log::info("PDF generated successfully");

   //                // $emailSubject = $bill->type === 'Material Fee' && $installmentInfo
   //                //    ? "Pembayaran Material Fee - Installment {$installmentInfo['current']} dari {$installmentInfo['total']} ({$student->name})"
   //                //    : "Pembayaran {$bill->type} {$student->name} Telah Dikonfirmasi!";

   //                $emailSubject = $bill->type === 'Material Fee' && $installmentInfo
   //                   ? "Pembayaran {$bill->type} {$student->name} Telah Dikonfirmasi!"
   //                   : "Pembayaran Monthly Fee {$student->name} Telah Dikonfirmasi!";

   //                Mail::to($array_email)->send(new PaymentSuccessMail($mailData, $emailSubject, $pdf, null));
   //                Log::info("Synchronous email sent successfully");

   //                dispatch(new SendPaymentReceived($array_email, $mailData, "Payment {$bill->type} {$student->name} has confirmed!", $pdfBill));
   //                Log::info("Async email job dispatched");
   //             } catch (Exception $err) {
   //                Log::error("Email sending failed: " . $err->getMessage());
   //                Log::error($err->getTraceAsString());

   //                statusInvoiceMail::create([
   //                   'bill_id' => $pdfBill->id,
   //                   'status' => false,
   //                   'is_paid' => true,
   //                ]);

   //                throw $err;
   //             }
   //          } else {
   //             Log::warning("Skipping bill with empty or undefined type.");
   //          }
   //       }

   //       DB::commit();
   //       Log::info('=== Payment Success Process Completed ===');
   //       return response()->json(['success' => true]);
   //    } catch (Exception $err) {
   //       DB::rollBack();
   //       Log::error("Process failed: " . $err->getMessage());
   //       Log::error($err->getTraceAsString());

   //       statusInvoiceMail::create([
   //          'bill_id' => $bill_id,
   //          'status' => false,
   //          'is_paid' => true,
   //       ]);

   //       return response()->json(['success' => false, 'text' => $err->getMessage()]);
   //    }
   // }


   public function successClicked($bill_id)
   {
      DB::beginTransaction();
      date_default_timezone_set('Asia/Jakarta');

      Log::info('=== Starting Payment Success Process ===');
      Log::info("Processing bill ID: {$bill_id}");

      try {
         $student = Student::with([
            'bill' => function ($query) use ($bill_id) {
               $query->where('id', $bill_id);
            },
            'relationship',
            'material_fee'
         ])
            ->whereHas('bill', function ($query) use ($bill_id) {
               $query->where('id', $bill_id);
            })
            ->first();

         if (!$student) {
            Log::error("Student not found for bill ID: {$bill_id}");
            throw new Exception("Student not found");
         }

         Log::info("Found student: {$student->name}");
         Log::info("Number of bills: " . $student->bill->count());

         foreach ($student->bill as $bill) {
            if (!empty($bill->type)) {
               Log::info("Processing bill - Type: {$bill->type}, Amount: {$bill->amount}");

               $installmentInfo = null;

               if ($bill->type === 'Material Fee') {
                  $existingBillsCount = Bill::where('student_id', $student->id)
                     ->where('type', 'Material Fee')
                     ->where('id', '<=', $bill->id)
                     ->count();

                  // Get the material fee subject/type
                  $materialFee = $student->material_fee;
                  Log::info("Material Fee data:", [
                     'material_fee' => $materialFee,
                     'type' => $materialFee ? $materialFee->type : 'not found'
                  ]);

                  $materialFeeType = $materialFee ? $materialFee->type : '';

                  $installmentInfo = [
                     'current' => $existingBillsCount,
                     'total' => $student->material_fee ? $student->material_fee->installment : 0,
                     'type' => $materialFeeType // Add this line
                  ];
               }

               $mailData = [
                  'student' => $student,
                  'bill' => [$bill],
                  'installment_info' => $installmentInfo
               ];

               $pdfBill = Bill::with(['student' => function ($query) {
                  $query->with(['grade', 'material_fee']);
               }, 'bill_collection', 'bill_installments'])
                  ->where('id', $bill->id)
                  ->first();

               try {
                  $array_email = [];
                  foreach ($student->relationship as $idx => $parent) {
                     if ($idx == 0) $mailData['name'] = $parent->name;
                     array_push($array_email, $parent->email);
                     Log::info("Added recipient email: {$parent->email}");
                  }

                  if (empty($array_email)) {
                     Log::error("No recipient emails found for student: {$student->name}");
                     throw new Exception("No recipient emails found");
                  }

                  $pdf = app('dompdf.wrapper');
                  if ($bill->type === 'Material Fee') {
                     // Load the Material Fee PDF view
                     $pdf->loadView('components.student.materialfee.paid-pdf', [
                        'data' => $pdfBill,
                        'installment_info' => $installmentInfo
                     ])->setPaper('a4', 'portrait');
                  } else {
                     // Load the standard Bill PDF view
                     $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])
                        ->setPaper('a4', 'portrait');
                  }


                  Log::info("PDF generated successfully");

                  // $emailSubject = $bill->type === 'Material Fee' && $installmentInfo
                  //    ? "Pembayaran Material Fee - Installment {$installmentInfo['current']} dari {$installmentInfo['total']} ({$student->name})"
                  //    : "Pembayaran {$bill->type} {$student->name} Telah Dikonfirmasi!";

                  $emailSubject = $bill->type === 'Material Fee' && $installmentInfo
                     ? "Pembayaran {$bill->type} {$student->name} Telah Dikonfirmasi!"
                     : "Pembayaran Monthly Fee {$student->name} Telah Dikonfirmasi!";

                  Mail::to($array_email)->send(new PaymentSuccessMail($mailData, $emailSubject, $pdf, null));
                  Log::info("Synchronous email sent successfully");

                  dispatch(new SendPaymentReceived($array_email, $mailData, "Payment {$bill->type} {$student->name} has confirmed!", $pdfBill));
                  Log::info("Async email job dispatched");
               } catch (Exception $err) {
                  Log::error("Email sending failed: " . $err->getMessage());
                  Log::error($err->getTraceAsString());

                  statusInvoiceMail::create([
                     'bill_id' => $pdfBill->id,
                     'status' => false,
                     'is_paid' => true,
                  ]);

                  throw $err;
               }
            } else {
               Log::warning("Skipping bill with empty or undefined type.");
            }
         }

         DB::commit();
         Log::info('=== Payment Success Process Completed ===');
         return response()->json(['success' => true]);
      } catch (Exception $err) {
         DB::rollBack();
         Log::error("Process failed: " . $err->getMessage());
         Log::error($err->getTraceAsString());

         statusInvoiceMail::create([
            'bill_id' => $bill_id,
            'status' => false,
            'is_paid' => true,
         ]);

         return response()->json(['success' => false, 'text' => $err->getMessage()]);
      }
   }



   public function sendPaymentSuccessNotification($bill_id)
   {
      try {
         // Validasi apakah bill_id valid
         if (!$bill_id) {
            throw new \InvalidArgumentException("Invalid bill ID provided.");
         }

         // Mulai transaksi database
         DB::beginTransaction();

         // Ambil data siswa dengan tagihan yang sesuai
         $student = Student::with(['bill' => function ($query) use ($bill_id) {
            $query->where('id', $bill_id);
         }, 'relationship'])->whereHas('bill', function ($query) use ($bill_id) {
            $query->where('id', $bill_id);
         })->first();

         // Jika tidak ada siswa ditemukan untuk bill ID yang diberikan, lemparkan pengecualian
         if (!$student) {
            throw new \Exception("Student not found for the provided bill ID.");
         }

         // Siapkan data email dan PDF
         foreach ($student->bill as $bill) {
            $mailData = [
               'student' => $student,
               'bill' => [$bill],
            ];

            $pdfBill = Bill::with(['student' => function ($query) {
               $query->with('grade');
            }, 'bill_collection', 'bill_installments'])->where('id', $bill->id)->first();

            $array_email = [];
            foreach ($student->relationship as $idx => $parent) {
               if ($idx == 0) {
                  $mailData['name'] = $parent->name;
               }
               array_push($array_email, $parent->email);
            }

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');

            $pdfReport = null;
            if ($pdfBill->installment) {
               $pdfReport = app('dompdf.wrapper');
               $pdfReport->loadView('components.emails.payment-success-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');
            }

            // Kirim email dan jadwalkan pekerjaan asinkron untuk mengirim pemberitahuan pembayaran
            Log::info('Sending email to: ' . json_encode($array_email));
            Mail::to($array_email)->send(new PaymentSuccessMail($mailData, "Payment for School Fee – " . date('F Y') . " " . $student->name . " has confirmed!", $pdf, $pdfReport));
            dispatch(new SendPaymentReceived($array_email, $mailData, "Payment " . $bill->type . " " . $student->name . " has confirmed!", $pdfBill));
         }

         // Commit transaksi
         DB::commit();

         // Log keberhasilan pengiriman pemberitahuan pembayaran
         info('Payment success notification sent successfully.');

         // Beri respons sukses
         return response()->json(['message' => 'Email Payment Confirmed successfully sent']);
      } catch (\Exception $err) {
         // Rollback transaksi jika terjadi kesalahan
         DB::rollBack();

         // Log kesalahan
         Log::error('Error sending payment success notification: ' . $err->getMessage());

         // Buat entri email status invoice jika terjadi kesalahan
         if (isset($pdfBill)) {
            statusInvoiceMail::create([
               'bill_id' => $pdfBill->id,
               'status' => false,
               'is_paid' => true,
            ]);
         }

         // Beri respons dengan pesan kesalahan
         return response()->json(['error' => 'Failed to send email: ' . $err->getMessage()], 500);
      }
   }
}
