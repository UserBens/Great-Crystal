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
            'child' => 'Database Transaction Transfer',
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
                    ->paginate(15);
            } else {
                $data = Accountnumber::orderBy('created_at', $order)->paginate(15);
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
            'account_category_id' => 'required',
            'amount' => 'required|numeric',
            'description' => 'required',
        ]);

        Accountnumber::create([
            'name' => $request->name,
            'account_no' => $request->account_no,
            'account_category_id' => $request->account_category_id,
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
            'account_category_id' => 'required',
            'amount' => 'required|numeric',
            'description' => 'required',
        ]);

        $accountNumbers = Accountnumber::findOrFail($id);

        $accountNumbers->update([
            'name' => $request->name,
            'account_no' => $request->account_no,
            'account_category_id' => $request->account_category_id,
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

    public function indexTransactionSend(Request $request)
    {
        session()->flash('page', (object)[
            'page' => 'Transaction',
            'child' => 'Database Transaction Send',
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
            return view('components.cash&bank.index-send', compact('data', 'form'));
        } catch (Exception $err) {
            // Menampilkan pesan error jika terjadi kesalahan
            return dd($err);
        }
    }

    public function indexTransactionReceive(Request $request)
    {
        session()->flash('page', (object)[
            'page' => 'Transaction',
            'child' => 'Database Transaction Receive',
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
            return view('components.cash&bank.index-receive', compact('data', 'form'));
        } catch (Exception $err) {
            // Menampilkan pesan error jika terjadi kesalahan
            return dd($err);
        }
    }

    public function indexJournal(Request $request)
    {
        session()->flash('page', (object) [
            'page' => 'Journal',
            'child' => 'database Journal',
        ]);

        $form = (object) [
            'sort' => $request->sort ?? null,
            'order' => $request->order ?? 'asc', // Default ascending
            'status' => $request->status ?? null,
            'search' => $request->search ?? null,
            'type' => $request->type ?? null,
        ];

        try {
            $transferdata = Transaction_transfer::select(
                'transaction_transfers.*',
                'a1.account_no AS transfer_account_no',
                'a1.name AS transfer_account_name',
                'a2.account_no AS deposit_account_no',
                'a2.name AS deposit_account_name'
            )
                ->leftJoin('accountnumbers as a1', 'a1.id', '=', 'transaction_transfers.transfer_account_id')
                ->leftJoin('accountnumbers as a2', 'a2.id', '=', 'transaction_transfers.deposit_account_id')
                ->where(function ($query) use ($request) {
                    if ($request->has('search')) {
                        $query->where('transaction_transfers.no_transaction', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('a1.account_no', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('a2.account_no', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('a1.name', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('a2.name', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_transfers.amount', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_transfers.description', 'LIKE', '%' . $request->search . '%');
                    }
                });

            // Filter data berdasarkan tanggal
            if ($request->filled('date')) {
                $searchDate = date('Y-m-d', strtotime($request->date));
                $transferdata->whereDate('transaction_transfers.date', $searchDate);
            }

            if ($request->filled('sort')) {
                // Ambil urutan dari request
                $order = $request->order; // Ubah dari $form->order menjadi $request->order
                $transferdata->orderBy($request->sort, $order);
            } else {
                // Jika tidak ada pengurutan yang dipilih, urutkan berdasarkan tanggal secara default
                $transferdata->orderBy('date', $form->order);
            }

            $allData = $transferdata->paginate(5);

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
