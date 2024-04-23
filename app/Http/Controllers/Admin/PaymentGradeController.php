<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Payment_grade;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentGradeController extends Controller
{
   

   public function index(Request $request)
   {
      try {
         //code...

         session()->flash('page',  (object)[
            'page' => 'payments',
            'child' => 'payment-grades',
         ]);

         $grades = Grade::groupBy('name')->orderBy('id', 'asc')->get('name');

         $form = (object) [
            'grade' => $request->grade? $request->grade : '',
            'class' => $request->class? $request->class : '',
         ];

         $data = Grade::with(
            ['spp' => function($query) {
            $query->where('type', 'SPP')->get();
         }, 'uniform' => function($query) {
            $query->where('type', 'Uniform')->get();
         }, 'bundle' => function($query) {
            $query->where('type', 'Paket')->get();
         },])->where('name','LIKE', '%'. $form->grade .'%')->where('class', 'LIKE', '%'. $form->class .'%')->get();



         return view('components.grade.payment.data-grade-payment')->with('data', $data)->with('grades', $grades)->with('form', $form);

      } catch (Exception $err) {
         return dd($err);
      }
   }


   public function pageById($id)
   {
      try {
         //code...
         session()->flash('page',  (object)[
            'page' => 'payments',
            'child' => 'payment-grades',
         ]);

         $data = Grade::with(['payment_grade' => function($query) {
            $query->orderBy('type', 'asc');
         }])->where('id', $id)->first();

         // return $data; 

         return view('components.grade.payment.data-payment')->with('data', $data);
         
         
      } catch (Exception $err) {
         //throw $th;
         return dd($err);
      }
   }
   
   public function chooseSection($id)
   {
      try {
         //code...
         
         session()->flash('page',  (object)[
            'page' => 'payments',
            'child' => 'payment-grades',
         ]);
         
         $data = Grade::where('id', $id)->first();
         
         return view('components.grade.payment.choose-payment')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }


   public function pageCreate($id, $type) 
   {
      try {
         //code...

         $data = Grade::where('id', $id)->first();

         session()->flash('page',  (object)[
            'page' => 'payments',
            'child' => 'payment-grades',
         ]);

         
         return view('components.grade.payment.form-payment')->with('data', $data)->with('type', $type);
      } catch (Exception $err) {
         
         return dd($err);
      }
   }


   public function actionCreate($id, $type, Request $request)
   {

      DB::beginTransaction();
      session()->flash('page', (object)[
         'page' =>'payments',
         'child' => 'payment-grades',
      ]);
      session()->flash('preloader');

      try {
         //code...

         $rules  = [
            'type' => $type,
            'amount' => $request->amount,
            'grade_id' => $id,
         ];
         
         $backupError = $rules['amount'];
         $rules['amount'] = (int)str_replace(".", "", $rules['amount']);
         
         $validator = Validator::make($rules, [
            'type' => 'required|string', 
            'amount' => 'required|integer', 
            'grade_id' => 'required|integer', 
         ]);
         
         if($validator->fails())
         {
            DB::rollBack();
            $rules['amount'] = $backupError;
            return redirect('/admin/payment-grades/'.$id.'/create/'.$type)->withErrors($validator->messages())->withInput($rules);
         }

         $checkUnique = Payment_grade::where('type', $rules['type'])->where('grade_id', $id)->first();


         $checkUnique ? Payment_grade::where('type', $rules['type'])->where('grade_id', $id)->delete() : '';
         
         Payment_grade::create([
            'type' => $type == 'SPP' ?  $type : ucwords($type),
            'amount' => (int)str_replace(".", "", $rules['amount']),
            'grade_id' => $id,
         ]);



         DB::commit();

         session()->flash('after_create_payment_grade');
         return redirect('/admin/payment-grades' . '/' . $id);
         
      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }


   public function pageEdit($id){
      try {
         //code...

         session()->flash('page', (object)[
            'page' =>'payments',
            'child' => 'payment-grades',
         ]);

         $data = Payment_grade::with('grade')->where('id', $id)->first();

         return view('components.grade.payment.edit-payment')->with('data', $data);
         
      } catch (Exception $err) {
         

         return dd($err);
      }
   }
   

   public function actionEdit(Request $request, $id){
      try {
         //code...

         session()->flash('page', (object)[
            'page' =>'payments',
            'child' => 'payment-grades',
         ]);

         $rules = $request->only('amount');

         $rules['amount'] = (int)str_replace(".", "", $rules['amount']);

         $validator = Validator::make($rules, [
            'amount' => 'integer|required',
         ]);

         if($validator->fails())
         {
            return redirect('/admin/payment-grades/'. $id .'/edit')->withErrors($validator->messages())->withInput($rules);
         }
         
         Payment_grade::where('id', $id)->update([
            'amount' => $rules['amount']
         ]);

         $data = Payment_grade::where('id', $id)->first();

         session()->flash('after_update_payment_grade');

         return redirect('/admin/payment-grades' . '/' . $data->grade_id);

      } catch (Exception $err) {
         return dd($err);
      }
   }


   public function deletePayment($id)
   {
      try {
         
         session()->flash('page', (object)[
            'page' =>'payments',
            'child' => 'payment-grades',
         ]);

         Payment_grade::where('id', $id)->delete();

         return response()->json([
            'success' => true,
         ]);

      } catch (Exception $err) {
         return response()->json([
            'success' => false,
         ]);
      }
   }
}