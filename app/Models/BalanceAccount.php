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
        return $this->belongsTo(Accountnumber::class, 'accountnumber_id');
    }

    // Dalam model BalanceAccount
    public function updateEndingBalance()
    {
        // Ambil semua transaksi terkait dengan akun ini
        $totalTransactions = $this->transactions()->sum('amount');

        // Hitung ending_balance berdasarkan beginning_balance dan total transaksi
        $this->ending_balance = $this->beginning_balance + $totalTransactions;
        $this->save();
    }

    public function postMonthly()
    {
        // Menandai saldo sebagai diposting untuk bulan ini
        $this->posted = true;
        $this->save();

        // Logika tambahan jika perlu
    }

    public function unpostMonthly()
    {
        // Membuka saldo yang telah diposting untuk bulan ini
        $this->posted = false;
        $this->save();

        // Logika tambahan jika perlu
    }

    public function isPostedForMonth($month, $year)
    {
        // Cek apakah saldo sudah diposting untuk bulan dan tahun tertentu
        return $this->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('posted', true)
            ->exists();
    }
}
