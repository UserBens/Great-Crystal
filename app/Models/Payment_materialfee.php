<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_materialfee extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Guard 'id', semua kolom lain bisa diisi secara mass-assignment

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function installment_bills()
    {
        return $this->hasMany(MaterialFeeInstallment::class, 'material_fee_id');
    }

    // Di model Payment_materialfee
    public function getPaidInstallmentsCountAttribute()
    {
        return $this->installment_bills()
            ->whereHas('bill', function ($query) {
                $query->where('paidOf', true);
            })
            ->count();
    }
}
