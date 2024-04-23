<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class BillDailyReports extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 
        'bill_id',
        'status',
        'date',
        'created_at',
        'updated_at',
    ];
}
