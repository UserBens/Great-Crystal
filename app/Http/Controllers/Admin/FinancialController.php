<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use App\Models\Bill;
use App\Models\Expenditure;
use Illuminate\Http\Request;

class FinancialController extends Controller
{

    public function indexIncome()
    {
        session()->flash('page', (object)[
            'page' => 'Financial',
            'child' => 'database income',
        ]);
        
        // $bills = Bill::where('paidOf', true)->get()->all();
        $bills = Bill::where('paidOf', true)->paginate(10);

        $totalPaid = Bill::where('paidOf', true)->sum('amount');


        

        return view('components.income.index', [
            'totalpaid' => $totalPaid,
            'bills' => $bills,
        ]);


    }

    public function indexExpenditure(Request $request)
    {
        session()->flash('page', (object)[
            'page' => 'Financial',
            'child' => 'database expenditure',
        ]);

        try {
            $form = (object) [
                'sort' => $request->sort ? $request->sort : null,
                'order' => $request->order ? $request->order : null,
                'status' => $request->status ? $request->status : null,
                'search' => $request->search ? $request->search : null,
                'type' => $request->type ? $request->type :  null,
            ];

            $data = [];

            // Mengatur default urutan
            $order = $request->sort ? $request->sort : 'desc';

            // Query data berdasarkan parameter yang diberikan
            if ($request->type && $request->search && $request->order) {
                $data = Expenditure::where('income_id', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('amount_spent', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('spent_at', 'LIKE', '%' . $request->search . '%')
                    ->orderBy($request->order, $order)
                    ->paginate(15);
            } else if ($request->type && $request->search) {
                $data = Expenditure::where('income_id', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('amount_spent', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('spent_at', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('created_at', $order)
                    ->paginate(15);
            } else if ($request->order) {
                $data = Expenditure::orderBy($request->order, $order)
                    ->paginate(15);
            } else {
                $data = Expenditure::orderBy('created_at', $order)
                    ->paginate(15);
            }

            return view('components.expenditure.index')->with('data', $data)->with('form', $form);
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function createExpenditure() {
        return view('components.expenditure.create');
    }
}
