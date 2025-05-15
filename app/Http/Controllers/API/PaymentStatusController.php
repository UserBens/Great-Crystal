<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            'amount' => $bill ? $bill->amount : 0,
            'bill_id' => $bill ? $bill->id : null // Add this line to include the bill ID
        ]);
    }

    public function getPaymentHistory($uniqueId)
    {
        $bills = Bill::whereHas('student', function ($query) use ($uniqueId) {
            $query->where('unique_id', $uniqueId);
        })
            ->orderBy('deadline_invoice', 'desc')
            ->get()
            ->map(function ($bill) {
                return [
                    'id' => $bill->id, // Add this line to include the bill ID
                    'month' => Carbon::parse($bill->deadline_invoice)->translatedFormat('F'),
                    'year' => Carbon::parse($bill->deadline_invoice)->format('Y'),
                    'amount' => $bill->amount,
                    'status' => $bill->paidOf ? 'Lunas' : 'Belum Lunas',
                    'payment_date' => $bill->paidOf ? Carbon::parse($bill->payment_date)->format('d F Y') : null,
                    'due_date' => Carbon::parse($bill->deadline_invoice)->format('d F Y')
                ];
            });

        return response()->json($bills);
    }

    public function getPaymentDetail($uniqueId, $billId)
    {
        $bill = Bill::whereHas('student', function ($query) use ($uniqueId) {
            $query->where('unique_id', $uniqueId);
        })
            ->where('id', $billId)
            ->first();

        if (!$bill) {
            return response()->json(['error' => 'Bill not found'], 404);
        }

        // Get student info
        $student = $bill->student;

        return response()->json([
            'data' => [
                'id' => $bill->id,
                'month' => Carbon::parse($bill->deadline_invoice)->translatedFormat('F'),
                'year' => Carbon::parse($bill->deadline_invoice)->format('Y'),
                'amount' => $bill->amount,
                'status' => $bill->paidOf ? 'Lunas' : 'Belum Lunas',
                'payment_date' => $bill->paidOf ? Carbon::parse($bill->payment_date)->format('d F Y') : null,
                'due_date' => Carbon::parse($bill->deadline_invoice)->format('d F Y'),
                'type' => 'Monthly Fee',
                'subject' => 'Tuition Fee',
                'student_name' => $student->name ?? 'Unknown',
                'student_email' => $student->email ?? 'Unknown'
            ]
        ]);
    }
}
