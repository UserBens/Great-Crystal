<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DemoMail;
use App\Mail\SppMail;
use App\Models\Bill;
use App\Models\Brothers_or_sister;
use App\Models\Grade;
use App\Models\Installment_Paket;
use App\Models\InstallmentPaket;
use App\Models\Payment_grade;
use App\Models\Payment_student;
use App\Models\Relationship;
use App\Models\Student;
use App\Models\Student_relation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
   public function index()
   {
      try {
         //code...
         $grade = Grade::orderBy('id', 'asc')->get();
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'register students',
         ]);
         // return $grade;
         return view('components.register', ['grade'=> $grade]);
      } catch (Exception $err) {
         
         return dd($err);
      }
   }


   public function register(Request $request)
   {

      
      try {
         
         //code...
         
         session()->flash('preloader', true);
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'register students',
         ]);

         $var = Student::orderBy('id', 'desc')->first();
         $unique_id = '';
         
         if( $var && date('Ym') == substr($var->unique_id, 0, 6))
         {
            $unique_id = (string)date('Ym') . str_pad(ltrim(substr($var->unique_id, 7) + 1, '0'), 4, '0', STR_PAD_LEFT);
         } else {
            $unique_id = (string)date('Ym') . str_pad('1', 4, '0', STR_PAD_LEFT);
         } 



         $credentials = [
            'is_active' => 1,
            'unique_id' => $unique_id,
            'name' => $request->studentName,
            'grade_id' => (int)$request->gradeId,
            'gender' => $request->studentGender,
            'religion' => $request->studentReligion,
            'nisn' => $request->nisn,
            'place_birth' => $request->studentPlace_birth,
            'date_birth' => $request->studentDate_birth ? $this->changeDateFormat($request->studentDate_birth) : null,
            'id_or_passport' => $request->studentId_or_passport,
            'nationality' => $request->studentNationality,
            'place_of_issue' => $request->studentPlace_of_issue,
            'date_exp' => $request->studentDate_exp ? $this->changeDateFormat($request->studentDate_exp) : null,
            'is_graduate' => false,
            'created_at' => $request->created_at ? date('Y-m-d H:i:s', strtotime($this->changeDateFormat($request->created_at))) : date('Y-m-d H:i:s'),
         ];
         
         $rules = [
            'name' => $request->studentName,
            'grade_id' => $request->gradeId,
            'gender' => $request->studentGender,
            'religion' => $request->studentReligion,
            'nisn' => $request->nisn,
            'place_birth' => $request->studentPlace_birth,
            'date_birth' => $request->studentDate_birth ? $this->changeDateFormat($request->studentDate_birth) : null,
            'id_or_passport' => $request->studentId_or_passport,
            'nationality' => $request->studentNationality,
            'place_of_issue' => $request->studentPlace_of_issue,
            'date_exp' => $request->studentDate_exp !== '' ? $this->changeDateFormat($request->studentDate_exp) : null,
            'created_at' => $request->created_at ? date('Y-m-d H:i:s', strtotime($this->changeDateFormat($request->created_at))) : date('Y-m-d H:i:s'),
            // Father rules
            'father_relation' => 'father',
            'father_name' => $request->fatherName,
            'father_religion' => $request->fatherReligion,
            'father_place_birth' => $request->fatherPlace_birth,
            'father_date_birth' => $request->fatherBirth_date ? $this->changeDateFormat($request->fatherBirth_date) : null,
            'father_id_or_passport' => $request->fatherId_or_passport,
            'father_nationality' => $request->fatherNationality,
            'father_occupation' => $request->fatherOccupation,
            'father_company_name' => $request->fatherCompany_name,
            'father_company_address' => $request->fatherCompany_address,
            'father_phone' => $request->fatherCompany_phone,
            'father_home_address' => $request->fatherHome_address,
            'father_telephone' => $request->fatherTelephhone,
            'father_mobilephone' => $request->fatherMobilephone,
            'father_email' => $request->fatherEmail,
            // Mother rules
            'mother_relation' => 'mother',
            'mother_name' => $request->motherName,
            'mother_religion' => $request->motherReligion,
            'mother_place_birth' => $request->motherPlace_birth,
            'mother_date_birth' => $request->motherBirth_date ? $this->changeDateFormat($request->motherBirth_date) : null,
            'mother_id_or_passport' => $request->motherId_or_passport,
            'mother_nationality' => $request->motherNationality,
            'mother_occupation' => $request->motherOccupation,
            'mother_company_name' => $request->motherCompany_name,
            'mother_company_address' => $request->motherCompany_address,
            'mother_phone' => $request->motherCompany_phone,
            'mother_home_address' => $request->motherHome_address,
            'mother_telephone' => $request->motherTelephhone,
            'mother_mobilephone' => $request->motherMobilephone,
            'mother_email' => $request->motherEmail,
            //brother and sister

            'brotherOrSisterName1' => $request->brotherOrSisterName1, 
            'brotherOrSisterBirth_date1' => $request->brotherOrSisterBirth_date1? $this->changeDateFormat($request->brotherOrSisterBirth_date1) : null,
            'brotherOrSisterGrade1' => $request->brotherOrSisterGrade1,
            'brotherOrSisterName2' => $request->brotherOrSisterName2, 
            'brotherOrSisterBirth_date2' => $request->brotherOrSisterBirth_date2? $this->changeDateFormat($request->brotherOrSisterBirth_date2) : null,
            'brotherOrSisterGrade2' => $request->brotherOrSisterGrade2,
            'brotherOrSisterName3' => $request->brotherOrSisterName3, 
            'brotherOrSisterBirth_date3' => $request->brotherOrSisterBirth_date3? $this->changeDateFormat($request->brotherOrSisterBirth_date3) : null,
            'brotherOrSisterGrade3' => $request->brotherOrSisterGrade3,
            'brotherOrSisterName4' => $request->brotherOrSisterName4, 
            'brotherOrSisterBirth_date4' => $request->brotherOrSisterBirth_date4? $this->changeDateFormat($request->brotherOrSisterBirth_date4) : null,
            'brotherOrSisterGrade4' => $request->brotherOrSisterGrade4,
            'brotherOrSisterName5' => $request->brotherOrSisterName5, 
            'brotherOrSisterBirth_date5' => $request->brotherOrSisterBirth_date5? $this->changeDateFormat($request->brotherOrSisterBirth_date5) : null,
            'brotherOrSisterGrade5' => $request->brotherOrSisterGrade5,


            //fee register

            'type' => 'Uang Gedung',
            'amount' => $request->amount && $request->amount > 0?  (int)str_replace(".", "", $request->amount) : 0,
            'dp' => $request->dp && $request->dp > 0? (int)str_replace(".", "", $request->dp) : 0,
            'installment' => $request->installment && $request->installment > 0 ? $request->installment : null,
            'sendEmail' => $request->sendEmail? true : false,


            // monthly fee

            "amount_monthly_fee" => $request->amount_monthly_fee && $request->amount_monthly_fee > 0? (int)str_replace(".", "", $request->amount_monthly_fee) : null,
         ];     
         
         // return $rules;
         
         $validator = Validator::make($rules, [
            'name' => 'string|required|min:3',
            'grade_id' => 'integer|required',
            'gender' => 'string|required',
            'religion' => 'string|required',
            'nisn' => 'string|nullable|min:7|max:12|unique:students',
            'place_birth' => 'string|required',
            'date_birth' => 'date|required',
            'id_or_passport' => 'nullable|string|min:9|max:16|unique:students',
            'nationality' => 'string|required|min:3',
            'place_of_issue' => 'nullable|string',
            'date_exp' => 'nullable|date',
            // father validation 
            'father_name' => 'string|required|min:3',
            'father_religion' => 'string|required',
            'father_place_birth' => 'string|required',
            'father_date_birth' => 'date|required',
            'father_id_or_passport' => 'string|required|min:12|max:16',
            'father_nationality' => 'string|required',
            'father_phone' => 'nullable|string|max:15|min:6',
            'father_home_address' => 'required|string',
            'father_mobilephone' => 'required|string|max:15|min:6',
            'father_telephone' => 'nullable|string|max:15|min:6',
            'father_email' => 'required|string|email',
            //mother validation
            'mother_name' => 'string|required|min:3',
            'mother_religion' => 'string|required',
            'mother_place_birth' => 'string|required',
            'mother_date_birth' => 'date|required',
            'mother_id_or_passport' => 'string|required|min:15|max:16',
            'mother_nationality' => 'string|required',
            'mother_occupation' => 'nullable|string',
            'mother_company_name' => 'nullable|string',
            'mother_company_address' => 'nullable|string',
            'mother_phone' => 'nullable|string|max:15|min:6',
            'mother_home_address' => 'required|string',
            'mother_telephone' => 'nullable|string|max:15|min:6',
            'mother_mobilephone' => 'required|string|max:15|min:6',
            'mother_telephone' => 'nullable|string|max:15|min:6',
            'mother_email' => 'required|string|email',

            'brotherOrSisterName1' => 'nullable|string',
            'brotherOrSisterBirth_date1' => 'nullable|string',
            'brotherOrSisterGrade1' => 'nullable|string',
            'brotherOrSisterName2' =>  'nullable|string',
            'brotherOrSisterBirth_date2'=>'nullable|string',
            'brotherOrSisterGrade2' => 'nullable|string',
            'brotherOrSisterName3' =>  'nullable|string',
            'brotherOrSisterBirth_date3'=>'nullable|string',
            'brotherOrSisterGrade3' => 'nullable|string',
            'brotherOrSisterName4' =>  'nullable|string',
            'brotherOrSisterBirth_date4'=>'nullable|string',
            'brotherOrSisterGrade4' => 'nullable|string',
            'brotherOrSisterName5' =>  'nullable|string',
            'brotherOrSisterBirth_date5'=>'nullable|string',
            'brotherOrSisterGrade5' => 'nullable|string',

            //fee register

            'amount' => 'required|integer',
            'dp' => 'nullable|integer',
            'installment' => 'nullable|integer',
            'sendEmail' => 'required|boolean',

            //monthly fee 

            'amount_monthly_fee' => 'nullable|integer|min:50000'
         ]);

         
         if($validator->fails())
         {
            // DB::rollBack();
            
            return redirect('/admin/register')->withErrors($validator->messages())->withInput($rules);
         }
         
         if($rules['amount'] < $rules['dp'])
         {
            // DB::rollBack();
            return redirect('/admin/register')->withErrors([
               'dp' => ['Done payment cannot be greater than the capital fee.'],
            ])->withInput($rules);
         }

         $fatherExist = Relationship::where('id_or_passport', $rules['father_id_or_passport'])->first(); 

         if($fatherExist && $fatherExist->relation == 'mother')
         {
            // DB::rollBack();
            return redirect('/admin/register')->withErrors([
               'father_id_or_passport' => ['This id or passport has been registered with mother relation.'],
            ])->withInput($rules);
         }

         $motherExist = Relationship::where('id_or_passport', $rules['mother_id_or_passport'])->first(); 

         if($motherExist && $motherExist->relation == 'father')
         {
            // DB::rollBack();
            return redirect('/admin/register')->withErrors([
               'mother_id_or_passport' => ['This id or passport has been registered with father relation.'],
            ])->withInput($rules);
         }

         $paket = Payment_grade::where('grade_id', $request->gradeId)
         ->where('type', 'Paket')
         ->first();

         if(!$paket){
            DB::rollBack();
               // return 'masuk';
               return redirect('/admin/register')->withErrors([
                  'paket' => ['Paket grade is not found.'], 
               ])->withInput($rules);
         }

         DB::beginTransaction();

         $student = Student::create($credentials);
         $relationship = $this->handleRelationship($request, $student);
         $brotherOrSister = $this->handleBrotherOrSister($request, $student);
         
         // return $relationship;
         if(!$relationship->success){
            DB::rollBack();
            return dd($relationship->error);
         } else if (!$brotherOrSister->success){
            DB::rollBack();
            return dd('error at brother or sisters');
         }

         // return 'post';
            
         $student = Student::with('relationship')->where('id', $student->id)->first();
         $brotherOrSister = Student::find($student->id);
         if($rules['amount'] > 0){
            $feeRegister = $this->handleFeeRegister($rules['amount'], $request->installment, $student, $rules['dp']);
            // $paket = $this->handlePaketPayment($student);
            
   
            $currentDate = date('Y-m-d');
   
            $newDate = date('Y-m-10', strtotime('+1 month', strtotime($currentDate)));
   
            Bill::create([
               'student_id' => $student->id,
               'type' => 'Paket',
               'amount' => $paket->amount,
               'paidOf' => false,
               'deadline_invoice' => $newDate,
               'subject' => 'Paket', 
            ]);  
         }

         if($rules['amount_monthly_fee']){

            Payment_student::create([
               'student_id' => $student->id,
               'type' => 'SPP',
               'amount' => $rules['amount_monthly_fee'],
            ]);
         }
         
         $data = (object) [
            'student' => $student,
            'brother_or_sisters' => $brotherOrSister->brotherOrSister()->get(),
            'after_create' => 'Success register student with name ' . $student->name,
         ];
         
         // return $data;
         session()->flash('after_create_student');
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'database students',
         ]);
         
         DB::commit();

         if($request->installment)
         {
            return redirect('/admin/register/edit-installment-capital/'.$feeRegister->last_bill->id);
         }

         return view('components.student.detailStudent')->with('data', $data);


      } catch (Exception $err) {
         
         DB::rollBack();
         // abort(500);
         return dd($err);
      }
   }


   private function handleRelationship($request, $student)
   {
      
      try {
         //code...

         $credentialsFather = [
            'relation' => 'father',
            'name' => $request->fatherName,
            'religion' => $request->fatherReligion,
            'place_birth' => $request->fatherPlace_birth,
            'date_birth' => $request->fatherBirth_date ? $this->changeDateFormat($request->fatherBirth_date) : null,
            'id_or_passport' => $request->fatherId_or_passport,
            'nationality' => $request->fatherNationality,
            'occupation' => $request->fatherOccupation,
            'company_name' => $request->fatherCompany_name,
            'company_address' => $request->fatherCompany_address,
            'phone' => $request->fatherCompany_phone,
            'home_address' => $request->fatherHome_address,
            'telephone' => $request->fatherTelephhone,
            'mobilephone' => $request->fatherMobilephone,
            'email' => $request->fatherEmail,
         ];


         $credentialsMother = [
            'relation' => 'mother',
            'name' => $request->motherName,
            'religion' => $request->motherReligion,
            'place_birth' => $request->motherPlace_birth,
            'date_birth' => $request->motherBirth_date ? $this->changeDateFormat($request->motherBirth_date) : null,
            'id_or_passport' => $request->motherId_or_passport,
            'nationality' => $request->motherNationality,
            'occupation' => $request->motherOccupation,
            'company_name' => $request->motherCompany_name,
            'company_address' => $request->motherCompany_address,
            'phone' => $request->motherCompany_phone,
            'home_address' => $request->motherHome_address,
            'telephone' => $request->motherTelephhone,
            'mobilephone' => $request->motherMobilephone,
            'email' => $request->motherEmail,
         ];


         $checkIdFather = Relationship::where('id_or_passport', $credentialsFather['id_or_passport'])->first();
         $checkIdMother = Relationship::where('id_or_passport', $credentialsMother['id_or_passport'])->first();


         $father = $checkIdFather && $checkIdFather->relation == 'father'? $this->updateRelation($checkIdFather->id, $credentialsFather) : Relationship::create($credentialsFather);
         $mother = $checkIdMother && $checkIdMother->relation == 'mother'? $this->updateRelation($checkIdMother->id, $credentialsMother) : Relationship::create($credentialsMother);

         Student_relation::create(['student_id' => $student->id,'relation_id' => $father->id]);
         Student_relation::create(['student_id' => $student->id,'relation_id' => $mother->id]);

         return (object)['success' => true, 'dataRelation' => (object)['father' => $father, 'mother' => $mother]];

      } catch (Exception $err) {
         
         return (object) ['success' => false, 'error' => $err];
      }
   }

   private function handleBrotherOrSister($request, $student)
   {
      
      try {
         //code...
         $credentialsBrotherOrSister = [];

         if($request->brotherOrSisterName1 && $request->brotherOrSisterBirth_date1 && $request->brotherOrSisterGrade1)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $request->brotherOrSisterName1, 'date_birth' => $this->changeDateFormat($request->brotherOrSisterBirth_date1), 'grade' => $request->brotherOrSisterGrade1, 'student_id' => $student->id]);
         }
         if($request->brotherOrSisterName2 && $request->brotherOrSisterBirth_date2 && $request->brotherOrSisterGrade2)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $request->brotherOrSisterName2, 'date_birth' => $this->changeDateFormat($request->brotherOrSisterBirth_date2), 'grade' => $request->brotherOrSisterGrade2, 'student_id' => $student->id]);
         }
         if($request->brotherOrSisterName3 && $request->brotherOrSisterBirth_date3 && $request->brotherOrSisterGrade3)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $request->brotherOrSisterName3, 'date_birth' => $this->changeDateFormat($request->brotherOrSisterBirth_date3), 'grade' => $request->brotherOrSisterGrade3, 'student_id' => $student->id]);
         }
         if($request->brotherOrSisterName4 && $request->brotherOrSisterBirth_date4 && $request->brotherOrSisterGrade4)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $request->brotherOrSisterName4, 'date_birth' => $this->changeDateFormat($request->brotherOrSisterBirth_date4), 'grade' => $request->brotherOrSisterGrade4, 'student_id' => $student->id]);
         }
         if($request->brotherOrSisterName5 && $request->brotherOrSisterBirth_date5 && $request->brotherOrSisterGrade5)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $request->brotherOrSisterName5, 'date_birth' => $this->changeDateFormat($request->brotherOrSisterBirth_date5), 'grade' => $request->brotherOrSisterGrade5, 'student_id' => $student->id]);
         }

         Brothers_or_sister::insert($credentialsBrotherOrSister);
         

         return (object)['success' => true, 'dataBrotherOrSister' => $credentialsBrotherOrSister];
      } catch (Exception $err) {
         return (object)['success' => false, 'error' => $err];
      }
   }

   public function changeDateFormat($date)
   {
      try {
         //code...
         $date = explode('/', $date);

                  
         return "$date[2]-$date[1]-$date[0]";
      } catch (Exception) {
         return null;
      }
   }

   public function updateRelation($id, $credential)
   {

      
      try {
         //code...
         
         Relationship::where('id', $id)->update($credential);

         return Relationship::where('id', $id)->first();

      } catch (Exception $err) {
         return dd($err);
      }
   }

   private function handleFeeRegister($amount, $installment=1, $student, $dp)
   {
      

      try {


      $installment  = $installment && $installment > 1 ? $installment : 1;   
      $user = Auth::user();
         $amountTotal = (int)$amount - (int)$dp;

         if (($amountTotal/$installment) % 1_000 === 0) {
            $billPerMonth = (int)$amountTotal / (int)$installment;
         } else {
            
            $billPerMonth = ceil((int)$amountTotal / (int)$installment);
            $billPerMonth += (1_000 - ($billPerMonth % 1_000));
         }

         $lastBillPerMonth = ($amountTotal % $billPerMonth) == 0? $billPerMonth : $amountTotal % $billPerMonth;

         
         $main_id = [];
         // $currentDate = date('Y-m-d');
         
         for($i=1; $i<=$installment; $i++){

            $newDate = Carbon::now()->setTimezone('Asia/Jakarta')->addMonths($i)->format('Y-m-10');
            $subject = $installment <= 1? 'Capital Fee' : $i;

            if($i == $installment) {
               

               $bill = Bill::create([
                  'student_id' => $student->id,
                  'type' => 'Capital Fee',
                  'amount' => $amount,
                  'dp' => $dp,
                  'paidOf' => false,
                  'discount' => null,
                  'deadline_invoice' =>$newDate,
                  'installment' => $installment == 1? null : $installment,
                  'amount_installment' => $installment > 1 ? $lastBillPerMonth : 0,
                  'subject' => $subject, 
                  'created_by' => $user->role == 'admin'? 'admin' : 'accounting',
               ]);   

               if($installment>1)
               {
                  array_push($main_id, $bill->id);
               }

               continue;
            } 

            $bill = Bill::create([
               'student_id' => $student->id,
               'type' => 'Capital Fee',
               'amount' => $amount,
               'dp' => $dp,
               'paidOf' => false,
               'discount' => null,
               'deadline_invoice' =>$newDate,
               'installment' => $installment == 1? null : $installment,
               'amount_installment' => $installment > 1 ? $billPerMonth : 0,
               'created_by' => $user->role == 'admin'? 'admin' : 'accounting',
               'subject' => $subject, 
            ]);   

            array_push($main_id, $bill->id);
         }

         foreach($main_id as $el) {
            

            foreach($main_id as $child_id) {

               InstallmentPaket::create([
                  'main_id' => $el,
                  'child_id' => $child_id,
               ]);
            }

         }
         
         
         return (object)['success' => true, 'last_bill' => $bill];
      } catch (Exception $err) {
         return (object)['success' => false, 'error' => dd($err)];
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
         
         $data = Bill::with(['student', 'bill_installments' => function($query) {
            $query->orderBy('id', 'asc');
            
         }])->where('id', $bill_id)->first();

         if(!$data || sizeof($data->bill_installments) <= 0 ) {
            return abort(404);
         }

         if(date('y-m-d',strtotime($data->created_at)) !== date('y-m-d'))
         {
            return abort(404);
         }

         if($data->type != 'Capital Fee')
         {
            return redirect()->back();
         }

         return view('components.installment-register')->with('data', $data);

      } catch (Exception $err) {
         
         return abort(404);
      }
   }


   public function actionEditInstallment(Request $request)
   {

      session()->flash('page',  $page = (object)[
         'page' => 'students',
         'child' => 'register students',
      ]);

      DB::beginTransaction();

      try {
         //code...
         $bills = $request->except(['_token', '_method', 'sendEmail']);
         $totalBill = 0;
         $installment = 0;

         foreach($bills as $el)
         {
            if($el)
            {
               $installment++;
            }
         }

         foreach($bills as $key => $amount_installment)
         {
            $id = str_replace('index_', '', $key);

            $billExist = Bill::where("id", (int)$id)->first();

            if(!$billExist)
            {
               DB::rollBack();
               return redirect()->back()->withErrors([
                  'bill' => ['bills error when search installment !!!'],
               ])
               ->withInput($bills);
            }

            $amount = $amount_installment? str_replace('.','',$amount_installment) : 0;
            
            $totalBill+=$amount;

            if($amount != 0)
            {
               Bill::where("id", (int)$id)->update([
                  'amount_installment' => $amount,
                  'installment' => $installment,
               ]);
            } else {
               Bill::where("id", (int)$id)->delete();
            }
            
         }
         
         if(($billExist->amount-$billExist->dp) != $totalBill)
         {
            DB::rollBack();
               return redirect()->back()->withErrors([ 
                  'bill' => ['The total installment must be Rp ' . number_format($billExist->amount,0,',','.')],
               ])
            ->withInput($bills);
         }

         $student = Student::where('id', $billExist->student_id)->first();

         if(!$student){
            DB::rollBack();
               return redirect()->back()->withErrors([
                  'bill' => ['student not found !!!'],
               ])
               ->withInput($bills);
         }

         session()->flash('after_create_student', 'Successfully register student with name '. $student->name);

         DB::commit();

         if($billExist->type == 'Paket')
         {          
            return redirect('/admin/bills');
         }


         return redirect('/admin/detail/'.$student->unique_id);
         
      } catch (Exception $err) {
         return dd($err);
      }

   }

}