<?php

namespace App\Models;

use App\Models\Income;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expenditure extends Model
{
    use HasFactory;

    protected $fillable = [
        'income_id',
        'description',
        'amount_spent',
        'spent_at',
    ];

    public function income()
    {
        return $this->belongsTo(Income::class);
    }
}
