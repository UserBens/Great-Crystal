<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;


class IncomeController extends Controller
{
    public function index()
    {

        session()->flash('page', (object)[
            'page' => 'Income',
            'child' => 'database income'
        ]);

        return view('components.income.index');
    }

    // public function index(Request $request)
    // {
    //     try {

    //         $user = Auth::user();

    //         session()->flash('page', (object)[
    //             'page' => 'Income',
    //             'child' => 'database income'
    //         ]);

    //         $bill = Bill::select('type', DB::raw('count(*) as total'))->groupBy('type')->get();

    //         $grades = Grade::orderBy('id', 'asc')->get();

    //         $form = (object) [
    //             'grade' => $request->grade && $request->grade !== 'all' ? $request->grade : null,
    //             'type' => $request->type && $request->type !== 'all' ? $request->type : null,
    //             'invoice' => $request->invoice && $request->invoice !== 'all' ? $request->invoice : null,
    //             'status' => $request->status && $request->status !== 'all' ? $request->status : null,
    //             'search' => $request->search ? $request->search : null,
    //             'page' => $request->page ? $request->page : null,
    //             'from_bill' => $request->from_bill ? $request->from_bill : null,
    //             'to_bill' => $request->to_bill ? $request->to_bill : null,
    //         ];

    //         $flag_date = true;

    //         if ($form->search || $request->page || $form->from_bill || $form->to_bill || $request->grade && $request->type && $request->invoice && $request->status) {

    //             $data = new Bill();
    //             $data = $data->with(['student' => function ($query) {
    //                 $query->with('grade')->get();
    //             }]);

    //             if ($form->grade) {
    //                 $data = $data->whereHas('student', function ($query) use ($form) {
    //                     $query
    //                         ->where('name', 'LIKE', '%' . $form->search . '%')
    //                         ->where('grade_id', (int)$form->grade);
    //                 });
    //             }

    //             if ($form->from_bill) {


    //                 $explode_f = explode('/', $form->from_bill);
    //                 $date_f = $explode_f[2] . '-' . $explode_f[1] . '-' . $explode_f[0];

    //                 $f_carbon = Carbon::createFromDate($date_f, 'Asia/Jakarta');
    //                 $f_carbon->setTime(0, 0, 0);
    //                 $f_carbon->setTimezone('Asia/Jakarta');
    //                 $f_formated = $f_carbon->format('Y-m-d 00:00:00');
    //             }

    //             if ($form->to_bill) {


    //                 $explode_t = explode('/', $form->to_bill);
    //                 $date_t = $explode_t[2] . '-' . $explode_t[1] . '-' . $explode_t[0];

    //                 $t_carbon = Carbon::createFromDate($date_t, 'Asia/Jakarta');
    //                 $t_carbon->setTime(0, 0, 0);
    //                 $t_carbon->setTimezone('Asia/Jakarta');
    //                 $t_formated = $t_carbon->format('Y-m-d 00:00:00');
    //             }

    //             if ($form->from_bill && $form->to_bill) {
    //                 // $fromDate = 
    //                 $data = $data->whereRaw("created_at BETWEEN '{$f_formated}' AND '{$t_formated}'");
    //                 $form->from_bill = $explode_f[0] . '/' . $explode_f[1] . '/' . $explode_f[2];
    //                 $form->to_bill = $explode_t[0] . '/' . $explode_t[1] . '/' . $explode_t[2];


    //                 $flag_date = $f_carbon->timestamp > $t_carbon->timestamp ? false : true;
    //             } else if ($form->from_bill) {
    //                 $data = $data->whereDate('created_at', '>=', $f_formated);
    //                 $form->from_bill = $explode_f[0] . '/' . $explode_f[1] . '/' . $explode_f[2];
    //             } else if ($form->to_bill) {
    //                 $data = $data->whereDate('created_at', '<=', $t_formated);
    //                 $form->to_bill = $explode_t[0] . '/' . $explode_t[1] . '/' . $explode_t[2];
    //             }




    //             if ($form->type) {
    //                 if (strtolower($form->type) != 'others') {

    //                     $data = $data->where('type', $form->type);
    //                 } else {
    //                     $data = $data->whereNotIn('type', ['SPP', "Capital Fee", "Paket", "Uniform", "Book"]);
    //                 }
    //             }

    //             if ($form->status) {
    //                 $statusPaid = $form->status == 'true' ? true : false;

    //                 $data = $data->where('paidOf', $statusPaid);
    //             }



    //             if ($form->invoice) {

    //                 if (is_numeric($form->invoice)) {
    //                     $data = $data
    //                         ->where('deadline_invoice', '<=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays((int)$form->invoice)->format('y-m-d'))
    //                         ->where('deadline_invoice', '>=', Carbon::now()->setTimezone('Asia/Jakarta')->format('y-m-d'));
    //                 } else {


    //                     if ($form->invoice == 'tommorow') {
    //                         $data = $data->where('deadline_invoice', '=', Carbon::now()->setTimezone('Asia/Jakarta')->addDays(1)->format('y-m-d'));
    //                     } else {

    //                         $operator = $form->invoice == 'today' ? '=' : '<';

    //                         $data = $data->where('deadline_invoice', $operator, Carbon::now()->setTimezone('Asia/Jakarta')->format('y-m-d'));
    //                     }
    //                 }
    //             }


    //             if ($user->role == 'admin') {
    //                 $data = $data->where('created_by', 'admin');
    //             }

    //             if ($form->search) {
    //                 $data = $data->whereHas('student', function ($query) use ($form) {
    //                     $query->where('name', 'LIKE', '%' . $form->search . '%')->orWhere('number_invoice', 'LIKE', '%' . $form->search . '%')->orderBy('id');
    //                 });
    //             }


    //             $data = $data->orderBy('id', 'desc')->paginate(15);
    //         } else {
    //             if ($user->role == 'admin') {
    //                 $data = Bill::with(['student' => function ($query) {
    //                     $query->with('grade')->get();
    //                 }])
    //                     ->where('created_by', 'admin')
    //                     ->orderBy('updated_at', 'desc')
    //                     ->paginate(15);
    //             } else {
    //                 $data = Bill::with(['student' => function ($query) {
    //                     $query->with('grade')->get();
    //                 }])
    //                     ->orderBy('updated_at', 'desc')
    //                     ->paginate(15);
    //             }
    //         }


    //         // return $form;
    //         return view('components.bill.data-bill')
    //             ->with('data', $data)
    //             ->with('grade', $grades)
    //             ->with('form', $form)
    //             ->with('bill', $bill)
    //             ->with('flag_date', $flag_date);
    //     } catch (Exception $err) {
    //         //throw $th;
    //         return dd($err);
    //     }
    // }
}
