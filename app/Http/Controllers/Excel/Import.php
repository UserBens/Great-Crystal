<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use App\Imports\StudentImport;
use Illuminate\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Controller
{

    public function index() {
        try {

            session()->flash('page',  $page = (object)[
                'page' => 'students',
                'child' => 'imports',
             ]);

            return view('components.student.import.import-pages');
            
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function upload(Request $request) {
        
        try {

            $rules = array('import_student' => $request->import_student);

            $validator = Validator::make($rules, [
                'import_student' => 'required|mimes:xlsx,xls',
            ]);


            if($validator->fails()){

                return dd($validator->errors());
            }

            $event = Excel::import(new StudentImport, $request->file('import_student'));

            return redirect('/admin/register/imports')->with('status', 'success');

            //code...
        } catch (Exception $err) {
            return dd($err);
        }
    }


    public function downloadTemplate() : BinaryFileResponse {
        try {
            //code...
            return response()->download(public_path('downloads/import_student.xlsx'));

        } catch (Exception $err) {
            return dd($err);
        }
    }
    
}