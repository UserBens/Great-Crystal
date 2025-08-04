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

    public function transferAccount()
    {
        return $this->belongsTo(Accountnumber::class, 'transfer_account_id');
    }

    public function depositAccount()
    {
        return $this->belongsTo(Accountnumber::class, 'deposit_account_id');
    }

    // Relasi untuk old_transfer_account_id
    public function oldAccount()
    {
        return $this->belongsTo(Accountnumber::class, 'old_transfer_account_id');
    }

    // Relasi untuk new_transfer_account_id
    public function newAccount()
    {
        return $this->belongsTo(Accountnumber::class, 'new_transfer_account_id');
    }
}
