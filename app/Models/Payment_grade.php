<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
date_default_timezone_set('Asia/Jakarta');
class Payment_grade extends Model
{
   use HasFactory;

   protected $fillable =[
      'id',
      'grade_id',
      'type',
      'amount',
      'created_at',	
      'updated_at'	
   ];


   public function grade()
   {
      return $this->belongsTo(Grade::class);
   }
}