<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Payment_student;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;




class PaymentStudentController extends Controller
{

   public function index(Request $request)
   {
      try {
         //code...

         session()->flash('page', (object)[
            'page' => 'payments',
            'child' => 'spp-students',
         ]);

         $form = (object) [
            'grade' => $request->grade && $request->grade != 'all' ? $request->grade : null,
            'sort' => $request->sort && $request->sort != 'all' ? $request->sort : 'desc',
            'order' => $request->order && $request->order != 'all' ? $request->order : 'id',
            'status' => $request->status && $request->status != 'all' ? $request->status : null,
            'search' => $request->search ? $request->search : null,
         ];


         $model = new Student();
         

         $data = $model->with([ 
            'spp_student' => function($query){
               $query->where('type', 'SPP')->get();
         },'grade']);

         if($form->search || $request->grade && $request->status)
         {
            if($form->status) {
               $temp = $form->status == 'true' ? true : false;
               if($temp){

                  $data = $data->whereHas('payment_student');
               } else {
                  $data =  $data->whereDoesntHave('payment_student');
               }
            }

            if($form->grade)
            {
               $data = $data->where('grade_id', (int)$form->grade);
            }

            if($form->search) 
            {
               $data = $data->where('name', 'LIKE', '%'.$form->search.'%');
            }
         }

         $data = $data->orderBy($form->order, $form->sort)->paginate(15);

         $grade = Grade::orderBy('id', 'asc')->get(['id', 'name', 'class']);

         
         return view('components.student.spp.data-payment-student')->with('data', $data)->with('form', $form)->with('grade', $grade);
         
      } catch (Exception $err) {
         //throw $th;
         // return dd($err);
         return abort(500);
      }
   }



   public function createPage($id)
   {
      try {
         //code...

         session()->flash('page', (object)[
            'page' => 'payments',
            'child' => 'spp-students',
         ]);

         session()->flash('preloader');

         $data = Student::with([
         'grade' => function ($query) {
               $query->with(['type' => function ($query) {
               $query->where('type', 'SPP');
            }]);
         },
      ])->where('unique_id', $id)->first();


         return view('components.student.spp.create-static-payment')->with('data', $data)->with('type', "SPP");
         
      } catch (Exception $err) {
         //throw $th;
         return abort(400);
      }
   }

   
   public function actionCreatePayment(Request $request, $id, $type = 'SPP'){
      try {
         //code...
         session()->flash('page', (object)[
            'page' => 'payments',
            'child' => 'spp-students',
         ]);
         
         $rules = [
            'type' => $type,
            'amount' => (int)str_replace(".", "", $request->amount),
            'discount' => $request->discount &&  $request->discount>0? (int)$request->discount : null,
            'installment' => $request->installment &&  $request->installment>0? (int)$request->installment : 0,
         ];
         
         
         $validator = Validator::make($rules, [
            'type' => 'required|string',
            'amount' => 'required|integer',
            'discount' => 'nullable|integer|max:99',
            'installment' => 'nullable|nullable|max:12',
         ]);
         
         
         if($validator->fails())
         {
            return redirect('/admin/spp-students/create/'. $id . '/' .$type)->withErrors($validator->messages())->withInput($rules);
         }
         
         $student = Student::where('unique_id', $id)->first();
         
         $check_unique = Payment_student::where('student_id', $student->id)->where('type', $type)->first();

         if($check_unique){
            
            $spp = Payment_student::where('id', $check_unique->id)->update([
               'type' => $type,
               'amount' => (int)str_replace(".", "", $request->amount),
               'discount' => $request->discount &&  $request->discount>0? (int)$request->discount : null,
            ]);
            
         } else  {
            
            for($i = 0; $i <= $request->installment; $i++)
            {
               $spp = Payment_student::create([
                  'type' => $type,
                  'student_id' => $student->id,
                  'amount' => (int)str_replace(".", "", $request->amount),
                  'discount' => $request->discount &&  $request->discount>0? (int)$request->discount : null,
               ]);
            }
         }

         session()->flash('after_create_spp_student');
         
         return redirect('/admin/spp-students/detail/' . $student->unique_id);
         
      } catch (Exception $err) {
         return dd($err);
      }
   }
   
   
   public function pageDetailSpp($id)
   {
      try {
         //code...
         session()->flash('page', (object)[
            'page' => 'payments',
            'child' => 'spp-students',
         ]);

         session()->flash('preloader');

         $data = Student::with(['spp_student' => function($query){
            $query->where('type', 'SPP')->get();
         }, 'grade'])->where('unique_id', $id)->first();
         
         return view('components.student.spp.detail-static-payment')->with('data', $data);
      } catch (Exception $err) {
         //throw $th;
         return dd($err);
      }
   }


   public function pageEditSpp($id)
   {
      try {
         //code...
         session()->flash('page', (object)[
            'page' => 'payments',
            'child' => 'spp-students',
         ]);

         session()->flash('preloader');
         
         $data = Student::with(['spp_student' => function($query){
            $query->where('type', "SPP")->get();
         }])->where('unique_id', $id)->first();


         return view('components.student.spp.edit-static-payment')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }


   public function actionEditStaticPayment(Request $request, $id, $id_student_payment){
      try {
         //code...
         session()->flash('page', (object)[
            'page' => 'payments',
            'child' => 'spp-students',
         ]);
         
         $rules = [
            'amount' => (int)str_replace(".", "", $request->amount),
            'discount' => $request->discount &&  $request->discount>0? (int)$request->discount : null,
         ];
         
         
         $validator = Validator::make($rules, [
            'amount' => 'required|integer',
            'discount' => 'nullable|integer|max:99',
         ]);
         
         
         if($validator->fails())
         {
            return redirect('/admin/spp-students/edit/'. $id)->withErrors($validator->messages())->withInput($rules);
         }

         
         
         Payment_student::where('id', $id_student_payment)->update([
            'amount' => (int)str_replace(".", "", $request->amount),
            'discount' => $request->discount &&  $request->discount>0? (int)$request->discount : null,
         ]);
         
         session()->flash('after_update_spp_student');
         
         return redirect('/admin/spp-students/detail/' . $id);
         
      } catch (Exception $err) {
         return dd($err);
      }
   }
}