<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    protected $start, $end;

    public function __construct(string $start, string $end) {
        $this->start = $start;
        $this->end = $end;
    }

    public function index(): object {

        $packageFormated = [];
        $grade_id = [];
        $student_id = [];
        $installment_id = [];

        $capFee = DB::table('students')->select('bills.id', 'grades.name as grade_name', 'grades.class as grade_class','students.name','bills.type','bills.installment','bills.created_at'
        ,'bills.deadline_invoice','bills.amount','bills.dp','bills.charge', 'bills.amount','bills.paid_date','bills.paidOf', 
        'bills.amount_installment', 'students.id as student_id', 'grades.id as grade_id', 'bills.number_invoice')
        ->join('bills', 'bills.student_id', '=', 'students.id')
        ->join('grades', 'grades.id', '=', 'students.grade_id')
        ->where('bills.type', 'Paket')
        ->whereBetween('bills.deadline_invoice', [$this->start, $this->end])
        ->orderBy('students.grade_id', 'asc')
        ->orderBy('students.name', 'asc')
        ->orderBy('bills.id', 'asc')
        ->get();

        $s_id = null;
        $start_col = 2;
        $end_student = 0;
        
        $g_id = null;
        $start_g = 2;
        $end_grade = 0;

        $start_installment = null;
        $end_installment = 0;
        $unique_installment = [];

        foreach($capFee as $idx => $bill){
    

            $obj = (object) [
                'no_invoice' => '#' .$bill->number_invoice,
                'grades' => $bill->grade_name . ' ' . $bill->grade_class,
                'name' => $bill->name,
                'type' => $bill->type,
                'installment' => $bill->installment? $bill->installment . ' Installments/Month' : "Cash",
                'created_at' => date('Y-m-d', strtotime($bill->created_at)),
                'deadline_invoice' => $bill->deadline_invoice,
                'total' => $this->currencyToIdr($bill->amount),
                'amount'=> $bill->installment? $this->currencyToIdr($bill->amount_installment) : $this->currencyToIdr($bill->amount),
                'paid_date' => $bill->paid_date,
                'status' => $bill->paidOf? "Lunas": "Belum lunas",
            ];

            
            if(!in_array($bill->id, $unique_installment)) {

                $start_date = $this->start;
                $end_date = $this->end;
                
                $installment = Bill::with(['bill_installments' => function ($query) use($start_date, $end_date) {
                    $query->whereDate('deadline_invoice', '>=', $start_date)->whereDate('deadline_invoice', '<=', $end_date)->get();
                 }])->where('id', $bill->id)->first();

                if(sizeof($installment->bill_installments) > 0) {

                    foreach($installment->bill_installments as $val) {

                        $start_installment = $idx+2;

                        if (count($capFee) === $end_student+1) {

                            $bill->student_id == $s_id ?
                                array_push($student_id, [$start_col, $end_student+2]) :
                               ( 
                                array_push($student_id, [$start_col, $end_student+1]) &&
                                array_push($student_id, [$end_student+2, $end_student+2])
                               );
            
                        } else if($s_id && $bill->student_id != $s_id) {
                            array_push($student_id, [$start_col, $end_student+1]);
                            $start_col=$end_student+2;
                        }
            
                        if(count($capFee) === $end_grade+1) {
                            $bill->grade_id == $g_id ?
                                array_push($grade_id, [$start_g, $end_grade+2]) :
                               ( 
                                array_push($grade_id, [$start_g, $end_grade+1]) &&
                                array_push($grade_id, [$end_grade+2, $end_grade+2])
                               );
            
                        } else if($g_id && $bill->grade_id != $g_id) {
                            array_push($grade_id, [$start_g, $end_grade+1]);
                            $start_g=$end_grade+2;
                        }
            
                        $g_id = (int)$bill->grade_id;
                        $s_id = (int)$bill->student_id;
        
                        
                        $obj = (object) [
                            'no_invoice' => '#' . $val->number_invoice,
                            'grades' => $bill->grade_name . ' ' . $bill->grade_class,
                            'name' => $bill->name,
                            'type' => $val->type,
                            'installment' => $val->installment? $val->installment . ' Installments/Month' : "Cash",
                            'created_at' => date('Y-m-d', strtotime($val->created_at)),
                            'deadline_invoice' => $val->deadline_invoice,
                            'total' => $this->currencyToIdr($val->amount),
                            'amount'=> $val->installment? $this->currencyToIdr($val->amount_installment) : $this->currencyToIdr($val->amount),
                            'paid_date' => $val->paid_date,
                            'status' => $val->paidOf? "Lunas": "Belum lunas",
                        ];
                        
                        
                        array_push($packageFormated, $obj);
                        array_push($unique_installment, $val->id);
                        
                        $end_student++;
                        $end_grade++;
                        $end_installment++;
                    }


                    array_push($installment_id, [$start_installment, $end_installment+1]);


                } else {

                    if (count($capFee) === $end_student+1) {

                        $bill->student_id == $s_id ?
                            array_push($student_id, [$start_col, $end_student+2]) :
                           ( 
                            array_push($student_id, [$start_col, $end_student+1]) &&
                            array_push($student_id, [$end_student+2, $end_student+2])
                           );
        
                    } else if($s_id && $bill->student_id != $s_id) {
                        array_push($student_id, [$start_col, $end_student+1]);
                        $start_col=$end_student+2;
                    }
        
                    if(count($capFee) === $end_grade+1) {
                        $bill->grade_id == $g_id ?
                            array_push($grade_id, [$start_g, $end_grade+2]) :
                           ( 
                            array_push($grade_id, [$start_g, $end_grade+1]) &&
                            array_push($grade_id, [$end_grade+2, $end_grade+2])
                           );
        
                    } else if($g_id && $bill->grade_id != $g_id) {
                        array_push($grade_id, [$start_g, $end_grade+1]);
                        $start_g=$end_grade+2;
                    }
        
                    $g_id = (int)$bill->grade_id;
                    $s_id = (int)$bill->student_id;

                    array_push($packageFormated, $obj);
                    array_push($unique_installment, $bill->id);

                    $end_student++;
                    $end_grade++;
                    $end_installment++;
                
                    
                
                }

            }
            
            
            
        }

        
        info('capFee - ' . json_encode(sizeof($capFee)));
        info('grade_id - ' . json_encode($grade_id));
        info('student_id - ' . json_encode($student_id));
        info('installment_id - ' . json_encode($installment_id));

        return (object) [
            'data' => $packageFormated,
            'grade_id' => $grade_id,
            'student_id' => $student_id,
            'installment_id' => $installment_id,
        ];
    }
    

    private function currencyToIdr(int $currency){

        return 'Rp.' . number_format($currency, 0, '', ',');
    }
}
