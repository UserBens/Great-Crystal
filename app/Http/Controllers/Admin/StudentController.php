<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brothers_or_sister;
use App\Models\Grade;
use App\Models\Relationship;
use App\Models\Student;
use App\Models\Student_relation;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
   public function index(Request $request){

      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'database students',
         ]);
         

         $grades = Grade::orderBy('id', 'asc')->get();

         $form = (object) [
            'sort' => $request->sort? $request->sort : null,
            'order' => $request->order? $request->order : null,
            'status' => $request->status? $request->status : null,
            'search' => $request->search? $request->search : null,
            'type' => $request->type? $request->type : null,
            'grade_id' => $request->grade_id && $request->grade_id !== 'all'? $request->grade_id : null,
         ];

         $data = [];
         $order = $request->sort ? $request->sort : 'desc';
         
         $status = $request->status? ($request->status == 'active' ? true : false) : true;
         $is_graduate = $request->status && $request->status == 'graduate' ? true : false;
         
         if($request->search || ($request->grade_id && $request->order && $order && $request->status && $request->type && $request->grade_id)){
            
            $dataModel = new Student();
            
            $data = $dataModel->with('grade')
            ->where('is_active', $status)
            ->where('is_graduate', $is_graduate);
            
            if($form->search){

               $data = $data->where($request->type,'LIKE','%'. $request->search .'%');
            }
            
            if($form->grade_id){

               $data = $data->where('grade_id', $form->grade_id);
            }

            if($form->order && $order) {
               
               $data = $data->orderBy($request->order, $order);
               
            }

            $data = $data->paginate(15);


         } else {

            $data = Student::with('grade')->where('is_active', true)->orderBy('created_at', $order)->paginate(15);
         }

         
         return view('components.student.tableStudent')->with('data', $data)->with('form', $form)->with('grades', $grades);
      } catch (Exception $err) {
         //throw $th;
         return abort(500, 'Internal server error');
      }
   }


   public function detail($id){
      try {
         session()->flash('preloader', true);
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'database students',
         ]);
         //code...
         $student = Student::with(['relationship', 'grade'])->where('unique_id', $id)->first();
         
         if(!DB::table('students')->where('unique_id', $id)->first())
         {
            return abort(404);
         }

         $brotherOrSister = Student::find($student->id);
         $data = (object) [
            'student' => $student,
            'brother_or_sisters' => $brotherOrSister->brotherOrSister()->get(),
            'after_create' => false,
         ];

         // return $data;
         
         return view('components.student.detailStudent')->with('data', $data);

         
      } catch (Exception $err) {
         
         return abort(500);
      }
   }


   public function edit($id)
   {
      try {
         session()->flash('preloader', true);
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'database students',
         ]);
         
         $student = Student::with(['relationship', 'grade'])->where('unique_id', $id)->first();
         $brotherOrSister = Student::find($student->id);
         $allGrade = Grade::orderBy('id', 'asc')->get();
         
         $data = (object) [
            'student' => $student,
            'brother_or_sisters' => $brotherOrSister->brotherOrSister()->get(),
            'allGrade' => $allGrade,
         ];

         // return $data;  
         return view('components.student.editStudent')->with('data', $data);
      } catch (Exception $err) {
         //throw $th;

         return dd($err);
      }
   }


   public function actionEdit(Request $request, $id)
   {

      DB::beginTransaction();


      session()->flash('preloader', true);
      session()->flash('page',  $page = (object)[
         'page' => 'students',
         'child' => 'database students',
      ]);


      try {

         $date_format = new RegisterController();   
         $student_unique_id = Student::where('id', $id)->first()->unique_id;
         //code...
         $credentials = [
            'name' => $request->studentName,
            'grade_id' => $request->gradeId,
            'gender' => $request->studentGender,
            'religion' => $request->studentReligion,
            'nisn' => $request->nisn,
            'place_birth' => $request->studentPlace_birth,
            'date_birth' => $request->studentDate_birth ? $date_format->changeDateFormat($request->studentDate_birth) : null,
            'id_or_passport' => $request->studentId_or_passport,
            'nationality' => $request->studentNationality,
            'place_of_issue' => $request->studentPlace_of_issue,
            'date_exp' => $request->studentDate_exp ? $date_format->changeDateFormat($request->studentDate_exp) : null,
            // 'created_at' => $request->created_at ? date('Y-m-d H:i:s', strtotime($this->changeDateFormat($request->created_at))) : null,
         ];

         
         $rules = [
            'name' => $request->studentName,
            'grade_id' => $request->gradeId,
            'gender' => $request->studentGender,
            'religion' => $request->studentReligion,
            'nisn' => $request->nisn,
            'place_birth' => $request->studentPlace_birth,
            'date_birth' => $request->studentDate_birth ? $date_format->changeDateFormat($request->studentDate_birth) : null,
            'id_or_passport' => $request->studentId_or_passport,
            'nationality' => $request->studentNationality,
            'place_of_issue' => $request->studentPlace_of_issue,
            'date_exp' => $request->studentDate_exp !== '' ? $date_format->changeDateFormat($request->studentDate_exp) : null,
            // 'created_at' => $request->created_at ? date('Y-m-d H:i:s', strtotime($this->changeDateFormat($request->created_at))) : null,
            // Father rules
            'father_relation' => 'father',
            'father_name' => $request->fatherName,
            'father_religion' => $request->fatherReligion,
            'father_place_birth' => $request->fatherPlace_birth,
            'father_date_birth' => $request->fatherBirth_date ? $date_format->changeDateFormat($request->fatherBirth_date) : null,
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
            'mother_date_birth' => $request->motherBirth_date ? $date_format->changeDateFormat($request->motherBirth_date) : null,
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
            'brotherOrSisterBirth_date1' => $request->brotherOrSisterBirth_date1? $date_format->changeDateFormat($request->brotherOrSisterBirth_date1) : null,
            'brotherOrSisterGrade1' => $request->brotherOrSisterGrade1,
            'brotherOrSisterName2' => $request->brotherOrSisterName2, 
            'brotherOrSisterBirth_date2' => $request->brotherOrSisterBirth_date2? $date_format->changeDateFormat($request->brotherOrSisterBirth_date2) : null,
            'brotherOrSisterGrade2' => $request->brotherOrSisterGrade2,
            'brotherOrSisterName3' => $request->brotherOrSisterName3, 
            'brotherOrSisterBirth_date3' => $request->brotherOrSisterBirth_date3? $date_format->changeDateFormat($request->brotherOrSisterBirth_date3) : null,
            'brotherOrSisterGrade3' => $request->brotherOrSisterGrade3,
            'brotherOrSisterName4' => $request->brotherOrSisterName4, 
            'brotherOrSisterBirth_date4' => $request->brotherOrSisterBirth_date4? $date_format->changeDateFormat($request->brotherOrSisterBirth_date4) : null,
            'brotherOrSisterGrade4' => $request->brotherOrSisterGrade4,
            'brotherOrSisterName5' => $request->brotherOrSisterName5, 
            'brotherOrSisterBirth_date5' => $request->brotherOrSisterBirth_date5? $date_format->changeDateFormat($request->brotherOrSisterBirth_date5) : null,
            'brotherOrSisterGrade5' => $request->brotherOrSisterGrade5,
         ];     

         


         $credentialBrotherSister = [
            'brotherOrSisterName1' => $request->brotherOrSisterName1, 
            'brotherOrSisterBirth_date1' => $request->brotherOrSisterBirth_date1? $date_format->changeDateFormat($request->brotherOrSisterBirth_date1) : null,
            'brotherOrSisterGrade1' => $request->brotherOrSisterGrade1,
            'brotherOrSisterName2' => $request->brotherOrSisterName2, 
            'brotherOrSisterBirth_date2' => $request->brotherOrSisterBirth_date2? $date_format->changeDateFormat($request->brotherOrSisterBirth_date2) : null,
            'brotherOrSisterGrade2' => $request->brotherOrSisterGrade2,
            'brotherOrSisterName3' => $request->brotherOrSisterName3, 
            'brotherOrSisterBirth_date3' => $request->brotherOrSisterBirth_date3? $date_format->changeDateFormat($request->brotherOrSisterBirth_date3) : null,
            'brotherOrSisterGrade3' => $request->brotherOrSisterGrade3,
            'brotherOrSisterName4' => $request->brotherOrSisterName4, 
            'brotherOrSisterBirth_date4' => $request->brotherOrSisterBirth_date4? $date_format->changeDateFormat($request->brotherOrSisterBirth_date4) : null,
            'brotherOrSisterGrade4' => $request->brotherOrSisterGrade4,
            'brotherOrSisterName5' => $request->brotherOrSisterName5, 
            'brotherOrSisterBirth_date5' => $request->brotherOrSisterBirth_date5? $date_format->changeDateFormat($request->brotherOrSisterBirth_date5) : null,
            'brotherOrSisterGrade5' => $request->brotherOrSisterGrade5,
         ];
         
         
         $validator = Validator::make($rules, [
            'name' => 'string|required|min:3',
            'grade_id' => 'integer|required',
            'gender' => 'string|required',
            'religion' => 'string|required',
            'place_birth' => 'string|required',
            'date_birth' => 'date|required',
            'id_or_passport' => 'string|required|min:15|max:16',
            'nationality' => 'string|required|min:3',
            'place_of_issue' => 'nullable|string',
            'date_exp' => 'nullable|date',
            // father validation 
            'father_name' => 'string|required|min:3',
            'father_religion' => 'string|required',
            'father_place_birth' => 'string|required',
            'father_date_birth' => 'date|required',
            'father_id_or_passport' => 'string|required|min:15|max:16',
            'father_nationality' => 'string|required',
            'father_phone' => 'nullable|string|max:13|min:9',
            'father_home_address' => 'required|string',
            'father_mobilephone' => 'required|string|max:13|min:9',
            'father_telephone' => 'nullable|string|max:13|min:9',
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
            'mother_phone' => 'nullable|string|max:13|min:9',
            'mother_home_address' => 'required|string',
            'mother_telephone' => 'nullable|string|max:13|min:9',
            'mother_mobilephone' => 'required|string|max:13|min:9',
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
         ]);

         $dataId = Student::where('id_or_passport', $rules['id_or_passport'])->where('id', '<>', $id)->first();
         if($dataId)
         {
               DB::rollBack();
               return redirect('/admin/update/' . $student_unique_id)->withErrors(['id_or_passport' => 'Id or passport has been registered'])->withInput($rules);
         }

         if($request->nisn) 
         {

            $dataNisn = Student::where('nisn', $rules['nisn'])->where('id', '<>', $id)->first();
            
            if($dataNisn) 
            {
               DB::rollBack();
               return redirect('/admin/update/' . $student_unique_id)->withErrors(['nisn' => 'nisn has been registered'])->withInput($rules);
            }
         }

         if($validator->fails())
         {
            DB::rollBack();
            // return $validator->messages();
            return redirect('/admin/update/' . $student_unique_id)->withErrors($validator->messages())->withInput($rules);
         }


         Student::where('id', $id)->update($credentials);
         $relationshipPost = $this->handleRelationship($request, $id);
         $brotherOrSisterPost = $this->handleBrotherOrSister($request, $id);


         if(!$relationshipPost->status || !$brotherOrSisterPost->status) return $brotherOrSisterPost->error;


         DB::commit();
         
         $student = Student::with('relationship')->where('id', $id)->first();
         $brotherOrSister = Student::find($id);


         $data = (object) [
            'student' => $student,
            'brother_or_sisters' => $brotherOrSister->brotherOrSister()->get(),
            'after_create' => true,
         ];


         session()->flash('after_update_student');
         
         return view('components.student.detailStudent')->with('data', $data);
         
      } catch (Exception $err) {
         DB::rollBack();
         return abort(500, 'Internal server error !!!');
      }
   }


   private function handleRelationship($request, $id)
   {
      try {
         $date_format = new RegisterController();
         $credentialFather = [
            'relation' => 'father',
            'name' => $request->fatherName,
            'religion' => $request->fatherReligion,
            'place_birth' => $request->fatherPlace_birth,
            'date_birth' => $request->fatherBirth_date ? $date_format->changeDateFormat($request->fatherBirth_date) : null,
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


         $credentialMother = [
            'relation' => 'mother',
            'name' => $request->motherName,
            'religion' => $request->motherReligion,
            'place_birth' => $request->motherPlace_birth,
            'date_birth' => $request->motherBirth_date ? $date_format->changeDateFormat($request->motherBirth_date) : null,
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
         
         $student = Student::with('relationship')->where('id', $id)->first();

         foreach($student->relationship as $value)
         {
            if($value->relation == 'father')
            {
               Relationship::where('id', $value->id)->update($credentialFather);
            } else {
               Relationship::where('id', $value->id)->update($credentialMother);
            }
         }
         
         return (object)[
            'status' => true,
            'father' => $credentialFather,
            'mother' => $credentialMother,
         ];

      } catch (Exception $err) {
         
         DB::rollBack();
         
         return (object)[
            'status' => false,
            'error' => $err,
         ];
      }
   }


   private function handleBrotherOrSister($credentials, $id)
   {
      try {

         Brothers_or_sister::where('student_id', $id)->delete();
         $date_format = new RegisterController();

         $credentialsBrotherOrSister = [];

         if($credentials->brotherOrSisterName1 && $credentials->brotherOrSisterBirth_date1 && $credentials->brotherOrSisterGrade1)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $credentials->brotherOrSisterName1, 'date_birth' => $date_format->changeDateFormat($credentials->brotherOrSisterBirth_date1), 'grade' => $credentials->brotherOrSisterGrade1, 'student_id' => $id]);
         }
         if($credentials->brotherOrSisterName2 && $credentials->brotherOrSisterBirth_date2 && $credentials->brotherOrSisterGrade2)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $credentials->brotherOrSisterName2, 'date_birth' => $date_format->changeDateFormat($credentials->brotherOrSisterBirth_date2), 'grade' => $credentials->brotherOrSisterGrade2, 'student_id' => $id]);
         }
         if($credentials->brotherOrSisterName3 && $credentials->brotherOrSisterBirth_date3 && $credentials->brotherOrSisterGrade3)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $credentials->brotherOrSisterName3, 'date_birth' => $date_format->changeDateFormat($credentials->brotherOrSisterBirth_date3), 'grade' => $credentials->brotherOrSisterGrade3, 'student_id' => $id]);
         }
         if($credentials->brotherOrSisterName4 && $credentials->brotherOrSisterBirth_date4 && $credentials->brotherOrSisterGrade4)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $credentials->brotherOrSisterName4, 'date_birth' => $date_format->changeDateFormat($credentials->brotherOrSisterBirth_date4), 'grade' => $credentials->brotherOrSisterGrade4, 'student_id' => $id]);
         }
         if($credentials->brotherOrSisterName5 && $credentials->brotherOrSisterBirth_date5 && $credentials->brotherOrSisterGrade5)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $credentials->brotherOrSisterName5, 'date_birth' => $date_format->changeDateFormat($credentials->brotherOrSisterBirth_date5), 'grade' => $credentials->brotherOrSisterGrade5, 'student_id' => $id]);
         }

         Brothers_or_sister::insert($credentialsBrotherOrSister);



         return (object)[
            'status' => true,
            'data' => $credentialsBrotherOrSister,
         ];
      } catch (Exception $err) {

         DB::rollBack();
         return (object)[
            'status' => false,
            'error' => $err,
         ];
      }
   }

                                      
}