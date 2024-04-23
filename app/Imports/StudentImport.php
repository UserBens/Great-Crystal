<?php

namespace App\Imports;

use App\Models\Grade;
use App\Models\Relationship;
use App\Models\Student;
use App\Models\Student_relation;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentImport implements ToCollection
{

    public function collection(Collection $row)
    {
       
       try {
          //code...
          
          if($row[0][0] !== 'Male') {
               DB::beginTransaction();
                
               foreach($row as $idx => $reg) {

                if($idx >= 1 && $reg[0]) {

                     $grade = explode(" - ",$reg[4]);
                     
                     
                     $grade_id = Grade::where('name', $grade[0])->where('class', $grade[1])->first('id');
                     //Lakukan validasi 

                     if(!$grade_id) {
                        info('At line ' . $idx+1 .' grade not found!');
                        session()->flash('import_status', [ 
                            'code' => 400,
                            'msg' => 'At line ' . $idx+1 .' grade not found!',
                        ]);
                        return;
                     }

                     $grade_id = $grade_id->id;
                        
                        $data = [
                            'name' => $reg[0],
                            'id_or_passport' => (string)$reg[1],
                            'gender' => $reg[2],
                            'religion' => $reg[3],
                            'grade_id' => $grade_id,
                            'nisn' => $reg[5],
                            'date_birth' => $reg[6] ? $this->dateFormated($reg[6]) : null,
                            'place_birth' => $reg[7],
                            'nationality' => $reg[8],
                            'place_of_issue' => $reg[9],
                            'date_exp' =>$reg[10] ? $this->dateFormated($reg[10]) : null,
                            'father_relation' => 'father',
                            'father_name' => $reg[11],
                            'father_id_or_passport' => (string)$reg[12],
                            'father_religion' => $reg[13],
                            'father_place_birth' => $reg[14],
                            'father_date_birth' => $reg[15] ? $this->dateFormated($reg[15]) : null,
                            'father_nationality' => $reg[16],
                            'father_occupation' => $reg[17],
                            'father_company_name' => $reg[18],
                            'father_company_address' => $reg[19],
                            'father_phone' => $reg[20],
                            'father_home_address' => $reg[21],
                            'father_telephone' => $reg[22],
                            'father_mobilephone' => $reg[23],
                            'father_email' => $reg[24],
                            'mother_relation' => 'mother',
                            'mother_name' => $reg[25],
                            'mother_id_or_passport' => (string)$reg[26],
                            'mother_religion' => $reg[27],
                            'mother_place_birth' => $reg[28],
                            'mother_date_birth' => $reg[29]? $this->dateFormated($reg[29]) : null,
                            'mother_nationality' => $reg[30],
                            'mother_occupation' => $reg[31],
                            'mother_company_name' => $reg[32],
                            'mother_company_address' => $reg[33],
                            'mother_phone' => $reg[34],
                            'mother_home_address' => $reg[35],
                            'mother_telephone' => $reg[36],
                            'mother_mobilephone' => $reg[37],
                            'mother_email' => $reg[38],
                        ];

                        $validator = Validator::make($data, [
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
                    ]);

                    info($data);

                    if($validator->fails()){

                        info('masuk errors');
                        info('At line ' . $idx+1 .' '.$validator->errors()->first());
                        session()->flash('import_status', [ 
                            'code' => 400,
                            'msg' => 'At line ' . $idx+1 .' '.$validator->errors()->first(),
                        ]);
                        DB::rollBack();
                        return;
                    }

                    $var = Student::orderBy('id', 'desc')->first();
                    $unique_id = '';

                    if( $var && date('Ym') == substr($var->unique_id, 0, 6))
                    {
                       $unique_id = (string)date('Ym') . str_pad(ltrim(substr($var->unique_id, 7) + 1, '0'), 4, '0', STR_PAD_LEFT);
                    } else {
                       $unique_id = (string)date('Ym') . str_pad('1', 4, '0', STR_PAD_LEFT);
                    } 

                    $student = Student::create([
                        'name' => $reg[0],
                        'id_or_passport' => (string)$reg[1],
                        'is_active' => true,
                        'unique_id' => $unique_id,
                        'gender' => $reg[2],
                        'religion' => $reg[3],
                        'grade_id' => $grade_id,
                        'nisn' => $reg[5],
                        'date_birth' => $reg[6] ? $this->dateFormated($reg[6]) : null,
                        'place_birth' => $reg[7],
                        'nationality' => $reg[8],
                        'place_of_issue' => $reg[9],
                        'date_exp' =>$reg[10] ? $this->dateFormated($reg[10]) : null,
                    ]);

                    $credentialsFather = [
                        'relation' => 'father',
                        'name' => $reg[11],
                        'id_or_passport' => (string)$reg[12],
                        'religion' => $reg[13],
                        'place_birth' => $reg[14],
                        'date_birth' => $reg[15] ? $this->dateFormated($reg[15]) : null,
                        'nationality' => $reg[16],
                        'occupation' => $reg[17],
                        'company_name' => $reg[18],
                        'company_address' => $reg[19],
                        'phone' => $reg[20],
                        'home_address' => $reg[21],
                        'telephone' => $reg[22],
                        'mobilephone' => $reg[23],
                        'email' => $reg[24],
                     ];
            
            
                     $credentialsMother = [
                        'relation' => 'mother',
                        'name' => $reg[25],
                        'id_or_passport' => (string)$reg[26],
                        'religion' => $reg[27],
                        'place_birth' => $reg[28],
                        'date_birth' => $reg[29]? $this->dateFormated($reg[29]) : null,
                        'nationality' => $reg[30],
                        'occupation' => $reg[31],
                        'company_name' => $reg[32],
                        'company_address' => $reg[33],
                        'phone' => $reg[34],
                        'home_address' => $reg[35],
                        'telephone' => $reg[36],
                        'mobilephone' => $reg[37],
                        'email' => $reg[38],
                     ];
            
            
                     $checkIdFather = Relationship::where('id_or_passport', $credentialsFather['id_or_passport'])->first();
                     $checkIdMother = Relationship::where('id_or_passport', $credentialsMother['id_or_passport'])->first();
            
            
                     $father = $checkIdFather && $checkIdFather->relation == 'father'? $this->updateRelation($checkIdFather->id, $credentialsFather) : Relationship::create($credentialsFather);
                     $mother = $checkIdMother && $checkIdMother->relation == 'mother'? $this->updateRelation($checkIdMother->id, $credentialsMother) : Relationship::create($credentialsMother);
            
                     Student_relation::create(['student_id' => $student->id,'relation_id' => $father->id]);
                     Student_relation::create(['student_id' => $student->id,'relation_id' => $mother->id]);

                     
                    }
                }
                
                DB::commit();
    
                info("success");
    
                session()->flash('import_status', [ 
                    'code' => 200,
                    'msg' => 'success',
                ]);
            }

        } catch (Exception $th) {

            info('masuk error'. json_encode($th));
            
            session()->flash('import_status', [ 
                'code' => 500,
                'msg' => 'Internal server error',
            ]);
            DB::rollBack();
        }
    }


    public function dateFormated($date): string {

        $filter = str_replace('=DATE(', '', $date);
        $filter = str_replace(')', '', $filter);

        $n = explode(',', $filter);
        $createDate = Carbon::create($n[0], $n[1], $n[2])->format('Y-m-d');

        return $createDate;
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
}