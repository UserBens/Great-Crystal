<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Models\Accountnumber;
use App\Models\BalanceAccount;
use App\Models\Accountcategory;
use App\Http\Controllers\Controller;

class BalanceController extends Controller
{
    public function indexBalance(Request $request)
    {
        session()->flash('preloader', true);
        session()->flash('page', (object)[
            'page' => 'AccountNumber',
            'child' => 'Database Balance',
        ]);

        try {
            $form = (object) [
                'sort' => $request->sort ?? null,
                'order' => $request->order ?? 'desc',
                'search' => $request->search ?? null,
                'date' => $request->date ?? null,
                'type' => $request->type ?? null,
            ];

            $query = BalanceAccount::with('accountnumber');

            // Filter berdasarkan parameter pencarian
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('account_no', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('amount', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('created_at', 'LIKE', '%' . $request->search . '%');
                });
            }

            // Filter berdasarkan tanggal
            if ($request->filled('date')) {
                $searchDate = date('Y-m-d', strtotime($request->date));
                $query->whereDate('created_at', $searchDate);
            }

            // Mengatur urutan berdasarkan parameter yang dipilih
            if ($request->filled('sort') && $request->filled('order')) {
                $query->orderBy($request->sort, $request->order);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $data = $query->paginate(15);

            $categories = Accountcategory::all();

            return view('components.account.balance-index')->with('data', $data)->with('categories', $categories)->with('form', $form);
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function createBalance()
    {
        $accountNumbers = Accountnumber::all();
        return view('components.account.create-balance', compact('accountNumbers'));
    }

    public function storeBalance(Request $request)
    {
        $request->validate([
            'accountnumber_id' => 'required|exists:accountnumbers,id',
            'beginning_balance' => 'required|numeric',
        ]);

        BalanceAccount::create([
            'accountnumber_id' => $request->accountnumber_id,
            'beginning_balance' => $request->beginning_balance,
            'ending_balance' => $request->beginning_balance,
        ]);

        return redirect()->route('balance.index')->with('success', 'Balance account created successfully.');
    }

    public function postMonthlyBalances(Request $request)
    {
        $balance = BalanceAccount::findOrFail($request->balance_id);
        $balance->postMonthly();

        return redirect()->route('balance.index')->with('success', 'Balance posted for the month.');
    }

    public function unpostMonthlyBalances(Request $request)
    {
        $balance = BalanceAccount::findOrFail($request->balance_id);
        $balance->unpostMonthly();

        return redirect()->route('balance.index')->with('success', 'Balance unposted for the month.');
    }
}
