<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use App\Models\Bill;
use App\Models\Book;
use App\Models\Book_student;
use App\Models\Grade;
use App\Models\Payment_grade;
use App\Models\Payment_student;
use App\Models\Student;
use App\Models\Teacher;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GradeController extends Controller
{
   public function index()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);

         $data = Grade::with(['teacher'])->withCount(['student as active_student_count' => function ($query) {
            $query->where('is_active', true);
         }])->get();;
         
         // return $data;

         return view('components.grade.data-grade')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function pageCreate()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);
         $data = Teacher::orderBy('id', 'ASC')->get();

         return view('components.grade.create-grade')->with('data', $data);
         
      } catch (Exception) {
         return abort(500);
      }
   }
   
   public function actionPost(Request $request)
   {

      DB::beginTransaction();

      try {

         $rules = [
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'class' => $request->class,
         ];

         $validator = Validator::make($rules, [
            'name' => 'required|string',
            'class' => 'required|string|max:15',
            'teacher_id' => 'required|integer',
         ],
      );

      
      if($validator->fails())
      {
         DB::rollBack();
         return redirect('/admin/grades/create')->withErrors($validator->messages())->withInput($rules);
      }
      
      if(Grade::where('name', $request->name)->where('class', $request->class)->first())
      {
         DB::rollBack();
         return redirect('/admin/grades/create')->withErrors([
            'name' => 'Grades ' . $request->name . ' class ' . $request->class . ' is has been created ',
         ])->withInput($rules);
      }

      if(Grade::where('teacher_id', $request->teacher_id)->first())
      {
         Grade::where('teacher_id', $request->teacher_id)->update([
               'teacher_id' => null,
            ]);
      }
         
      $post = [
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'class' => $request->class,
      ];


         session()->flash('after_create_grade');

         Grade::create($post);

         DB::commit();
         
         return redirect('/admin/grades');

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }


   public function detailGrade($id){
      
      try {
         //code...
         
         $data = (object)[
            'gradeTeacher' => Grade::with(['teacher'])->where('id', $id)->first(),
            'gradeStudent' => Student::where('grade_id', $id)->where('is_active', true)->get(),
         ];
         
         // return $data;

         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);

         return view('components.grade.detail-grade')->with('data', $data);
      } catch (Exception $err) {
         
         return abort(404);
      }

   }


   public function pageEdit($id)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);
         $teacher = Teacher::orderBy('id', 'asc')->get();
         $data = Grade::where('id', $id)->first();

         return view('components.grade.edit-grade')->with('data', $data)->with('teacher', $teacher);
         
      } catch (Exception $err) {
         return abort(404);
      }
   }


   public function actionPut(Request $request, $id)
   {

      DB::beginTransaction();
      try {

         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);

         $rules = [
            'name' => $request->name,
            'teacher_id' => $request->teacher_id? $request->teacher_id : null,
            'class' => $request->class,
         ];

         $validator = Validator::make($rules, [
            'name' => 'required|string',
            'class' => 'required|string|max:15',
            'teacher_id' => 'nullable|integer',
            ]
         );
         if($validator->fails())
         {
            DB::rollBack();
            return redirect('/admin/grades/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
         }
         
         $check = Grade::where('name', $request->name)->where('class', $request->class)->first();

         if($check && $check->id != $id)
         {

            DB::rollBack();
            return redirect('/admin/grades/edit/' . $id)->withErrors(['name' => ["The grade " . $request->name . " with class " . $request->class ." is already created !!!"]])->withInput($rules);
         }
      

         
         if(Grade::where('teacher_id', $request->teacher_id)->first())
         {
            Grade::where('teacher_id', $request->teacher_id)->update([
               'teacher_id' => null,
            ]);
         }

   
         Grade::where('id', $id)->update($rules);
         
         DB::commit();

         session()->flash('after_update_grade');

         return redirect('/admin/grades');

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         // return abort(500);
      }
   }


   public function pagePromotion($id)
   {
      try {
         //code...
         $data = Student::with(['grade', 'bill' => function($query) {

            $query->where('paidOf', false)->get();
         }])
         ->where('grade_id', $id)
         ->where('is_active', true)
         ->orderBy('name', 'asc')
         ->get();
         
         $grade = Grade::where('id', $id)->first();
         
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);

         // return $data;

         return view('components.grade.promotion.page')->with('data', $data)->with('grade', $grade);
         
      } catch (Exception $err) {
         return abort(404);
      }
   }


   public function actionPromotion(Request $request)
   {
      DB::beginTransaction();
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);
         
         $promoteId = $request->except(['_token', '_method']);

         if(sizeof($promoteId)<=0)
         {
            DB::rollBack();
            return redirect()->back()->withErrors([
               'checklist' => 'must checklist at least one student!!!',
            ]);
         }

         $grade = Student::with('grade', 'relationship')->where('id', reset($promoteId))->first();
         
         $lastest_grade = DB::table('grades')->where('name', $grade->grade->name)->orderBy('id', 'desc')->first();

         
         if($grade->grade->id < $lastest_grade->id)
         {
            $paket = Payment_grade::where('type', 'Paket')->where('grade_id', $grade->grade->id+1)->first('amount');
   
            //harus memberi ada validasi semisal paket dari kelas belom di set up;
            if(!$paket)
            {
               return redirect('/admin/payment-grades/'.$grade->grade->id+1)->withErrors(['paket' => 'Paket payments for '. $grade->grade->name .' - '. $grade->grade->class. ' grades have not been set up']);
            }
            foreach ($promoteId as $value) {
               
               Book_student::where('student_id', (int)$value)->delete();
               Payment_student::where('student_id', (int)$value)->where('type', "SPP")->delete();

               Student::where('id', $value)->update([
                  'grade_id' => (int)$grade->grade->id + 1,
               ]);
            }

            session()->flash('levelup', $grade->grade->name . " " . $grade->grade->class);
         } else {
            foreach ($promoteId as $value) {
               
               Book_student::where('student_id', (int)$value)->delete();
               
               Student::where('id', $value)->update([
                  'is_active' => 0,
                  'is_graduate' => 1,
               ]);
            }
            
            session()->flash('graduate', $grade->grade->name . " " . $grade->grade->class);
         }


         session()->flash('preloader');
         $this->billPaketGraduate($promoteId);


         DB::commit();

         return redirect('/admin/grades/');

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }


   public function pagePDF($id){
      try {

         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);
         
         $data = Grade::with(['student' => function ($query) {
               $query->where('is_active', true)->orderBy('name', 'asc');
         }])->find($id);

         $nameFormatPdf = Carbon::now()->format('YmdHis') . mt_rand(1000, 9999).'_'.date('d-m-Y').'_'.$data->name.'_'.$data->class.'.pdf';

	      $pdf = app('dompdf.wrapper');
         $pdf->loadView('components.grade.pdf.dom-pdf', ['data' => $data])->setPaper('a4', 'portrait');
         return $pdf->stream($nameFormatPdf);

      } catch (Exception $err) {
         
         return dd($err);
      }
   }



   public function billPaketGraduate($array)
   {
      DB::beginTransaction();
      try {
         //code...

         foreach($array as $studentId)
         {

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
               'subject' => 'Paket',
               'amount' => $amount,
               'discount' => null,
               'installment' => null,
               'paidOf' => false,
               'deadline_invoice' => Carbon::now()->setTimezone('Asia/Jakarta')->addMonth()->format('Y-m-10'),
            ]);

         }


         DB::commit();
         
         return (object) [
            'status' => true,
         ];
         
      } catch (Exception $err) {
         DB::rollBack();
         return (object) [
            'status' => false,
         ];
      }
   }
}