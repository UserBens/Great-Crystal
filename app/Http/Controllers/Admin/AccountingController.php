<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Cash;
use Illuminate\Http\Request;
use App\Models\Accountnumber;
use App\Models\Accountcategory;
use App\Models\Transaction_send;
use App\Models\Transaction_receive;
use App\Http\Controllers\Controller;
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
            // Inisialisasi objek form dengan nilai default
            $form = (object) [
                'search' => $request->search ?? null,
                'type' => $request->type ?? null,
                'sort' => $request->sort ?? null,
                'order' => $request->order ?? null,
                'status' => $request->status ?? null,
            ];

            // Query data berdasarkan parameter pencarian yang diberikan
            $query = Transaction_transfer::with(['transferAccount', 'depositAccount']);

            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereHas('transferAccount', function ($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', $searchTerm)
                            ->orWhere('account_no', 'LIKE', $searchTerm);
                    })->orWhereHas('depositAccount', function ($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', $searchTerm)
                            ->orWhere('account_no', 'LIKE', $searchTerm);
                    })->orWhere('amount', 'LIKE', $searchTerm)
                        ->orWhere('date', 'LIKE', $searchTerm);
                });
            }

            // Mengatur urutan berdasarkan parameter yang dipilih
            if ($request->filled('sort') && $request->filled('order')) {
                if ($request->sort === 'date') {
                    $query->orderBy('date', $request->order);
                } else {
                    $query->orderBy($request->sort, $request->order);
                }
            }

            // Filter data berdasarkan tanggal
            if ($request->filled('date')) {
                $searchDate = date('Y-m-d', strtotime($request->date));
                $query->whereDate('date', $searchDate);
            }

            // Memuat data dengan pagination
            $data = $query->paginate(10);

            // Menampilkan view dengan data dan form
            return view('components.cash&bank.index', compact('data', 'form'));
        } catch (Exception $err) {
            // Menampilkan pesan error jika terjadi kesalahan
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
                $data = Accountnumber::where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('account_no', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('amount', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $request->search . '%')
                    ->orderBy($request->order ?? 'created_at', $order)
                    ->paginate(10);
            } else {
                $data = Accountnumber::orderBy('created_at', $order)->paginate(10);
            }

            $categories = Accountcategory::all();


            return view('components.account.index')->with('data', $data)->with('categories', $categories)->with('form', $form);
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function createAccount()
    {
        $categories = Accountcategory::all();

        return view('components.account.create-account', [
            'categories' => $categories,
        ]);
    }

    public function storeAccount(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'account_no' => 'required',
            'type' => 'required',
            'account_category_id' => 'required',
            'bank_name' => 'required',
            'amount' => 'required|numeric',
            'description' => 'required',
        ]);

        Accountnumber::create([
            'name' => $request->name,
            'account_no' => $request->account_no,
            'type' => $request->type,
            'account_category_id' => $request->account_category_id,
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
        $categories = Accountcategory::all();

        return view('components.account.edit-account', [
            'accountNumbers' => $accountNumbers,
            'categories' => $categories,
        ]);
    }

    public function updateAccount(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'account_no' => 'required',
            'type' => 'required',
            'account_category_id' => 'required',
            'bank_name' => 'required',
            'amount' => 'required|numeric',
            'description' => 'required',
        ]);

        $accountNumbers = Accountnumber::findOrFail($id);

        $accountNumbers->update([
            'name' => $request->name,
            'account_no' => $request->account_no,
            'type' => $request->type,
            'account_category_id' => $request->account_category_id,
            'bank_name' => $request->bank_name,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        // Redirect ke halaman indeks pengeluaran dengan pesan sukses
        return redirect()->route('account.index')->with('success', 'Account Updated successfully!');
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
        try {
            $request->validate([
                // 'accountnumber_id' => 'required', // Pastikan Anda memvalidasi accountnumber_id
                'transfer_account_id' => 'required',
                'deposit_account_id' => 'required',
                'amount' => 'required|numeric',
                'date' => 'required|date_format:d/m/Y',
                'description' => 'required',
                'no_transaction' => 'required',
            ]);

            $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

            Transaction_transfer::create([
                'transfer_account_id' => $request->transfer_account_id,
                'deposit_account_id' => $request->deposit_account_id,
                'amount' => $request->amount,
                'date' => $date,
                'description' => $request->description,
                'no_transaction' => $request->no_transaction,
            ]);

            return redirect()->route('cash.index')->with('success', 'Transaction Transfer Created Successfully!');
        } catch (Exception $err) {
            // Tangani kesalahan di sini
            return dd($err);
        }
    }

    public function editTransactionTransfer($id)
    {
        $transaction = Transaction_transfer::findOrFail($id);
        $accountNumbers = Accountnumber::all(); // Ambil semua data dari tabel accountnumbers


        return view('components.cash&bank.edit-transaction-transfer', [
            'transaction' => $transaction,
            'accountNumbers' => $accountNumbers,
        ]);
    }

    public function updateTransactionTransfer(Request $request, $id)
    {
        try {
            $request->validate([
                // 'accountnumber_id' => 'required', // Pastikan Anda memvalidasi accountnumber_id
                // 'transfer' => 'required',
                // 'deposit' => 'required',
                'transfer_account_id' => 'required',
                'deposit_account_id' => 'required',
                'amount' => 'required|numeric',
                'date' => 'required|date_format:d/m/Y',
                'description' => 'required',
            ]);

            $transaction_transfer = Transaction_transfer::findOrFail($id);

            $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

            $transaction_transfer::update([
                'transfer_account_id' => $request->transfer_account_id,
                'deposit_account_id' => $request->deposit_account_id,
                // 'transfer' => $request->transfer_account_id, // Apakah ini benar-benar diperlukan?
                // 'deposit' => $request->deposit_account_id, // Apakah ini benar-benar diperlukan?
                'amount' => $request->amount,
                'date' => $date,
                'description' => $request->description,
            ]);

            return redirect()->route('cash.index')->with('success', 'Transaction Transfer Updated Successfully!');
        } catch (Exception $err) {
            // Tangani kesalahan di sini
            return dd($err);
        }
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

    // public function createTransactionSend()
    // {
    //     $accountNumbers = AccountNumber::all(); // Ambil semua data dari tabel accountnumbers
    //     return view('components.cash&bank.create-transaction-send', [
    //         'accountNumbers' => $accountNumbers,
    //     ]);
    // }

    // // milik transaction send
    // public function storeTransactionSend(Request $request)
    // {
    //     $request->validate([
    //         // 'accountnumber_id' => 'required', // Pastikan Anda memvalidasi accountnumber_id
    //         'transfer' => 'required',
    //         'deposit' => 'required',
    //         'amount' => 'required|numeric',
    //         'date' => 'required|date_format:d/m/Y',
    //         'description' => 'required',
    //     ]);

    //     $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

    //     Transaction_send::create([
    //         'accountnumber_id' => $request->transfer, // Sertakan nilai accountnumber_id yang diterima dari form
    //         'transfer' => $request->transfer,
    //         'deposit' => $request->deposit,
    //         'amount' => $request->amount,
    //         'date' => $date,
    //         'description' => $request->description,
    //     ]);

    //     return redirect()->route('cash.index')->with('success', 'Transaction Send Created Successfully!');
    // }

    // public function deleteTransactionSend(Request $request)
    // {
    //     try {
    //         // Ambil ID dari request
    //         $transactionId = $request->input('transaction_id');

    //         // Cari data transaksi send berdasarkan ID
    //         $transactionSend = Transaction_send::findOrFail($transactionId);

    //         // Hapus data transaksi send
    //         $transactionSend->delete();

    //         return redirect()->back()->with('success', 'Transaction Send Deleted Successfully!');
    //     } catch (Exception $err) {
    //         return dd($err);
    //     }
    // }

    // // milik transaction Receive
    // public function createTransactionReceive()
    // {
    //     $accountNumbers = AccountNumber::all(); // Ambil semua data dari tabel accountnumbers
    //     return view('components.cash&bank.create-transaction-receive', [
    //         'accountNumbers' => $accountNumbers,
    //     ]);
    // }

    // public function storeTransactionReceive(Request $request)
    // {
    //     $request->validate([
    //         // 'accountnumber_id' => 'required', // Pastikan Anda memvalidasi accountnumber_id
    //         'transfer' => 'required',
    //         'deposit' => 'required',
    //         'amount' => 'required|numeric',
    //         'date' => 'required|date_format:d/m/Y',
    //         'description' => 'required',
    //     ]);

    //     $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

    //     Transaction_receive::create([
    //         'accountnumber_id' => $request->transfer, // Sertakan nilai accountnumber_id yang diterima dari form
    //         'transfer' => $request->transfer,
    //         'deposit' => $request->deposit,
    //         'amount' => $request->amount,
    //         'date' => $date,
    //         'description' => $request->description,
    //     ]);

    //     return redirect()->route('cash.index')->with('success', 'Transaction Send Created Successfully!');
    // }

    // public function deleteTransactionReceive(Request $request)
    // {
    //     try {
    //         // Ambil ID dari request
    //         $transactionId = $request->input('transaction_id');

    //         // Cari data transaksi receive berdasarkan ID
    //         $transactionReceive = Transaction_receive::findOrFail($transactionId);

    //         // Hapus data transaksi receive
    //         $transactionReceive->delete();

    //         return redirect()->back()->with('success', 'Transaction Receive Deleted Successfully!');
    //     } catch (Exception $err) {
    //         return dd($err);
    //     }
    // }

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
                'transaction_transfers.*',
                'a1.account_no AS transfer_account_no',
                'a1.name AS transfer_account_name',
                'transaction_transfers.no_transaction' // Tambahkan kolom no_transaction
            )
                ->leftJoin('accountnumbers as a1', 'a1.id', '=', 'transaction_transfers.transfer_account_id')
                ->leftJoin('accountnumbers as a2', 'a2.id', '=', 'transaction_transfers.deposit_account_id')
                ->where(function ($query) use ($request) {
                    if ($request->has('search')) {
                        $query->where('a1.account_no', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('a2.account_no', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_transfers.amount', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_transfers.description', 'LIKE', '%' . $request->search . '%');
                    }
                });
    
            // Filter data berdasarkan tanggal
            if ($request->filled('date')) {
                $searchDate = date('Y-m-d', strtotime($request->date));
                $transferdata->whereDate('transaction_transfers.date', $searchDate);
            }
    
            $allData = $transferdata
                ->orderBy($form->order ?? 'date', $form->sort ?? 'desc')
                ->paginate(5);
    
            return view('components.journal.index', [
                'allData' => $allData,
                'form' => $form,
            ]);
        } catch (Exception $err) {
            return dd($err);
        }
    }
    

    public function showJournalDetail($id)
    {
        try {
            $transaction = Transaction_transfer::findOrFail($id);

            return view('components.journal.detail', [
                'transaction' => $transaction,
            ]);
        } catch (Exception $e) {
            return redirect()->route('journal.index')->with('error', 'Failed to fetch transaction details.');
        }
    }
}
