<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceAccount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function accountnumber()
    {
        return $this->belongsTo(Accountnumber::class);
    }

    public function post()
    {
        $this->posted = true;
        $this->save();

        // Update balance for the next month
        $nextMonth = \Carbon\Carbon::parse($this->month)->addMonth()->format('Y-m-01');
        $nextBalance = BalanceAccount::firstOrNew([
            'accountnumber_id' => $this->accountnumber_id,
            'month' => $nextMonth,
        ]);
        $nextBalance->debit = $this->debit;
        $nextBalance->credit = $this->credit;
        $nextBalance->save();
    }

    public function unpost()
    {
        $this->posted = false;
        $this->save();

        // Remove balance for the next month
        $nextMonth = \Carbon\Carbon::parse($this->month)->addMonth()->format('Y-m-01');
        BalanceAccount::where('accountnumber_id', $this->accountnumber_id)
            ->where('month', $nextMonth)
            ->delete();
    }
}
