<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use App\Mail\BookEmail;
use App\Models\Bill;
use App\Models\BillCollection;
use App\Models\Book;
use App\Models\Book_student;
use App\Models\Grade;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class PaymentBookController extends Controller
{
    public function index(Request $request)
    {
        try {
            
            session()->flash('page',  (object)[
                'page' => 'payments',
                'child' => 'payment-books',
             ]);

             $form = (object) [
                'sort' => $request->sort? $request->sort : null,
                'order' => $request->order? $request->order : null,
                'status' => $request->status? $request->status : null,
                'search' => $request->search? $request->search : null,
                'grade_id' => $request->grade_id? $request->grade_id : null,
                'page' => $request->page? $request->page : null,
             ];

             $grade = Grade::orderBy('id', 'asc')->get(['id', 'name', 'class']);

             $order = $request->sort ? $request->sort : 'desc';
             $status = $request->status? ($request->status == 'true' ? true : false) : true;

            if($form->search || $form->page || $request->order && $request->status && $request->sort && $request->grade_id){
            

                $form->grade_id = $form->grade_id == 'all' ? null : $form->grade_id;

                $dataModel = new Student();

                $data = $dataModel->with(['grade'])->withCount('book');
                

                if($form->order && $order)
                {
                    $data = $data->orderBy($form->order, $order);
                }

                if($form->search) {

                     $data = $data->where('name', 'LIKE', '%'.$form->search.'%');
                }

                if($form->grade_id) {
                     $data = $data->where('grade_id', (int)$form->grade_id);
                }

                if($status){
                    $data = $data->where('is_active', $status);
                }

                $data = $data->paginate(15);
                
            }else {

                $data = Student::with(['grade'])
                ->withCount('book')
                ->where('is_active', $status)->orderBy('id', 'desc')->paginate(15);
            }


            return view('components.book.payments.data-payment-book')->with('data', $data)->with('form', $form)->with('grade', $grade);

        } catch (Exception $err) {
            return abort(500, $err);
        }
    }

    public function studentBook($id)
    {
        try {
            //code...

            session()->flash('page',  (object)[
                'page' => 'payments',
                'child' => 'payment-books',
             ]);

            $student = Student::where('unique_id', $id)->first();

            $data = Student::with(['book' => function($query) use ($student) {
                $query->where('grade_id', $student->grade_id)->get();
            }, 'grade' ])->where('unique_id', $id)->first();

            $haveAllBooks = Book::where('grade_id', $data->grade_id)->get()->count() === sizeof($data->book)? true : false;
            
            // return $data;
            return view('components.book.payments.data-book-by-id')->with('data', $data)->with('have_all_books', $haveAllBooks);

        } catch (Exception $err) {
            return dd($err);
            return abort(404);
        }
    }


    public function pageAddBook($id)
    {
        try {
            //code...
            session()->flash('page',  (object)[
                'page' => 'payments',
                'child' => 'payment-books',
             ]);    

             $student = Student::with('grade')
             ->where('unique_id', $id)
             ->first(['id', 'name', 'grade_id']);

             $data = Book::where('grade_id', $student->grade_id)->get();

             $bookExist = Book::where('grade_id', $student->grade_id)->first() ? true : false;

            // return $data;
            return view('components.book.payments.add-student-book')->with('data', $data)->with('student', $student)->with('bookExist', $bookExist);

        } catch (Exception $err) {
            return dd($err);
        }
    }


    public function actionAddBook(Request $request, $id)
    {
        

        DB::beginTransaction();

        try {
            //code...
            $bookName = [];
            $billCollection = [];
            $data = $request->except(['_token', '_method']);
            $student = Student::with(['grade', 'relationship'])->where('id', $id)->first();
            $totalAmount = 0;



            foreach($data as $el)
            {

                $book = Book::where('id', (int)$el)->first();

                if($book) {

                    $totalAmount += (int)$book->amount;
                    
                    array_push($billCollection, (array)[
                        'type' => 'Book',
                        // 'bill_id' => (int)$billLastId->id + 1,
                        'book_id' => $book->id,
                        'name' => $book->name,
                        'amount' => (int)$book->amount,
                        'discount' => null,
                    ]);
                    
                    array_push($bookName, $book->name);
                }
                
            }
            
            if(sizeof($billCollection)<=0)
            {
                DB::rollBack();
                return redirect('/admin/payment-books/'.$student->unique_id.'/add-books')->withErrors([
                    'bill' => 'Checklist book is required',
                ]);
            }
            
            $collect = [
                'type' => 'Book',
                'subject' => 'Book',
                'student_id' => (int)$student->id,
                'amount' => $totalAmount,
                'paidOf' => false,
                'discount' => null,
                'deadline_invoice' => Carbon::now()->addMonth()->format('Y-m-10'),
                'installment' => null,
            ];
            
            $bill = Bill::create($collect);
            
            foreach ($billCollection as $value) {
                
                $value['bill_id'] = $bill->id;
                BillCollection::create($value);
            } 

            session()->flash('preloader');
            session()->flash('after_add_book', $student->name);
            
            DB::commit();
            return redirect('/admin/bills');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }
}
