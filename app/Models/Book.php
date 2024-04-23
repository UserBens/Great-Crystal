<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PhpParser\Node\Expr\FuncCall;
date_default_timezone_set('Asia/Jakarta');
class Book extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'grade_id',
        'name',
        'amount',
        'nisb',
        'created_at',
        'updated_at',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function student()
    {
        return $this->belongsToMany(Student::class, 'book_students', 'book_id', 'student_id');
    }

    public function book_collection()
    {
        return $this->hasMany(BillCollection::class, 'book_id');
    }
}
