<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
date_default_timezone_set('Asia/Jakarta');
class Book_student extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'student_id',
        'book_id',
        'created_at',
        'updated_at',
    ];
}
