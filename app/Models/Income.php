<?php

namespace App\Models;

use App\Models\Bill;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'student_id',
        'amount_received',
        'received_at',
    ];

    // Relationship with the Bill model
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    // Relationship with the Student model
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
