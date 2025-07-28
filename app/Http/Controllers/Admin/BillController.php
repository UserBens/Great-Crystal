<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Notification\NotificationPaymentSuccess;
use App\Mail\ChargePaymentMail;
use App\Mail\PaymentSuccessMail;
use App\Mail\SppMail;
use App\Models\Accountcategory;
use App\Models\Accountnumber;
use App\Models\Bill;
use App\Models\BillCollection;
use App\Models\Book;
use App\Models\Book_student;
use App\Models\Grade;
use App\Models\InstallmentPaket;
use App\Models\Payment_grade;
use App\Models\Payment_materialfee;
use App\Models\statusInvoiceMail;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PDO;

class BillController extends Controller
{

   public function index(Request $request)
   {
      try {
         $user = Auth::user();
         session()->flash('preloader', true);
         session()->flash('page', (object)[
            'page' => 'Bills',
            'child' => 'database bills'
         ]);

         $bill = Bill::select('type', DB::raw('count(*) as total'))->groupBy('type')->get();

         $grades = Grade::orderBy('id', 'asc')->get();

         $form = (object) [
            'grade' => $request->grade && $request->grade !== 'all' ? $request->grade : null,
            'type' => $request->type && $request->type !== 'all' ? $request->type : null,
            'invoice' => $request->invoice && $request->invoice !== 'all' ? $request->invoice : null,
            'status' => $request->status && $request->status !== 'all' ? $request->status : null,
            'search' => $request->search ? $request->search : null,
            'page' => $request->page ? $request->page : null,
            'from_bill' => $request->from_bill ? $request->from_bill : null,
            'to_bill' => $request->to_bill ? $request->to_bill : null,
         ];

         $flag_date = true;

         if ($form->search || $request->page || $form->from_bill || $form->to_bill || $request->grade && $request->type && $request->invoice && $request->status) {

            $data = new Bill();
            $data = $data->with(['student' => function ($query) {
               $query->with('grade')->get();
            }]);

            if ($form->grade) {
               $data = $data->whereHas('student', function ($query) use ($form) {
                  $query
                     ->where('name', 'LIKE', '%' . $form->search . '%')
                     ->where('grade_id', (int)$form->grade);
               });
            }

            if ($form->from_bill) {
               $explode_f = explode('/', $form->from_bill);
               $date_f = $explode_f[2] . '-' . $explode_f[1] . '-' . $explode_f[0];

               $f_carbon = Carbon::createFromDate($date_f, 'Asia/Jakarta');
               $f_carbon->setTime(0, 0, 0);
               $f_carbon->setTimezone('Asia/Jakarta');
               $f_formated = $f_carbon->format('Y-m-d 00:00:00');
            }

            if ($form->to_bill) {
               $explode_t = explode('/', $form->to_bill);
               $date_t = $explode_t[2] . '-' . $explode_t[1] . '-' . $explode_t[0];

               $t_carbon = Carbon::createFromDate($date_t, 'Asia/Jakarta');
               $t_carbon->setTime(0, 0, 0);
               $t_carbon->setTimezone('Asia/Jakarta');
               $t_formated = $t_carbon->format('Y-m-d 00:00:00');
            }

            if ($form->from_bill && $form->to_bill) {
               $data = $data->whereRaw("created_at BETWEEN '{$f_formated}' AND '{$t_formated}'");
               $form->from_bill = $explode_f[0] . '/' . $explode_f[1] . '/' . $explode_f[2];
               $form->to_bill = $explode_t[0] . '/' . $explode_t[1] . '/' . $explode_t[2];

               $flag_date = $f_carbon->timestamp > $t_carbon->timestamp ? false : true;
            } else if ($form->from_bill) {
               $data = $data->whereDate('created_at', '>=', $f_formated);
               $form->from_bill = $explode_f[0] . '/' . $explode_f[1] . '/' . $explode_f[2];
            } else if ($form->to_bill) {
               $data = $data->whereDate('created_at', '<=', $t_formated);
               $form->to_bill = $explode_t[0] . '/' . $explode_t[1] . '/' . $explode_t[2];
            }

            if ($form->type) {
               if (strtolower($form->type) != 'others') {
                  $data = $data->where('type', $form->type);
               } else {
                  $data = $data->whereNotIn('type', ['SPP', "Capital Fee", "Paket", "Uniform", "Book"]);
               }
            }

            if ($form->status) {
               $statusPaid = $form->status == 'true' ? true : false;
               $data = $data->where('paidOf', $statusPaid);
            }

            if ($form->invoice) {
               if (is_numeric($form->invoice)) {
                  $data = $data
                     ->where('deadline_invoice', '<=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays((int)$form->invoice)->format('y-m-d'))
                     ->where('deadline_invoice', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->format('y-m-d'));
               } else {
                  if ($form->invoice == 'tommorow') {
                     $data = $data->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(1)->format('y-m-d'));
                  } else {
                     $operator = $form->invoice == 'today' ? '=' : '<';
                     $data = $data->where('deadline_invoice', $operator, Carbon::now()->setTimezone('Asia/Jakarta')->format('y-m-d'));
                  }
               }
            }

            if ($user->role == 'admin') {
               $data = $data->where('created_by', 'admin');
            }

            if ($form->search) {
               $data = $data->whereHas('student', function ($query) use ($form) {
                  $query->where('name', 'LIKE', '%' . $form->search . '%')->orWhere('number_invoice', 'LIKE', '%' . $form->search . '%')->orderBy('id');
               });
            }

            $data = $data->orderBy('id', 'desc')->paginate(25);
         } else {
            if ($user->role == 'admin') {
               $data = Bill::with(['student' => function ($query) {
                  $query->with('grade')->get();
               }])
                  ->where('created_by', 'admin')
                  ->orderBy('updated_at', 'desc')
                  ->paginate(15);
            } else {
               $data = Bill::with(['student' => function ($query) {
                  $query->with('grade')->get();
               }])
                  ->orderBy('updated_at', 'desc')
                  ->paginate(25);
            }
         }

         return view('components.bill.data-bill')
            ->with('data', $data)
            ->with('grade', $grades)
            ->with('form', $form)
            ->with('bill', $bill)
            ->with('flag_date', $flag_date);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function payCharge(Request $request, $billId)
   {
      try {
         DB::beginTransaction();

         $bill = Bill::with(['student.grade'])->findOrFail($billId);

         if ($bill->charge <= 0) {
            return response()->json([
               'success' => false,
               'message' => 'This bill has no charge to pay'
            ], 400);
         }

         // Update bill status to paid
         $bill->paidOf = true;
         $bill->paid_date = Carbon::now('Asia/Jakarta');
         $bill->save();

         // Prepare email data
         $mailData = [
            'bill' => [$bill],
            'student' => $bill->student,
            'charge_amount' => $bill->charge,
            'total_amount' => $bill->amount + $bill->charge,
            'payment_date' => Carbon::now('Asia/Jakarta')->format('d M Y H:i:s'),
            'invoice_number' => $bill->number_invoice,
            'past_due' => false,
            'charge' => true,
            'change' => false,
            'is_paid' => true,
            'name' => $bill->student->name
         ];

         $subject = 'Charge Payment Notification - ' . $bill->student->name . ' - ' . $bill->type;

         // Generate PDF menggunakan dompdf wrapper (sesuai dengan pattern yang ada)
         $pdfBill = Bill::with(['student' => function ($query) {
            $query->with('grade');
         }, 'bill_collection', 'bill_installments'])->where('id', $billId)->first();

         $pdf = app('dompdf.wrapper');
         $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');

         // Optional: Generate PDF Report jika diperlukan (untuk installment)
         $pdfReport = null;
         if ($pdfBill->installment) {
            $pdfReport = app('dompdf.wrapper');
            $pdfReport->loadView('components.emails.payment-success-pdf', ['data' => $pdfBill])->setPaper('a4', 'portrait');
         }

         // Optional: Generate PDF Report jika diperlukan
         // $pdfReport = PDF::loadView('pdf.report', $mailData);

         // Send email notification dengan PDF attachment
         Mail::to('benedictus.radyan@great.sch.id')->send(
            new ChargePaymentMail($mailData, $subject, $pdf, $pdfReport)
         );

         DB::commit();

         return response()->json([
            'success' => true,
            'message' => 'Charge payment processed successfully and notification sent to accounting'
         ]);
      } catch (Exception $e) {
         DB::rollBack();
         return response()->json([
            'success' => false,
            'message' => 'Failed to process charge payment: ' . $e->getMessage()
         ], 500);
      }
   }


   public function chooseStudent(Request $request)
   {

      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'create bills'
      ]);

      try {
         //code...

         $form = (object) [
            'sort' => $request->sort ? $request->sort : null,
            'order' => $request->order ? $request->order : null,
            'search' => $request->search ? $request->search : null,
            'grade_id' => $request->grade_id ? $request->grade_id : null,
         ];

         $grade = Grade::orderBy('id', 'asc')->get();

         // return $form;

         $data = [];
         $order = $request->sort ? $request->sort : 'desc';

         $dataModel = new Student();
         $dataModel = $dataModel->with('grade')->where('is_active', true);

         if ($form->search || $request->grade_id && $request->order && $request->sort) {

            $data = $dataModel;

            if ($request->grade_id !== 'all') {

               $data = $data->where('grade_id', $form->grade_id);
            }

            if ($order && $request->sort) {

               $data = $data->orderBy($request->order, $order);
            }

            if ($form->search) {

               $data = $data->where('name', 'LIKE', '%' . $form->search . '%');
            }


            $data = $data->get();
         } else {

            $data = $dataModel->orderBy('created_at', $order)->get();
         }


         return view('components.bill.choose-bill')->with('data', $data)->with('form', $form)->with('grade', $grade);
      } catch (Exception $err) {
         throw $err;
         return abort(500, 'Internal server error');
      }
   }


   public function pageCreateBill($id)
   {

      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'create bills'
      ]);

      try {
         //code...
         $data = Student::with(['grade'])->where('unique_id', $id)->first();
         $monthDefault = Carbon::now()->addMonth()->setTimezone('Asia/Jakarta')->format('d/m/Y');

         return view('components.bill.spp.create-bill')->with('data', $data)->with('monthDefault', $monthDefault);
      } catch (Exception $err) {
         return dd($err);
      }
   }


   // public function actionCreateBill(Request $request, $id)
   // {
   //    session()->flash('page', (object)[
   //       'page' => 'Bills',
   //       'child' => 'database bills'
   //    ]);

   //    session()->flash('preloader', true);

   //    try {
   //       //code...

   //       $student = Student::with('relationship')->where('id', $id)->first();
   //       date_default_timezone_set('Asia/Jakarta');
   //       $user = Auth::user();
   //       $dateFormat = new RegisterController;

   //       $rules = [
   //          'student_id' => $id,
   //          'subject' => $request->subject,
   //          'type' => $request->type,
   //          'description' => $request->description,
   //          'amount' => $request->amount ? (int)str_replace(".", "", $request->amount) : null,
   //          'created_by' => $user->role == 'admin' ? 'admin' : 'accounting',
   //          'deadline_invoice' => $request->deadline_invoice ? $dateFormat->changeDateFormat($request->deadline_invoice) : null,
   //       ];

   //       info($rules);

   //       $validator = Validator::make($rules, [
   //          'type' => 'required|string|min:3',
   //          'subject' => 'required|string|min:3',
   //          'description' => 'nullable|string|min: 10',
   //          'amount' => 'required|integer|min:10000',
   //          'deadline_invoice' => 'required:date',
   //       ]);

   //       if ($validator->fails()) {
   //          session()->flash('page', (object)[
   //             'page' => 'Bills',
   //             'child' => 'create bills'
   //          ]);
   //          return redirect('/admin/bills/create-bills/' . $student->unique_id)->withErrors($validator->errors())->withInput($rules);
   //       }

   //       Bill::create($rules);

   //       return redirect('/admin/bills');
   //    } catch (Exception $err) {
   //       //throw $th;
   //       return dd($err);
   //    }
   // }

   public function actionCreateBill(Request $request, $id)
   {
      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      session()->flash('preloader', true);

      try {
         $student = Student::with('relationship')->where('id', $id)->first();
         date_default_timezone_set('Asia/Jakarta');
         $user = Auth::user();
         $dateFormat = new RegisterController;

         $rules = [
            'student_id' => $id,
            'subject' => $request->subject,
            'type' => $request->type,
            'description' => $request->description,
            'amount' => $request->amount ? (int)str_replace(".", "", $request->amount) : null,
            'created_by' => $user->role == 'admin' ? 'admin' : 'accounting',
            'deadline_invoice' => $request->deadline_invoice ? $dateFormat->changeDateFormat($request->deadline_invoice) : null,
         ];

         $validator = Validator::make($rules, [
            'type' => 'required|string|min:3',
            'subject' => 'required|string|min:3',
            'description' => 'nullable|string|min:10',
            'amount' => 'required|integer|min:10000',
            'deadline_invoice' => 'required|date',
         ]);

         if ($validator->fails()) {
            session()->flash('page', (object)[
               'page' => 'Bills',
               'child' => 'create bills'
            ]);
            return redirect('/admin/bills/create-bills/' . $student->unique_id)
               ->withErrors($validator->errors())
               ->withInput($rules);
         }

         // Generate number_invoice unik
         $prefix = date('Y/m');
         $lastBill = Bill::where('number_invoice', 'like', $prefix . '/%')
            ->orderBy('number_invoice', 'desc')
            ->first();

         if ($lastBill) {
            $lastNumber = (int)substr($lastBill->number_invoice, strrpos($lastBill->number_invoice, '/') + 1);
            $newNumber = $lastNumber + 1;
         } else {
            $newNumber = 1;
         }

         $number_invoice = $prefix . '/' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

         // Tambahkan ke data yang disimpan
         $rules['number_invoice'] = $number_invoice;

         Bill::create($rules);

         return redirect('/admin/bills')->with('success', 'Bill successfully created.');
      } catch (Exception $err) {
         return dd($err); // Debug jika error
      }
   }



   public function detailPayment($id)
   {
      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      try {
         $accountNumbers = Accountnumber::all();
         $accountCategory = Accountcategory::all();

         $data = Bill::with([
            'student' => function ($query) {
               $query->with('grade')->get();
            },
            'bill_collection',
            'bill_installments',
            'material_fee_installment.material_fee' // Add this line
         ])->where('id', $id)->first();

         $selectedAccountId = $data->deposit_account_id;

         return view('components.bill.spp.detail-spp', [
            'data' => $data,
            'accountNumbers' => $accountNumbers,
            'selectedAccountId' => $selectedAccountId,
            'accountCategory' => $accountCategory
         ]);
      } catch (Exception $err) {
         Log::error('Error in detailPayment: ' . $err->getMessage());
         Log::error('Stack trace: ' . $err->getTraceAsString());

         if (config('app.debug')) {
            // In development, show detailed error
            dd($err);
         }
         return abort(500, 'An error occurred while processing your request');
      }
   }


   public function chooseaccountnumber(Request $request)
   {
      try {
         // Validasi input
         $request->validate([
            'id' => 'required|integer',
            'deposit_account_id' => 'required|integer'
         ]);

         // Temukan bill berdasarkan ID
         $bill = Bill::find($request->id);
         if ($bill) {
            // Update deposit_account_id dan simpan
            // $bill->deposit_account_id = $request->deposit_account_id;
            $bill->new_deposit_account_id = $request->deposit_account_id; // Update new_deposit_account_id
            $bill->save();

            // Log info
            Log::info('Bill Updated', ['id' => $bill->id, 'deposit_account_id' => $bill->deposit_account_id]);

            // Redirect ke halaman yang sesuai dengan pesan sukses
            return redirect()->back()->with('success', 'Choose Account number successfully!');
         }

         // Jika tidak ditemukan, tampilkan pesan error
         return redirect()->back()->withErrors(['error' => 'Bill not found.']);
      } catch (\Exception $e) {
         // Log error
         Log::error('Error updating account number: ' . $e->getMessage());

         // Redirect dengan pesan error
         return redirect()->back()->withErrors(['error' => 'Failed to update account number. Please try again.']);
      }
   }

   public function storeAccount(Request $request)
   {
      try {
         // Validasi input
         $request->validate([
            'name' => 'required',
            'account_no' => ['required', 'regex:/^\d{3}\.\d{3}$/'],
            'account_category_id' => 'required',
            // 'description' => 'required',
         ]);

         // Buat data akun baru
         Accountnumber::create([
            'name' => $request->name,
            'account_no' => $request->account_no,
            'account_category_id' => $request->account_category_id,
            'description' => $request->description,
         ]);

         // Redirect ke halaman indeks akun dengan pesan sukses
         return redirect()->back()->with('success', 'Account Number created successfully!');
      } catch (\Illuminate\Database\QueryException $ex) {
         $errorMessage = 'Database error occurred. Please try again later.';
         if ($ex->errorInfo[1] == 1062) {
            $errorMessage = "The account name or number already exists.";
         }
         return redirect()->back()->withInput()->with('error', $errorMessage);
      }
   }

   public function paidOf($id)
   {
      DB::beginTransaction();

      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      try {
         //code...


         $bill = Bill::with('student')->where('id', $id)->first();

         if (!$bill) {

            DB::rollBack();
            return (object) [
               'success' => false,
               'text' => 'Id bill with ' . $id . ' not found !!!'
            ];
         }


         if ($bill->type == 'Paket') {
            $books = Book::where('grade_id', $bill->student->grade_id)->get();

            foreach ($books as $book) {

               $bookExist = Book_student::where('student_id', $bill->student_id)->where('book_id', $book->id)->first();

               if (!$bookExist) {
                  Book_student::create([
                     'student_id' => $bill->student_id,
                     'book_id' => $book->id,
                  ]);
               }
            }
         }

         Bill::where('id', $id)->update([
            'paidOf' => true,
            'paid_date' => Carbon::now()->setTimezone('Asia/Jakarta'),
         ]);

         $sendEmail = new NotificationPaymentSuccess;
         $sendEmail->successClicked($id);

         DB::commit();

         return (object) [
            'success' => true,
         ];
      } catch (Exception $err) {
         //throw $th;

         info('error paid of : ' . $err->getMessage());
         DB::rollBack();
         return (object) [
            'success' => false,
            'text' => $err->getMessage(),
         ];
      }
   }

   public function paidOfBook($bill_id, $student_id)
   {
      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      try {
         DB::beginTransaction();
         //code...
         $bill = Bill::with(['bill_collection'])
            ->where('id', $bill_id)
            ->first();

         foreach ($bill->bill_collection as $el) {
            if ($el->book_id) {
               $bookExist = Book_student::where('student_id', $student_id)->where('book_id', $el->book_id)->first();
               if (!$bookExist) {
                  Book_student::create([
                     'book_id' => $el->book_id,
                     'student_id' => $student_id,
                  ]);
               }
            }
         }

         Bill::where('id', $bill_id)->update([
            'paidOf' => true,
            'paid_date' => Carbon::now()->setTimezone('Asia/Jakarta'),
         ]);


         $sendEmail = new NotificationPaymentSuccess;
         $sendEmail->successClicked((int)$bill_id);

         DB::commit();

         return (object) [
            'success' => true,
         ];
      } catch (Exception $err) {
         DB::rollBack();
         return (object) [
            'success' => false,
            'errors' => $err,
         ];
      }
   }


   public function pageChangePaket($student_id, $bill_id)
   {
      try {
         //code...
         session()->flash('page', (object)[
            'page' => 'Bills',
            'child' => 'database bills'
         ]);

         $checkBill = Bill::where('id', $bill_id)->first();


         if (!$checkBill || $checkBill->type !== 'Paket') {
            return redirect('/admin/bills/detail-payment/' . $bill_id)->withErrors([
               'bill' => 'This is not a paket type of bills so you can`t edit it !!!',
            ]);
         }

         $student = Student::with(['grade' => function ($query) {
            $query->with(['uniform' => function ($query) {
               $query->where('type', 'Uniform')->get();
            }]);
         }, 'bill' => function ($query) use ($bill_id) {
            $query->where('id', $bill_id);
         }])
            ->where('unique_id', $student_id)
            ->first(['id', 'name', 'grade_id']);

         if (sizeof($student->bill) < 1) {
            return redirect('/admin/bills')->withErrors([
               'bill' => 'This is wrong paket from id ' . $student->unique_id . ' so you can`t edit it !!!',
            ]);
         }

         $uniform = $student->grade->uniform ? $student->grade->uniform : null;

         $data = Book::where('grade_id', $student->grade_id)
            ->orderBy('name', 'asc')
            ->get();


         // return $data;
         return view('components.bill.change-paket.select-book')
            ->with('data', $data)
            ->with('student', $student)
            ->with('bill_id', $bill_id)
            ->with('uniform', $uniform);
      } catch (Exception $err) {
         return dd($err);
      }
   }


   public function actionChangePaket(Request $request, $bill_id, $student_id)
   {
      DB::beginTransaction();

      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);
      session()->flash('preloader', true);

      try {


         $bookArray = $request->except(['_token', '_method', 'installment_book', 'installment_uniform']);
         $uniform = $request->only('uniform');

         $totalAmountBook = 0;

         if (sizeof($bookArray) < 1 && sizeof($uniform) < 1) {
            return redirect()->back()->withErrors([
               'bill' => 'Checklist book or uniform are required !!!',
            ]);
         }

         $checkBill = Bill::where('id', $bill_id)->first();

         if (!$checkBill) {
            return redirect()->back()->withErrors([
               'bill' => 'Bill id not found !!!',
            ]);
         }

         $bookName = [];
         $bookId = [];

         foreach ($bookArray as $key => $el) {

            if ($key != "uniform") {

               $book = Book::where('id', (int)$el)->first();

               BillCollection::create([
                  'bill_id' => $bill_id,
                  'book_id' => $book->id,
                  'type' => 'Book',
                  'name' => $book->name,
                  'amount' => $book->amount,
               ]);

               $totalAmountBook += $book->amount;
               array_push($bookName, $book->name);
               array_push($bookId, $book->id);
            } else {

               $uniformGrade = Payment_grade::where('id', (int)$el)->first();
               BillCollection::create([
                  'bill_id' => $bill_id,
                  'book_id' => NULL,
                  'type' => 'Uniform',
                  'name' => "Uniform",
                  'amount' => $uniformGrade->amount,
               ]);

               $totalAmountBook += $uniformGrade->amount;
            }
         }

         $flagBookCreate = false;

         if (sizeof($bookName) > 0) {


            Bill::where('id', $bill_id)
               ->update([
                  'type' => 'Book',
                  'subject' => sizeof($uniform) < 1 ? 'Book' : 'Book and uniform',
                  'amount' => $totalAmountBook + $checkBill->charge,
                  'date_change_bill' => now(),
               ]);
            $flagBookCreate = true;
         }


         if (!$flagBookCreate) {
            foreach ($uniform as $el) {
               $uniform = Payment_grade::where('id', (int)$el)->first();
               Bill::where('id', $bill_id)
                  ->update([
                     'type' => 'Uniform',
                     'subject' => 'Uniform ' . date("Y"),
                     'amount' => $uniform->amount + $checkBill->charge,
                     'date_change_bill' => now(),
                  ]);
            }
         }

         DB::commit();

         session()->flash('change_type_paket');

         return redirect('/admin/bills');
      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }

   public function pagePaketInstallment($bill_id)
   {

      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      try {
         //code...

         $bill = Bill::with('student')->where('id', $bill_id)->first();

         // return $bill;

         if (!$bill) {

            return redirect()->back()->withErrors([
               'bill' => [
                  'Bill not found !!!',
               ],
            ]);
         }

         return view('components.bill.change-paket.intallment-paket')->with('data', $bill);
      } catch (Exception $err) {

         return dd($err);
      }
   }


   public function actionPaketInstallment(Request $request, $bill_id)
   {

      DB::beginTransaction();

      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      session()->flash('preloader', true);

      try {

         $bill = Bill::with('student')->where('id', $bill_id)->first();

         if (!$bill) {

            DB::rollBack();
            return redirect()->back()->withErrors([
               'bill' => [
                  'Bill not found !!!',
               ],
            ]);
         }

         $rules = $request->only('installment');

         $validator = Validator::make($rules, [
            'installment' => 'required|integer|max:12|min:2',
         ]);

         if ($validator->fails()) {
            DB::rollBack();
            return redirect()->back()->withErrors($validator->messages())->withInput($rules);
         }

         if ((int)$bill->amount / (int)$request->installment % 1_000 == 0) {

            $billPerMonth = $bill->amount / $request->installment;
         } else {
            $billPerMonth = ceil((int)$bill->amount / (int)$request->installment);
            $billPerMonth += (1_000 - ($billPerMonth % 1_000));
         }


         $lastBillPerMonth = $bill->amount % $billPerMonth == 0 ? $billPerMonth : $bill->amount % $billPerMonth;

         $main_id = [];

         for ($i = 1; $i <= $request->installment; $i++) {

            if ($i == 1) {

               Bill::where('id', $bill->id)->update([
                  'subject' => $i,
                  'installment' => $request->installment,
                  'amount_installment' => $billPerMonth + $bill->charge,
                  'date_change_bill' => now(),
               ]);


               array_push($main_id, $bill->id);

               continue;
            }

            if ($i == $request->installment) {
               $currentDate = $bill->deadline_invoice;
               $newDate = date('Y-m-10', strtotime('+' . ($i - 1) . ' month', strtotime($currentDate)));

               $bill_child = Bill::create([
                  'student_id' => $bill->student_id,
                  'type' => 'Paket',
                  'subject' => $i,
                  'amount' => $bill->amount,
                  'paidOf' => false,
                  'discount' => null,
                  'installment' => $request->installment,
                  'amount_installment' => $lastBillPerMonth,
                  'deadline_invoice' => $newDate,

               ]);
               array_push($main_id, $bill_child->id);
               continue;
            }


            $currentDate = $bill->deadline_invoice;
            $newDate = date('Y-m-10', strtotime('+' . ($i - 1) . ' month', strtotime($currentDate)));

            $bill_child = Bill::create([
               'student_id' => $bill->student_id,
               'type' => 'Paket',
               'subject' => $i,
               'amount' => $bill->amount,
               'paidOf' => false,
               'discount' => null,
               'installment' => $request->installment,
               'amount_installment' => $billPerMonth,
               'deadline_invoice' => $newDate,

            ]);
            array_push($main_id, $bill_child->id);
         }

         foreach ($main_id as $el) {
            foreach ($main_id as $child_id) {
               InstallmentPaket::create([
                  'main_id' => $el,
                  'child_id' => $child_id,
               ]);
            }
         }


         $books = Book::where('grade_id', $bill->student->grade_id)->get();

         foreach ($books as $book) {

            Book_student::create([
               'student_id' => $bill->student_id,
               'book_id' => $book->id,
            ]);
         }

         session()->flash('create_installment_bill');

         DB::commit();


         return redirect('/admin/bills/edit-installment-paket/' . $bill->id);
      } catch (Exception $err) {

         return dd($err);
      }
   }

   public function pagePdf($bill_id)
   {

      session()->flash('page', (object)[
         'page' => 'Bills',
         'child' => 'database bills'
      ]);

      try {


         $data = Bill::with(['student' => function ($query) {
            $query->with('grade');
         }, 'bill_collection', 'bill_installments'])
            ->where('id', $bill_id)
            ->first();

         $nameFormatPdf = Carbon::now()->format('YmdHis') . mt_rand(1000, 9999) . '_' . date('d-m-Y') . '_' . $data->type . '_' . $data->student->name . '.pdf';

         $pdf = app('dompdf.wrapper');
         $pdf->loadView('components.bill.pdf.paid-pdf', ['data' => $data])->setPaper('a4', 'portrait');
         return $pdf->stream($nameFormatPdf);
      } catch (Exception $err) {
         return abort(500);
      }
   }

   public function reportInstallmentPdf($bill_id)
   {
      try {

         $data = Bill::with([
            'student' => function ($query) {
               $query->with('grade');
            },
            'bill_installments' => function ($query) {
               $query->orderBy('id');
            }
         ])
            ->where('id', $bill_id)
            ->first();

         if (!$data || sizeof($data->bill_installments) <= 0) {
            return redirect('/admin/bills/detail-payment/' . $bill_id);
         }

         $nameFormatPdf = Carbon::now()->format('YmdHis') . mt_rand(1000, 9999) . '_' . date('d-m-Y') . '_' . $data->type . '_' . $data->student->name . '.pdf';

         $pdf = app('dompdf.wrapper');
         $pdf->loadView('components.bill.pdf.installment-pdf', ['data' => $data])->setPaper('a4', 'portrait');

         return $pdf->stream($nameFormatPdf);
      } catch (Exception $err) {

         abort(500);
      }
   }

   public function reportMaterialInstallmentPdf($material_fee_id)
   {
      try {
         $data = Payment_materialfee::with([
            'student' => function ($query) {
               $query->with('grade');
            },
            'installment_bills' => function ($query) {
               $query->with('bill')
                  ->orderBy('installment_number');
            }
         ])
            ->where('id', $material_fee_id)
            ->first();

         if (!$data || !$data->installment_bills->count()) {
            return redirect()->back();
         }

         $nameFormatPdf = Carbon::now()->format('YmdHis') .
            mt_rand(1000, 9999) . '_' .
            date('d-m-Y') . '_' .
            'MaterialFee' . '_' .
            $data->student->name . '.pdf';

         $pdf = app('dompdf.wrapper');
         $pdf->loadView('components.bill.pdf.materialfee-installment-pdf', [
            'data' => $data
         ])->setPaper('a4', 'portrait');

         return $pdf->stream($nameFormatPdf);
      } catch (Exception $err) {
         abort(500);
      }
   }


   public function pageEditInstallment($bill_id)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'register students',
         ]);

         $data = Bill::with(['student', 'bill_installments' => function ($query) {
            $query->orderBy('id', 'asc');
         }])->where('id', $bill_id)->first();

         if (!$data || sizeof($data->bill_installments) <= 0) {
            return abort(404);
         }

         if (date('y-m-d', strtotime($data->updated_at)) != date('y-m-d')) {
            return abort(404);
         }

         if ($data->type != 'Paket') {
            return redirect()->back();
         }

         return view('components.installment-register')->with('data', $data);
      } catch (Exception $err) {
         return abort(404);
      }
   }
}
