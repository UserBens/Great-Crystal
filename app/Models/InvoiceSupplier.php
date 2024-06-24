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
        return $this->belongsTo(SupplierData::class, 'supplier_name', 'name');
    }

    public function statuses()
    {
        return $this->hasMany(InvoiceSupplierStatus::class, 'no_invoice', 'no_invoice');
    }
}
