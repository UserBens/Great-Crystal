<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
date_default_timezone_set('Asia/Jakarta');
class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'is_active',
      'unique_id',
      'name',
      'place_birth',
      'religion',
      'date_birth',
      'home_address',
      'nationality',
      'nik',
      'gender',
      'email',
      'handphone',
      'temporary_address',
      'last_education',
      'major',
      'created_at',
      'updated_at',
   ];

   public function grade()
   {
      return $this->hasMany(Grade::class);
   }
}