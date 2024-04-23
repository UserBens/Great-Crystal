<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
date_default_timezone_set('Asia/Jakarta');
class InstallmentPaket extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_id',
        'child_id',
        'created_at',
        'updated_at',
    ];
}
