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
        return $this->belongsTo(AccountNumber::class, 'transfer');
    }

    public function depositAccount()
    {
        return $this->belongsTo(AccountNumber::class, 'deposit');
    }
}
