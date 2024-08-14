<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Bill;
use App\Models\Expenditure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InvoiceSupplier;

class FinancialController extends Controller
{

    public function indexIncome()
    {
        session()->flash('preloader', true);
        session()->flash('page', (object)[
            'page' => 'Financial',
            'child' => 'database income',
        ]);
        
        $bills = Bill::where('paidOf', true)->paginate(25);

        $totalPaid = Bill::where('paidOf', true)->sum('amount');

        return view('components.financial.income-index', [
            'totalpaid' => $totalPaid,
            'bills' => $bills,
        ]);
    }


    public function indexExpenditure()
    {
        session()->flash('preloader', true);
        session()->flash('page', (object)[
            'page' => 'Financial',
            'child' => 'database expense',
        ]);

        $invoiceSuppliers = InvoiceSupplier::paginate(25);
        $totalAmountInvoiceSupplier = InvoiceSupplier::sum('amount'); // Mengambil total pengeluaran dari field amount

        return view('components.financial.expenditure-index', [
            'invoiceSuppliers' => $invoiceSuppliers,
            'totalAmountInvoiceSupplier' => $totalAmountInvoiceSupplier,
        ]);
    }
}
