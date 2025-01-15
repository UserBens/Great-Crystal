<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialFeeInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_fee_id',
        'bill_id',
        'installment_number'
    ];

    public function material_fee()
    {
        return $this->belongsTo(Payment_materialfee::class, 'material_fee_id');
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }
}
