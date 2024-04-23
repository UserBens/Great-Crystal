<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialFeeController extends Controller
{
    protected $start, $end;

    public function __construct(string $start, string $end) {
        $this->start = $start;
        $this->end = $end;
    }

    public function index(): object {

        $materialFeeFormated = [];
        $grade_id = [];

        $materialFee = DB::table('students')->select('bills.id', 'grades.name as grade_name', 'grades.class as grade_class','students.name','bills.type','bills.installment','bills.created_at'
        ,'bills.deadline_invoice','bills.amount','bills.dp','bills.charge', 'bills.amount','bills.paid_date','bills.paidOf', 
        'bills.amount_installment', 'students.id as student_id', 'grades.id as grade_id', 'bills.number_invoice')
        ->join('bills', 'bills.student_id', '=', 'students.id')
        ->join('grades', 'grades.id', '=', 'students.grade_id')
        ->where('bills.type', 'Book')
        ->whereBetween('bills.deadline_invoice', [$this->start, $this->end])
        ->orderBy('students.grade_id', 'asc')
        ->orderBy('students.name', 'asc')
        ->get();
        
        $g_id = null;
        $start_g = 2;

        foreach($materialFee as $idx => $bill){

            if(count($materialFee) === $idx+1) {
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
                'type' => 'Material Fee',
                'created_at' => date('Y-m-d', strtotime($bill->created_at)),
                'deadline_invoice' => $bill->deadline_invoice,
                'charge' => $bill->charge? $this->currencyToIdr($bill->charge) : "",
                'amount'=> $bill->installment? $this->currencyToIdr($bill->amount_installment) : $this->currencyToIdr($bill->amount),
                'paid_date' => $bill->paid_date,
                'status' => $bill->paidOf? "Lunas": "Belum lunas",
            ];

            array_push($materialFeeFormated, $obj);
        }

        return (object) [
            'data' => $materialFeeFormated,
            'grade_id' => $grade_id,
            'student_id' => [],
        ];
    }
    

    private function currencyToIdr(int $currency){

        return 'Rp.' . number_format($currency, 0, '', ',');
    }
}
