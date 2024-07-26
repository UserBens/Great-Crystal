<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSupplier extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function supplier()
    {
        return $this->belongsTo(SupplierData::class, 'supplier_id');
    }

    public function accountnumber()
    {
        return $this->belongsTo(AccountNumber::class, 'accountnumber_id');
    }

}
