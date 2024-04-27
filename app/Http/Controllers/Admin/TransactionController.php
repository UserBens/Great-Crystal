<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Cash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function indexCash(Request $request)
    {
        session()->flash('page', (object)[
            'page' => 'Transaction',
            'child' => 'database Cash',
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
            if ($request->has('search')) {
                $data = Cash::where('type', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('amount_spent', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('spent_at', 'LIKE', '%' . $request->search . '%')
                    ->orderBy($request->order ?? 'created_at', $order)
                    ->paginate(10);
            } else {
                $data = Cash::orderBy('created_at', $order)->paginate(10);
            }

            return view('components.cash.index')->with('data', $data)->with('form', $form);
        } catch (Exception $err) {
            return dd($err);
        }
    }


    public function createAccount()
    {
        return view('components.cash.create-account');
    }

    public function storeAccount(Request $request)
    {
        // Validasi input
        $request->validate([
            'type' => 'required',
            'spent_at' => 'required|date_format:d/m/Y',
            'amount_spent' => 'required|numeric', // Ubah aturan validasi menjadi 'numeric'
            'description' => 'required',
        ]);

        // Konversi format tanggal menggunakan Carbon
        $spent_at = Carbon::createFromFormat('d/m/Y', $request->spent_at)->format('Y-m-d');

        // Simpan data pengeluaran ke dalam database
        Cash::create([
            'spent_at' => $spent_at,
            'amount_spent' => $request->amount_spent,
            'description' => $request->description,
            'type' => $request->type,
        ]);

        // Redirect ke halaman indeks pengeluaran dengan pesan sukses
        return redirect()->route('expenditure.index')->with('success', 'Expenditure created successfully!');
    }

    public function createTransaction()
    {
        return view('components.cash.create-transaction');
    }

    public function indexBank()
    {
        session()->flash('page', (object)[
            'page' => 'Transaction',
            'child' => 'database Bank',
        ]);

        return view('components.bank.index');
    }


    public function indexJournal(Request $request)
    {
        session()->flash('page', (object)[
            'page' => 'Transaction',
            'child' => 'database Journal',
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
            if ($request->has('search')) {
                $data = Cash::where('type', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('amount_spent', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('spent_at', 'LIKE', '%' . $request->search . '%')
                    ->orderBy($request->order ?? 'created_at', $order)
                    ->paginate(10);
            } else {
                $data = Cash::orderBy('created_at', $order)->paginate(10);
            }

            return view('components.journal.index')->with('data', $data)->with('form', $form);
        } catch (Exception $err) {
            return dd($err);
        }
    }
}
