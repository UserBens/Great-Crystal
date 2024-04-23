<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Http\Controllers\Excel\CapFeeExcelController;
use App\Http\Controllers\Excel\MaterialFeeController;
use App\Http\Controllers\Excel\MonthFeeController;
use App\Http\Controllers\Excel\PackageController;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithProperties;

class ReportExport implements WithMultipleSheets, WithProperties
{

    protected $from, $to;
    
    public function __construct(string $from, string $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function sheets(): array
    {
        $sheets = [];

        $dateFrom = explode('/', $this->from);
        $dateTo = explode('/', $this->to);
        $getMonthFrom = (int)$dateFrom[0];
        $getYearFrom = (int)$dateFrom[1];
        $getMonthTo = (int)$dateTo[0];
        $getYearTo = (int)$dateTo[1];

        // yyyy-mm-dd from date
        $date_start = $this->startDateFormat($getMonthFrom, $getYearFrom);
        // yyyy-mm-dd to date
        $date_end = $this->endDateFormat($getMonthTo, $getYearTo);


        $capFee = new CapFeeExcelController($date_start, $date_end);
        $capFee = $capFee->index();
        $matFee = new MaterialFeeController($date_start, $date_end);
        $matFee = $matFee->index();
        $package = new PackageController($date_start, $date_end);
        $package = $package->index();

        array_push($sheets, new InvoicePerMonthSheet(array_values($capFee->data), $getYearFrom, "Capital Fee", $capFee->student_id, $capFee->grade_id, $capFee->installment_id));
        array_push($sheets, new InvoicePerMonthSheet(array_values($matFee->data), $getYearFrom, "Material Fee", $matFee->student_id, $matFee->grade_id));
        array_push($sheets, new InvoicePerMonthSheet(array_values($package->data), $getYearFrom, "Package", $package->student_id, $package->grade_id, $package->installment_id));

        for($year=$getYearFrom; $year<=$getYearTo; $year++){
            
            for ($month = $getMonthFrom; ($year == $getYearTo? $month<=$getMonthTo : $month <= 12); $month++) {
                    
                $monthFee = new MonthFeeController($date_start, $date_end);
                $indexMonth = $monthFee->index($month);
                $data = $indexMonth->data;
                $map_grade = $indexMonth->grade_id;

                array_push($sheets, new InvoicePerMonthSheet(array_values($data), $year, $month, [], $map_grade));

            }

            $getMonthFrom = 1;
        }
        
        return $sheets;
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Achmad Sofyan',
            'lastModifiedBy' => 'System',
            'title'          => 'Invoices Export',
            'description'    => 'Invoices Export',
            'subject'        => 'Invoices',
            'keywords'       => 'invoices,export,spreadsheet',
            'category'       => 'Invoices',
            'manager'        => 'Donny Prasetya',
            'company'        => 'Great crystal',
        ];
    }

    public function startDateFormat(int $month, int $year) {
            
        $dateFormated = Carbon::create($year, $month, 1);
        info('start ' . $dateFormated);
        return $dateFormated->startOfMonth()->format('Y-m-d');
    } 
    
    public function endDateFormat(int $month, int $year) {
        $dateFormated = Carbon::create($year, $month, 1);
        return $dateFormated->endOfMonth()->format('Y-m-d');
    }
}
