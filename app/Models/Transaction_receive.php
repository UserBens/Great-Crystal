<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction_receive extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function transferAccount()
    {
        return $this->belongsTo(Accountnumber::class, 'transfer_account_id');
    }

    public function depositAccount()
    {
        return $this->belongsTo(Accountnumber::class, 'deposit_account_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
