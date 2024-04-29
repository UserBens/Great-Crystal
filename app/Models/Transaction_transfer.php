<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction_transfer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function accountNumber()
    {
        return $this->belongsTo(AccountNumber::class, 'transfer'); // Ubah 'transfer' sesuai dengan nama kolom foreign key yang sesuai
    }
}
