<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
date_default_timezone_set('Asia/Jakarta');
class Relationship extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'name',
      'relation',
      'place_birth',
      'religion',
      'date_birth',
      'occupation',
      'company_name',
      'company_address',
      'home_address',
      'telephone',
      'mobilephone',
      'id_or_passport',
      'nationality',
      'phone',
      'email',
      'created_at',
      'updated_at',
    ];



   public function student()
      {
         return $this->belongsToMany(Student::class, 'student_relations', 'relation_id', 'student_id');
      }
}