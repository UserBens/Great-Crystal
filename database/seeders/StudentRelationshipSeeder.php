<?php

namespace Database\Seeders;

use App\Models\Brothers_or_sister;
use App\Models\Relationship;
use App\Models\Student;
use App\Models\Student_relation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class StudentRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(public_path('data/student.json'));
        $data = json_decode($json);
  
        foreach ($data as $value) {
            $student = Student::create([
               "is_active"=> $value->student->is_active,
               "unique_id"=> $value->student->unique_id,
               "name"=> $value->student->name,
               "grade_id"=> $value->student->grade_id,
               "gender"=> $value->student->gender,
               "religion"=> $value->student->religion,
               "place_birth"=> $value->student->place_birth,
               "date_birth"=> $value->student->date_birth,
               "id_or_passport"=> $value->student->id_or_passport,
               "nationality"=> $value->student->nationality,
               "place_of_issue"=> $value->student->place_of_issue,
               "date_exp"  => $value->student->date_exp,
               "created_at" => now(),
               "updated_at" => now(),
            ]);

            foreach ($value->student->relationship as $el)
            {
               $relationship = Relationship::create([
                  "name"=> $el->name,
                  "relation"=> $el->relation,
                  "place_birth"=> $el->place_birth,
                  "religion"=> $el->religion,
                  "date_birth"=> $el->date_birth,
                  "occupation"=> $el->occupation,
                  "company_name"=> $el->company_name,
                  "company_address"=> $el->company_address,
                  "home_address"=> $el->home_address,
                  "telephone"=> $el->telephone,
                  "mobilephone"=> $el->mobilephone,
                  "id_or_passport"=> $el->id_or_passport,
                  "nationality"=> $el->nationality,
                  "phone"=> $el->phone,
                  "email"=> $el->email,
                  "created_at"=> now(),
                  "updated_at"=> now(),
               ]);

               Student_relation::create([
                  'student_id' => $student->id,
                  'relation_id' => $relationship->id,
               ]);
            }
        }


        foreach ($value->brother_or_sisters as $el) {
               
               Brothers_or_sister::create([
                  "name"=> $el->name,
                  "date_birth"=> $el->date_birth,
                  "grade"=> $el->grade,
                  "student_id"=> $el->student_id,
                  "created_at"=> now(),
                  "updated_at"=> now()
            ]);
         }
    }
}