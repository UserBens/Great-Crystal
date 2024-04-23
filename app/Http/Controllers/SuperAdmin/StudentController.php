<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Brothers_or_sister;
use App\Models\Grade;
use App\Models\Payment_grade;
use App\Models\Student;
use App\Models\Student_relation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
   public function inactiveStudent($id)
   {
      try {
         //code...
      session()->flash('page',  $page = (object)[
         'page' => 'students',
         'child' => 'database students',
      ]);
      
      info('inactive was successfully');
      Student::where('id', $id)->update([
         'is_active' => 0,
      ]);
      
      return response()->json([
            'success' => true,
      ]); 
      } catch (Exception $err) {
         return response()->json([
            'error' => 'error'
      ]);
      }
   }

   public function activateStudent($student_id)
   {
      
      DB::beginTransaction();

      try {

         $student = Student::where('id', $student_id)->first();

         if(!$student)
         {
            return (object) [
               'success' => false,
               'text' => 'Student not found !!!',
            ];
         }

         if(!$student->is_active && $student->is_graduate)
         {

            Student::where('id', $student_id)->update([
               'is_active' => true,
               'is_graduate' => false,
               'grade_id' => $student->grade_id + 1,
            ]);
            
         } else {
            
            Student::where('id', $student_id)->update([
               'is_active' => true,
               'is_graduate' => false,
            ]);
         }

         DB::commit();
         return (object) [
            'success' => true,
         ];
         
      } catch (Exception $err) {
         
         return (object) [
            'success' => false,
            'text' => 'Internal server error !!!',
         ];
      }

   }            
   
   public function pageReRegis($student_id){
      try {
         //code...
         $student = Student::where('unique_id', $student_id)->first();
         
         if(!$student){
            
            return abort(404);
         }
         $grade = Grade::where('id', '>', $student->grade_id)->orderBy('id', 'asc')->get();

         return view('components.super.re-regis')->with('data', $student)->with('grade', $grade);

      } catch (Exception $err) {
         
         return abort(404);

      }
   }


   public function actionReRegis(Request $request, $student_id)
   {
      DB::beginTransaction();

      try {
         //code...

         $input = $request->only('grade_id');
         
         $student = Student::with('relationship')->where('id', $student_id)->first();
         $grade = Grade::where('id', $request->grade_id)->first();

         if(!$student || !$grade) {
            DB::rollBack();
            return redirect()->back()->withErrors([
               'grade_id' => 'student or grade id not found !!!',
               ])->withInput($input);
            }
            
         if($request->grade_id == $student->grade_id) {
            DB::rollBack();
            return redirect()->back()->withErrors([
               'grade_id' => 'Grade must change to re-register',
               ])->withInput($input);
         }

         Student::where('id', $student_id)->update([
            'grade_id' => $request->grade_id,
            'is_graduate' => false,
            'is_active' => true,
         ]);


         $payment = Payment_grade::where('grade_id', $request->grade_id)->where('type', 'paket')->first();

         if(!$payment){
            return redirect('/admin/payment-grades/'.$request->grade_id)->withErrors([
               '404' => [
                  'Paket for '. $grade->name . ' ' . $grade->class .  ' never been created !!!',
               ]
            ]);
         }

         Bill::create([
            'student_id' => $student->id,
            'type' => 'Paket',
            'subject' => 'Paket',
            'amount' => $payment->amount,
            'installment' => null,
            'diskon' => null,
            'paidOf' => false,
            'deadline_invoice' => Carbon::now()->setTimezone('Asia/Jakarta')->addMonth()->format('y-m-10'),
         ]);

         DB::commit();

         session()->flash('after_update_student');

         return redirect('/admin/detail/'.$student->unique_id);
         
         
      } catch (Exception $err) {
         
         DB::rollBack();
         return dd($err);
      }  
   }
}