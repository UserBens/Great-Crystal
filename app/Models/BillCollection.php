<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

date_default_timezone_set('Asia/Jakarta');

class BillCollection extends Model
{
    use HasFactory;


    protected $fillable = [
        'id', 
        'bill_id',
        'book_id',
        'name',
        'amount',
        'discount',
        'created_at',
        'updated_at',
    ];



    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
