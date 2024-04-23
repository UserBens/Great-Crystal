<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthFeeController extends Controller
{

    private $date_start, $date_end;

    public function __construct(string $date_start, string $date_end)
    {
        $this->date_start = $date_start;
        $this->date_end = $date_end;
    }

    public function index($month) {

        $monthFeeFormated = [];
        $grade_id = [];

        $monthFee = DB::table('students')->select('bills.id', 'grades.name as grade_name', 'grades.class as grade_class','students.name','bills.type','bills.created_at'
        ,'bills.deadline_invoice','bills.amount','bills.charge', 'bills.amount','bills.paid_date','bills.paidOf', 'students.id as student_id', 'grades.id as grade_id', 'bills.number_invoice')
        ->join('bills', 'bills.student_id', '=', 'students.id')
        ->join('grades', 'grades.id', '=', 'students.grade_id')
        ->where('bills.type', 'SPP')
        ->whereBetween('bills.deadline_invoice', [$this->date_start, $this->date_end])
        ->orderBy('students.grade_id', 'asc')
        ->orderBy('students.name', 'asc')
        ->get();
        
        $g_id = null;
        $start_g = 2;


        foreach($monthFee as $idx => $bill){

            if(count($monthFee) === $idx+1) {
                $bill->grade_id == $g_id ?
                    array_push($grade_id, [$start_g, $idx+2]) :
                   ( 
                    array_push($grade_id, [$start_g, $idx+1]) &&
                    array_push($grade_id, [$idx+2, $idx+2])
                   );

            } else if($g_id && $bill->grade_id != $g_id) {
                array_push($grade_id, [$start_g, $idx+1]);
                $start_g=$idx+2;
            }

            $g_id = (int)$bill->grade_id;

            $obj = (object) [
                'no_invoice' => '#' . $bill->number_invoice,
                'grades' => $bill->grade_name . ' ' . $bill->grade_class,
                'name' => $bill->name,
                'type' => "Monthly Fee",
                'created_at' => date('Y-m-d', strtotime($bill->created_at)),
                'deadline_invoice' => $bill->deadline_invoice,
                'charge' => $bill->charge? $this->currencyToIdr($bill->charge) : "",
                'amount'=> $this->currencyToIdr($bill->amount),
                'paid_date' => $bill->paid_date,
                'status' => $bill->paidOf? "Lunas": "Belum lunas",
            ];

            array_push($monthFeeFormated, $obj);
        }

        return (object) [
            'data' => $monthFeeFormated,
            'grade_id' => $grade_id,
        ];
    }


    private function currencyToIdr(int $currency){

        return 'Rp.' . number_format($currency, 0, '', ',');
    }
}
