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

        // try {
        //     $form = (object) [
        //         'sort' => $request->sort ? $request->sort : null,
        //         'order' => $request->order ? $request->order : null,
        //         'status' => $request->status ? $request->status : null,
        //         'search' => $request->search ? $request->search : null,
        //         'type' => $request->type ? $request->type :  null,
        //     ];

        //     $data = [];

        //     // Mengatur default urutan
        //     $order = $request->sort ? $request->sort : 'desc';

        //     // Query data berdasarkan parameter yang diberikan
        //     if ($request->has('search')) {
        //         $data = Transaction_transfer::where('transfer', 'LIKE', '%' . $request->search . '%')
        //             ->orWhere('deposit', 'LIKE', '%' . $request->search . '%')
        //             ->orWhere('amount', 'LIKE', '%' . $request->search . '%')
        //             ->orWhere('date', 'LIKE', '%' . $request->search . '%')
        //             ->orderBy($request->order ?? 'created_at', $order)
        //             ->paginate(10);
        //     } else {
        //         $data = Transaction_transfer::orderBy('created_at', $order)->paginate(10);
        //     }

        //     return view('components.cash&bank.index')->with('data', $data)->with('form', $form);
        // } catch (Exception $err) {
        //     return dd($err);
        // }

        $form = (object) [
            'sort' => $request->sort ?? null,
            'order' => $request->order ?? null,
            'status' => $request->status ?? null,
            'search' => $request->search ?? null,
            'type' => $request->type ?? null,
        ];

        try {
            $allData = Cash::leftJoin('accountnumbers as transfer_account', 'cash.transfer', '=', 'transfer_account.id')
                ->leftJoin('accountnumbers as deposit_account', 'cash.deposit', '=', 'deposit_account.id')
                ->where(function ($query) use ($request) {
                    if ($request->has('search')) {
                        $query->where('transfer_account.account_no', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('deposit_account.account_no', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('cash.amount', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('cash.date', 'LIKE', '%' . $request->search . '%');
                    }
                })
                ->select([
                    'transfer_account.account_no as transfer_account_no',
                    'deposit_account.account_no as deposit_account_no',
                    'cash.amount',
                    'cash.date',
                    'cash.created_at'
                ])
                ->orderBy($form->order ?? 'date', $form->sort ?? 'desc')
                ->paginate(10);

            return view('components.journal.index', [
                'allData' => $allData,
                'form' => $form,
            ]);
        } catch (Exception $err) {
            return dd($err);
        }
    }

    // public function deleteSelectedItems(Request $request)
    // {
    //     // Ambil jenis transaksi dari form
    //     $transactionType = $request->input('transaction_type');

    //     // Sesuaikan model yang digunakan berdasarkan jenis transaksi
    //     switch ($transactionType) {
    //         case 'transaction_transfer':
    //             $model = Transaction_transfer::whereIn('id', $request->input('selectedItems'));
    //             break;
    //         case 'transaction_send':
    //             $model = Transaction_send::whereIn('id', $request->input('selectedItems'));
    //             break;
    //         case 'transaction_receive':
    //             $model = Transaction_receive::whereIn('id', $request->input('selectedItems'));
    //             break;
    //         default:
    //             // Jenis transaksi tidak valid
    //             return redirect()->back()->with('error', 'Invalid transaction type.');
    //     }

    //     // Lakukan penghapusan data dari tabel yang sesuai
    //     $model->delete();

    //     // Redirect atau tampilkan pesan sukses
    //     return redirect()->back()->with('success', 'Selected items deleted successfully.');
    // }


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

    public function editAccount($id)
    {
        $accountNumbers = Accountnumber::findOrFail($id);

        return view('components.account.edit-account', [
            'accountNumbers' => $accountNumbers,
        ]);
    }

    public function updateAccount(Request $request, $id)
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

        $accountNumbers = Accountnumber::findOrFail($id);

        $accountNumbers->update([
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

    public function destroyAccount($id)
    {
        try {
            // Cari data transaksi transfer berdasarkan ID
            $accountNumbers = Accountnumber::findOrFail($id);

            // Hapus data transaksi transfer
            $accountNumbers->delete();

            return redirect()->back()->with('success', 'Account Number Deleted Successfully!');
        } catch (Exception $err) {
            return dd($err);
        }
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
            'accountnumber_id' => $request->transfer, // Sertakan nilai accountnumber_id yang diterima dari form
            'accountnumber_id' => $request->deposit, // Sertakan nilai accountnumber_id yang diterima dari form
            'transfer' => $request->transfer,
            'deposit' => $request->deposit,
            'amount' => $request->amount,
            'date' => $date,
            'description' => $request->description,
        ]);

        return redirect()->route('cash.index')->with('success', 'Transaction Transfer Created Successfully!');
    }

    public function deleteTransactionTransfer($id)
    {
        try {
            // Cari data transaksi transfer berdasarkan ID
            $transactionTransfer = Transaction_transfer::findOrFail($id);

            // Hapus data transaksi transfer
            $transactionTransfer->delete();

            return redirect()->back()->with('success', 'Transaction Transfer Deleted Successfully!');
        } catch (Exception $err) {
            return dd($err);
        }
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
            'accountnumber_id' => $request->transfer, // Sertakan nilai accountnumber_id yang diterima dari form
            'transfer' => $request->transfer,
            'deposit' => $request->deposit,
            'amount' => $request->amount,
            'date' => $date,
            'description' => $request->description,
        ]);

        return redirect()->route('cash.index')->with('success', 'Transaction Send Created Successfully!');
    }

    public function deleteTransactionSend(Request $request)
    {
        try {
            // Ambil ID dari request
            $transactionId = $request->input('transaction_id');

            // Cari data transaksi send berdasarkan ID
            $transactionSend = Transaction_send::findOrFail($transactionId);

            // Hapus data transaksi send
            $transactionSend->delete();

            return redirect()->back()->with('success', 'Transaction Send Deleted Successfully!');
        } catch (Exception $err) {
            return dd($err);
        }
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
            'accountnumber_id' => $request->transfer, // Sertakan nilai accountnumber_id yang diterima dari form
            'transfer' => $request->transfer,
            'deposit' => $request->deposit,
            'amount' => $request->amount,
            'date' => $date,
            'description' => $request->description,
        ]);

        return redirect()->route('cash.index')->with('success', 'Transaction Send Created Successfully!');
    }

    public function deleteTransactionReceive(Request $request)
    {
        try {
            // Ambil ID dari request
            $transactionId = $request->input('transaction_id');

            // Cari data transaksi receive berdasarkan ID
            $transactionReceive = Transaction_receive::findOrFail($transactionId);

            // Hapus data transaksi receive
            $transactionReceive->delete();

            return redirect()->back()->with('success', 'Transaction Receive Deleted Successfully!');
        } catch (Exception $err) {
            return dd($err);
        }
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
        session()->flash('page', (object) [
            'page' => 'Transaction',
            'child' => 'database Journal',
        ]);

        $form = (object) [
            'sort' => $request->sort ?? null,
            'order' => $request->order ?? null,
            'status' => $request->status ?? null,
            'search' => $request->search ?? null,
            'type' => $request->type ?? null,
        ];


        try {
            $transferdata = Transaction_transfer::select(
                'transaction_transfers.transfer',
                'transaction_transfers.deposit',
                'transaction_transfers.amount',
                'transaction_transfers.date',
                'transaction_transfers.description'
            )
                ->leftJoin('accountnumbers', function ($join) {
                    $join->on('accountnumbers.id', '=', 'transaction_transfers.accountnumber_id')
                        ->whereNotNull('transaction_transfers.accountnumber_id');
                })
                ->where(function ($query) use ($request) {
                    if ($request->has('search')) {
                        $query->where('transaction_transfers.transfer', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_transfers.deposit', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_transfers.amount', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_transfers.description', 'LIKE', '%' . $request->search . '%');
                    }
                })
                ->selectRaw("'transaction_transfers' as type");

            $senddata = Transaction_send::select(
                'transaction_sends.transfer',
                'transaction_sends.deposit',
                'transaction_sends.amount',
                'transaction_sends.date',
                'transaction_sends.description'
            )
                ->leftJoin('accountnumbers', function ($join) {
                    $join->on('accountnumbers.id', '=', 'transaction_sends.accountnumber_id')
                        ->whereNotNull('transaction_sends.accountnumber_id');
                })
                ->where(function ($query) use ($request) {
                    if ($request->has('search')) {
                        $query->where('transaction_sends.transfer', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_sends.deposit', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_sends.amount', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_sends.description', 'LIKE', '%' . $request->search . '%');
                    }
                })
                ->selectRaw("'transaction_send' as type");

            $receivedata = Transaction_receive::select(
                'transaction_receives.transfer',
                'transaction_receives.deposit',
                'transaction_receives.amount',
                'transaction_receives.date',
                'transaction_receives.description'
            )
                ->leftJoin('accountnumbers', function ($join) {
                    $join->on('accountnumbers.id', '=', 'transaction_receives.accountnumber_id')
                        ->whereNotNull('transaction_receives.accountnumber_id');
                })
                ->where(function ($query) use ($request) {
                    if ($request->has('search')) {
                        $query->where('transaction_receives.transfer', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_receives.deposit', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_receives.amount', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_receives.description', 'LIKE', '%' . $request->search . '%');
                    }
                })
                ->selectRaw("'transaction_receive' as type");

            $allData = $transferdata->union($senddata)->union($receivedata)
                ->orderBy($form->order ?? 'date', $form->sort ?? 'desc')
                ->paginate(2);

            return view('components.journal.index', [
                'allData' => $allData,
                'form' => $form,
            ]);
        } catch (Exception $err) {
            return dd($err);
        }
    }
}
