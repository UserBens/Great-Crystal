<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
date_default_timezone_set('Asia/Jakarta');
class Brothers_or_sister extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'name',
      'date_birth',
      'grade',
      'student_id',
      'created_at',
      'updated_at',
    ];

    public function student()
    {
       return $this->hasOne(Student::class, 'id');
    }
}