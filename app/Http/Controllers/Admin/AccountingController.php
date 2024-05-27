<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Cash;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Models\Accountnumber;
use App\Models\Accountcategory;
use App\Models\Transaction_send;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction_receive;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Transaction_transfer;
use Illuminate\Pagination\LengthAwarePaginator;

class AccountingController extends Controller
{
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

    public function storeAccountCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = new Accountcategory();
        $category->category_name = $request->category_name;
        $category->save();

        // return redirect('create-account.create');
        return redirect()->route('create-account.create');
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
            $query = Transaction_send::with(['transferAccount', 'depositAccount']);

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

    public function createTransactionSend()
    {
        $accountNumbers = AccountNumber::all(); // Ambil semua data dari tabel accountnumbers

        return view('components.cash&bank.create-transaction-send', [
            'accountNumbers' => $accountNumbers,
        ]);
    }

    public function storeTransactionSend(Request $request)
    {
        try {
            $request->validate([
                'transfer_account_id' => 'required',
                'deposit_account_id' => 'required',
                'amount' => 'required|numeric',
                'date' => 'required|date_format:d/m/Y',
                'description' => 'required',
                'no_transaction' => 'required',
                'payer' => 'required'
            ]);

            $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

            Transaction_send::create([
                'transfer_account_id' => $request->transfer_account_id,
                'deposit_account_id' => $request->deposit_account_id,
                'amount' => $request->amount,
                'date' => $date,
                'description' => $request->description,
                'no_transaction' => $request->no_transaction,
                'payer' => $request->payer,
            ]);

            return redirect()->route('transaction-send.index')->with('success', 'Transaction Send Created Successfully!');
        } catch (Exception $err) {
            // Tangani kesalahan di sini
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
            $query = Transaction_receive::with(['transferAccount', 'depositAccount']);

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

    public function createTransactionReceive()
    {
        $accountNumbers = AccountNumber::all(); // Ambil semua data dari tabel accountnumbers

        return view('components.cash&bank.create-transaction-receive', [
            'accountNumbers' => $accountNumbers,
        ]);
    }

    public function storeTransactionReceive(Request $request)
    {
        try {
            $request->validate([
                'transfer_account_id' => 'required',
                'deposit_account_id' => 'required',
                'payer' => 'required',
                'amount' => 'required|numeric',
                'date' => 'required|date_format:d/m/Y',
                'description' => 'required',
                'no_transaction' => 'required',
            ]);

            $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

            Transaction_receive::create([
                'transfer_account_id' => $request->transfer_account_id,
                'deposit_account_id' => $request->deposit_account_id,
                'payer' => $request->payer,
                'amount' => $request->amount,
                'date' => $date,
                'description' => $request->description,
                'no_transaction' => $request->no_transaction,
            ]);

            return redirect()->route('transaction-receive.index')->with('success', 'Transaction Receive Created Successfully!');
        } catch (Exception $err) {
            // Tangani kesalahan di sini
            return dd($err);
        }
    }
}
