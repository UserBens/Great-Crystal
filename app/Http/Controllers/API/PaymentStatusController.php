<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
    public function checkStatus($uniqueId)
    {
        $currentMonth = Carbon::now();

        $bill = Bill::whereHas('student', function ($query) use ($uniqueId) {
            $query->where('unique_id', $uniqueId);
        })
            ->whereMonth('deadline_invoice', $currentMonth->month)
            ->whereYear('deadline_invoice', $currentMonth->year)
            ->where('paidOf', false)
            ->first();

        return response()->json([
            'has_unpaid_bill' => !is_null($bill),
            'message' => !is_null($bill) ? 'You have not paid the bill for ' . $currentMonth->format('F Y') : null,
            'amount' => $bill ? $bill->amount : 0
        ]);
    }

    public function getPaymentHistory($uniqueId)
    {
        $currentMonth = Carbon::now()->startOfMonth(); // Awal bulan ini
        $startDate = $currentMonth->copy()->subMonths(2); // 2 bulan sebelum bulan ini

        $bills = Bill::whereHas('student', function ($query) use ($uniqueId) {
            $query->where('unique_id', $uniqueId);
        })
            ->whereBetween('deadline_invoice', [$startDate, $currentMonth->endOfMonth()])
            ->orderBy('deadline_invoice', 'desc')
            ->get()
            ->map(function ($bill) {
                return [
                    'month' => Carbon::parse($bill->deadline_invoice)->translatedFormat('F'), // Menggunakan format bulan terjemahan
                    'year' => Carbon::parse($bill->deadline_invoice)->format('Y'),
                    'amount' => $bill->amount,
                    'status' => $bill->paidOf ? 'Lunas' : 'Belum Lunas',
                    'payment_date' => $bill->paidOf ? Carbon::parse($bill->payment_date)->format('d F Y') : null,
                    'due_date' => Carbon::parse($bill->deadline_invoice)->format('d F Y')
                ];
            });

        return response()->json($bills);
    }
}
