<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
date_default_timezone_set('Asia/Jakarta');
class statusInvoiceMail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'bill_id',
        'status',
        'charge',
        'past_due',
        'is_paid',
        'is_change',
        'created_at',
        'updated_at',
    ];


    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }
}
