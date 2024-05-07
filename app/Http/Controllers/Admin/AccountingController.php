<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Cash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Accountnumber;
use App\Models\Transaction_receive;
use App\Models\Transaction_send;
use App\Models\Transaction_transfer;

class AccountingController extends Controller
{
    public function indexCash(Request $request)
    {
        session()->flash('page', (object)[
            'page' => 'Transaction',
            'child' => 'database Cash & Bank',
        ]);

        try {
            $form = (object) [
                'sort' => $request->sort ? $request->sort : null,
                'order' => $request->order ? $request->order : null,
                'status' => $request->status ? $request->status : null,
                'search' => $request->search ? $request->search : null,
                'type' => $request->type ? $request->type :  null,
            ];

            // Query data dari ketiga tabel
            $transferdata = Transaction_transfer::select('id', 'transfer', 'deposit', 'amount', 'date', 'description')
                ->where(function ($query) use ($request) {
                    if ($request->has('search')) {
                        $query->where('transfer', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('deposit', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('amount', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('description', 'LIKE', '%' . $request->search . '%');
                    }
                })
                ->selectRaw("'transaction_transfer' as type"); // Menambahkan kolom 'type' sebagai penanda sumber data

            $senddata = Transaction_send::select('id', 'transfer', 'deposit', 'amount', 'date', 'description')
                ->where(function ($query) use ($request) {
                    if ($request->has('search')) {
                        $query->where('transfer', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('deposit', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('amount', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('description', 'LIKE', '%' . $request->search . '%');
                    }
                })
                ->selectRaw("'transaction_send' as type"); // Menambahkan kolom 'type' sebagai penanda sumber data

            $receivedata = Transaction_receive::select('id', 'transfer', 'deposit', 'amount', 'date', 'description')
                ->where(function ($query) use ($request) {
                    if ($request->has('search')) {
                        $query->where('transfer', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('deposit', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('amount', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('description', 'LIKE', '%' . $request->search . '%');
                    }
                })
                ->selectRaw("'transaction_receive' as type"); // Menambahkan kolom 'type' sebagai penanda sumber data

            // Gabungkan hasil query dari ketiga tabel
            $allData = $transferdata->union($senddata)->union($receivedata)
                ->orderBy($form->order ?? 'date', $form->sort ?? 'desc')
                ->paginate(10);

            // Return view dengan data yang diperlukan
            return view('components.cash&bank.index', [
                'allData' => $allData,
                'form' => $form,
            ]);
        } catch (Exception $err) {
            return dd($err);
        }
    }



    public function indexAccount(Request $request)
    {
        session()->flash('page', (object)[
            'page' => 'Transaction',
            'child' => 'database Account Number',
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
                $data = Accountnumber::where('type', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('amount', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('spent_at', 'LIKE', '%' . $request->search . '%')
                    ->orderBy($request->order ?? 'created_at', $order)
                    ->paginate(10);
            } else {
                $data = Accountnumber::orderBy('created_at', $order)->paginate(10);
            }

            return view('components.account.index')->with('data', $data)->with('form', $form);
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function createAccount()
    {
        return view('components.account.create-account');
    }

    public function storeAccount(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'account_no' => 'required',
            'type' => 'required',
            'bank_name' => 'required',
            'amount' => 'required|numeric',
            'description' => 'required',
        ]);

        // Konversi format tanggal menggunakan Carbon
        // $spent_at = Carbon::createFromFormat('d/m/Y', $request->spent_at)->format('Y-m-d');

        // Simpan data pengeluaran ke dalam database
        Accountnumber::create([
            'name' => $request->name,
            'account_no' => $request->account_no,
            'type' => $request->type,
            'bank_name' => $request->bank_name,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        // Redirect ke halaman indeks pengeluaran dengan pesan sukses
        return redirect()->route('account.index')->with('success', 'Account created successfully!');
    }

    // milik transfer transaction
    public function createTransactionTransfer()
    {
        $accountNumbers = AccountNumber::all(); // Ambil semua data dari tabel accountnumbers

        return view('components.cash&bank.create-transaction-transfer', [
            'accountNumbers' => $accountNumbers,
        ]);
    }

    public function storeTransactionTransfer(Request $request)
    {
        $request->validate([
            // 'accountnumber_id' => 'required', // Pastikan Anda memvalidasi accountnumber_id
            'transfer' => 'required',
            'deposit' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date_format:d/m/Y',
            'description' => 'required',
        ]);

        $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

        Transaction_transfer::create([
            // 'accountnumber_id' => $request->accountnumber_id, // Sertakan nilai accountnumber_id yang diterima dari form
            'transfer' => $request->transfer,
            'deposit' => $request->deposit,
            'amount' => $request->amount,
            'date' => $date,
            'description' => $request->description,
        ]);

        return redirect()->route('cash.index')->with('success', 'Transaction Transfer Created Successfully!');
    }


    public function createTransactionSend()
    {
        $accountNumbers = AccountNumber::all(); // Ambil semua data dari tabel accountnumbers
        return view('components.cash&bank.create-transaction-send', [
            'accountNumbers' => $accountNumbers,
        ]);
    }

    // milik transaction send
    public function storeTransactionSend(Request $request)
    {
        $request->validate([
            // 'accountnumber_id' => 'required', // Pastikan Anda memvalidasi accountnumber_id
            'transfer' => 'required',
            'deposit' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date_format:d/m/Y',
            'description' => 'required',
        ]);

        $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

        Transaction_send::create([
            // 'accountnumber_id' => $request->accountnumber_id, // Sertakan nilai accountnumber_id yang diterima dari form
            'transfer' => $request->transfer,
            'deposit' => $request->deposit,
            'amount' => $request->amount,
            'date' => $date,
            'description' => $request->description,
        ]);

        return redirect()->route('cash.index')->with('success', 'Transaction Send Created Successfully!');
    }

    // milik transaction Receive
    public function createTransactionReceive()
    {
        $accountNumbers = AccountNumber::all(); // Ambil semua data dari tabel accountnumbers
        return view('components.cash&bank.create-transaction-receive', [
            'accountNumbers' => $accountNumbers,
        ]);
    }

    public function storeTransactionReceive(Request $request)
    {
        $request->validate([
            // 'accountnumber_id' => 'required', // Pastikan Anda memvalidasi accountnumber_id
            'transfer' => 'required',
            'deposit' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date_format:d/m/Y',
            'description' => 'required',
        ]);

        $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

        Transaction_receive::create([
            // 'accountnumber_id' => $request->accountnumber_id, // Sertakan nilai accountnumber_id yang diterima dari form
            'transfer' => $request->transfer,
            'deposit' => $request->deposit,
            'amount' => $request->amount,
            'date' => $date,
            'description' => $request->description,
        ]);

        return redirect()->route('cash.index')->with('success', 'Transaction Send Created Successfully!');
    }

    // public function indexBank()
    // {
    //     session()->flash('page', (object)[
    //         'page' => 'Transaction',
    //         'child' => 'database Bank',
    //     ]);

    //     return view('components.cash&bank.index');
    // }


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
                $data = Transaction_transfer::where('type', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('amount_spent', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('spent_at', 'LIKE', '%' . $request->search . '%')
                    ->orderBy($request->order ?? 'created_at', $order)
                    ->paginate(10);
            } else {
                $data = Transaction_transfer::orderBy('created_at', $order)->paginate(10);
            }

            return view('components.journal.index')->with('data', $data)->with('form', $form);
        } catch (Exception $err) {
            return dd($err);
        }
    }
}
