<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
   public function index(Request $request)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'teachers',
         'child' => 'database teachers',
      ]);
      

      try {

         $form = (object) [
            'sort' => $request->sort? $request->sort : null,
            'order' => $request->order? $request->order : null,
            'status' => $request->status? $request->status : null,
            'search' => $request->search? $request->search : null,
            'type' => $request->type? $request->type:  null,
         ];

         $data = [];

         $order = $request->sort ? $request->sort : 'desc';
         $status = $request->status? ($request->status == 'true' ? true : false) : true;
         
         if($request->type && $request->search && $request->order){
            
            $data = Teacher::where('is_active', $status)->where($request->type,'LIKE','%'. $request->search .'%')->orderBy($request->order, $order)->paginate(15);
         } else if($request->type && $request->search)
         {
            $data = Teacher::where('is_active', $status)->where($request->type,'LIKE','%'. $request->search .'%')->orderBy('created_at', $order)->paginate(15);
         } else if($request->order) {
            $data = Teacher::where('is_active', $status)->orderBy($request->order, $order)->paginate(15);
         } else {

            $data = Teacher::where('is_active', $status)->orderBy('created_at', $order)->paginate(15);
            
         }
         return view('components.teacher.data-teacher')->with('data', $data)->with('form', $form);

      } catch (Exception $err) {
         return dd($err);
      }
   }
   public function getById($id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'teachers',
         'child' => 'database teachers',
      ]);

      

      try {
         $data = Teacher::where('unique_id', $id)->first();
         // return view('components.teacher.data-teacher')->with('data', $data);
         return view('components.teacher.detail-teacher')->with('data', $data);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function pagePost()
   {
      session()->flash('page',  $page = (object)[
         'page' => 'teachers',
         'child' => 'database teachers',
      ]);

      
      
      try {
         
         
         $data = Teacher::orderBy('id', 'desc')->get();
         return view('components.teacher.register-teacher')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }
   
   public function actionPost(Request $request)
   {
      
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'teachers',
            'child' => 'database teachers',
         ]);
         
         session()->flash('preloader', true);
         
         $var = DB::table('teachers')->latest('id')->first();
         $unique_id = '';
         
         if(date('Ym') == substr($var->unique_id, 0, 6))
         {
            $unique_id = (string)date('Ym') . str_pad(ltrim(substr($var->unique_id, 7) + 1, '0'), 4, '0', STR_PAD_LEFT);
         } else {
            $unique_id = (string)date('Ym') . str_pad('1', 4, '0', STR_PAD_LEFT);
         }

         $credentials = [
            'name' => $request->name,
            'unique_id' => $unique_id,
            'is_active' => 1,
            'nik' => $request->nik,
            'gender' => $request->gender, 
            'place_birth' => $request->place_birth, 
            'date_birth' => $this->handleDate($request->date_birth, true), 
            'nationality' => $request->nationality, 
            'religion' => $request->religion,
            'home_address' => $request->home_address, 
            'temporary_address' => $request->temporary_address, 
            'handphone' => $request->handphone, 
            'email' => $request->email, 
            'last_education' => $request->last_education, 
            'major' => $request->major, 
         ];


         // return $credentials;


         $validator = Validator::make($credentials, [
            'name' => 'required|min:3|string',
            'nik' => 'required|string|min:9|max:16|unique:teachers',
            'gender' => 'required|string|in:Male,Female',
            'place_birth' => 'required|string',
            'date_birth' => 'required|date',
            'nationality' => 'required|string',
            'home_address' => 'required|string',
            'religion' => 'required|string',
            'temporary_address' => 'required|string', 
            'handphone' => 'required|string|max:13|min:9|unique:teachers', 
            'email' => 'required|email|string|unique:teachers', 
            'last_education' => 'required|string', 
            'major' => 'required|string', 
         ]);

         if($validator->fails())
         {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/admin/teachers/register')->withErrors($validator->messages())->withInput($credentials);
         }

         $data = Teacher::create($credentials);

         session()->flash('after_create_teacher', (object) [
            'name' => $data->name,
         ]);
         return redirect('/admin/teachers/detail/' . $unique_id);
         
      } catch (Exception $err) {
         return dd($err);
      }
   }


   public function editPage($id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'teachers',
         'child' => 'database teachers',
      ]);

      
      
      try {

         $data = Teacher::where('unique_id', $id)->first();
         // return view('components.teacher.data-teacher')->with('data', $data);
         return view('components.teacher.edit-teacher')->with('data', $data);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function actionEdit(Request $request, $id)
   {

      session()->flash('page',  $page = (object)[
         'page' => 'teachers',
         'child' => 'database teachers',
      ]);

      session()->flash('preloader', true);

      try {
         
         $credentials = $request->only(['id', 'name', 'nik', 'gender', 'place_birth', 'date_birth', 'nationality', 'home_address', 'religion', 'temporary_address', 'email', 'handphone', 'last_education', 'major']);
         
         $credentials['date_birth'] = $this->handleDate($credentials['date_birth'], 'Y-m-d');

         $validator = Validator::make($credentials, [
            'name' => 'required|min:3|string',
            'nik' => 'required|string|min:9|max:16',
            'gender' => 'required|string|in:Male,Female',
            'place_birth' => 'required|string',
            'date_birth' => 'required|date',
            'nationality' => 'required|string',
            'email' => 'required|string|email',
            'handphone' => 'required|string|max:13|min:9',
            'last_education' => 'required|string',
            'major' => 'required|string',
            'temporary_address' => 'required|string',
            'home_address' => 'required|string',
            'religion' => 'required|string',
         ]);
         
         $checkUniqueNik = Teacher::where('nik', $request->nik)->first();
         $checkUniqueEmail = Teacher::where('email', $request->email)->first();
         $checkUniqueHandphone = Teacher::where('handphone', $request->handphone)->first();
         

         
         if($checkUniqueNik) if($checkUniqueNik->id != $id)
         {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/admin/teachers/' . $id)->withErrors(['nik' => 'nik or passport has already been taken.'])->withInput($credentials);
         } 
         if($checkUniqueEmail) if($checkUniqueEmail->id != $id) {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/admin/teachers/' . $id)->withErrors(['email' => 'email has already been taken.'])->withInput($credentials);
         } 
         
         if($checkUniqueHandphone) if($checkUniqueHandphone->id != $id) {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/admin/teachers/' . $id)->withErrors(['handphone' => 'Mobilephone has already been taken.'])->withInput($credentials);
         }
         
         if($validator->fails())
         {
            
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/admin/teachers/' . $id)->withErrors($validator->messages())->withInput($credentials);
         }
         
         Teacher::where('id', $id)->update($credentials);
         
         session()->flash('after_update_teacher');
         $target = Teacher::where('id', $id)->first();
         
         return redirect('/admin/teachers/detail/' . $target->unique_id);
         
      } catch (Exception $err) {
         return dd($err);
      }
   }



   public function handleDate($date, $format = false)
   {

      $dateStr = '';

      if($format)
      {
         
         $date_format = explode('/', $date);
         return $date_format[2] . '-' . $date_format[1] .  '-' . $date_format[0];
      } else {
         $date_format = explode('-', $date);
         return $date_format[2] . '/' . $date_format[1] .  '/' . $date_format[0];
      }
   }


   public function deactivated($id)
   {
      try {
         
         if(!Teacher::where('id', $id)->first())
         {
            return response()->json([
               'message' => 'Teacher with id ' . $id . ' not found!!!',
            ], 404);
         }

         Teacher::where('id', $id)->update([
            'is_active' => 0,
         ]);

         return response()->json([
            'success' => true,
         ]);

      } catch (Exception $err) {
         //throw $th;
         return response()->json([
            'success' => false,
         ]);
      }
   }

   public function activated($id)
   {
      try {
         
         if(!Teacher::where('id', $id)->first())
         {
            return response()->json([
               'message' => 'Teacher with id ' . $id . ' not found!!!',
            ], 404);
         }

         Teacher::where('id', $id)->update([
            'is_active' => true,
         ]);

         return response()->json([
            'success' => true,
         ]);

      } catch (Exception $err) {
         //throw $th;
         return response()->json([
            'success' => false,
         ]);
      }
   }
}