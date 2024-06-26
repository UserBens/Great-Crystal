<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSupplier extends Model
{
    use HasFactory;

    protected $fillable = ['no_invoice', 'supplier_name', 'amount', 'date', 'nota', 'deadline_invoice', 'payment_status', 'description', 'image_path'];

    public function supplier()
    {
        return $this->belongsTo(SupplierData::class, 'supplier_name', 'name');
    }

    // public function statuses()
    // {
    //     return $this->hasMany(InvoiceSupplierStatus::class, 'no_invoice', 'no_invoice');
    // }
}
