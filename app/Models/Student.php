<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
date_default_timezone_set('Asia/Jakarta');
class Student extends Model
{
    use HasFactory;


    protected $fillable = [
      'id',
      'is_active',
      'unique_id',
      'name',
      'grade_id',
      'gender',
      'religion',
      'nisn',
      'place_birth',
      'date_birth',
      'id_or_passport',
      'nationality',
      'place_of_issue',
      'date_exp',
      'created_at',
      'updated_at',
    ];

    public function relationship()
    {
       return $this->belongsToMany(Relationship::class, 'student_relations', 'student_id', 'relation_id');
    }

    public function grade()
    {
      return $this->belongsTo(Grade::class,'grade_id');
    }

    public function brotherOrSister()
    {
       return $this->hasMany(Brothers_or_sister::class, 'student_id');
    }

    public function bill()
    {
       return $this->hasMany(Bill::class, 'student_id');
    }

    public function payment_student()
    {
       return $this->hasMany(Payment_student::class, 'student_id');
    }


    public function spp_student()
    {
       return $this->hasOne(Payment_student::class, 'student_id');
    }

    public function book()
   {
      return $this->belongsToMany(Book::class, 'book_students', 'student_id', 'book_id')->withPivot('created_at');
   }
}