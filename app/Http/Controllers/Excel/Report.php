<?php

namespace App\Http\Controllers\Excel;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class Report extends Controller
{
    public function index() {

        try {

            session()->flash('page', (object)[
                'page' => 'Report',
                'child' => 'report bills'
             ]);

            return view('components.bill.report.report-page');
            
        } catch (Exception $err) {
            return abort(500);
        }
    }


    public function export(Request $request) {

        try {
            //code...

            $formatedName = time().rand(10000, 99999). '_'. date("dmY") . '_report_bill' . '.xlsx';
            $rules = $request->only(['from_report', 'to_report']);

            $validator = Validator::make($rules, [
                'from_report' => 'required|string|min:7|max:7',
                'to_report' => 'required|string|min:7|max:7',
            ]);

            if($validator->fails()){
                return redirect('/admin/reports')->withErrors($validator->messages())->withInput($rules);
            }
            
            $dateFrom = explode("/", $request->from_report);
            $monthForm = $dateFrom[0];
            $yearForm = $dateFrom[1];
            
            $dateTo = explode("/", $request->to_report);
            $monthTo = $dateTo[0];
            $yearTo = $dateTo[1];
            
            if( !(int)$monthForm || (int)$monthForm < 1 || (int)$monthForm > 12 || (int)$yearForm < 1000 ) {
                return (int)$monthForm;
                return redirect('/admin/reports')->withErrors([
                    'from_report' => 'Invalid format from date',
                ])->withInput($rules);
            } 
            
            if( !(int)$monthTo || (int)$monthTo < 1 || (int)$monthTo > 12 || (int)$yearTo < 1000 ) {
                return redirect('/admin/reports')->withErrors([
                    'to_report' => 'Invalid format to date',
                ])->withInput($rules);
            } 

            return Excel::download(new ReportExport($request->from_report, $request->to_report), $formatedName);

        } catch (Exception $err) {
            return dd($err);
        }
    }
}
