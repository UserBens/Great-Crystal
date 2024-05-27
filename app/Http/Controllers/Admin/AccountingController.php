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
            ]);

            $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

            Transaction_send::create([
                'transfer_account_id' => $request->transfer_account_id,
                'deposit_account_id' => $request->deposit_account_id,
                'amount' => $request->amount,
                'date' => $date,
                'description' => $request->description,
                'no_transaction' => $request->no_transaction,
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


    // public function indexJournal(Request $request)
    // {
    //     session()->flash('page', (object) [
    //         'page' => 'Journal',
    //         'child' => 'database Journal',
    //     ]);

    //     $form = (object) [
    //         'sort' => $request->sort ?? null,
    //         'order' => $request->order ?? 'asc', // Default ascending
    //         'status' => $request->status ?? null,
    //         'search' => $request->search ?? null,
    //         'type' => $request->type ?? null,
    //     ];

    //     try {
    //         $transferdata = Transaction_transfer::select(
    //             'transaction_transfers.*',
    //             'a1.account_no AS transfer_account_no',
    //             'a1.name AS transfer_account_name',
    //             'a2.account_no AS deposit_account_no',
    //             'a2.name AS deposit_account_name'
    //         )
    //             ->leftJoin('accountnumbers as a1', 'a1.id', '=', 'transaction_transfers.transfer_account_id')
    //             ->leftJoin('accountnumbers as a2', 'a2.id', '=', 'transaction_transfers.deposit_account_id')
    //             ->where(function ($query) use ($request) {
    //                 if ($request->has('search')) {
    //                     $query->where('transaction_transfers.no_transaction', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('a1.account_no', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('a2.account_no', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('a1.name', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('a2.name', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('transaction_transfers.amount', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('transaction_transfers.description', 'LIKE', '%' . $request->search . '%');
    //                 }
    //             });

    //         // Filter data berdasarkan tanggal
    //         if ($request->filled('date')) {
    //             $searchDate = date('Y-m-d', strtotime($request->date));
    //             $transferdata->whereDate('transaction_transfers.date', $searchDate);
    //         }

    //         if ($request->filled('sort')) {
    //             // Ambil urutan dari request
    //             $order = $request->order; // Ubah dari $form->order menjadi $request->order
    //             $transferdata->orderBy($request->sort, $order);
    //         } else {
    //             // Jika tidak ada pengurutan yang dipilih, urutkan berdasarkan tanggal secara default
    //             $transferdata->orderBy('date', $form->order);
    //         }

    //         $transfer = $transferdata->paginate(5);

    //         return view('components.journal.index', [
    //             'allData' => $transfer,
    //             'form' => $form,
    //         ]);
    //     } catch (Exception $err) {
    //         return dd($err);
    //     }
    // }


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
            'date' => $request->date ?? null,
        ];

        try {
            $transferdata = Transaction_transfer::select(
                'transaction_transfers.*',
                'a1.account_no AS transfer_account_no',
                'a1.name AS transfer_account_name',
                'a2.account_no AS deposit_account_no',
                'a2.name AS deposit_account_name',
                DB::raw("'transfer' as type")
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

            $senddata = Transaction_send::select(
                'transaction_sends.*',
                'a1.account_no AS transfer_account_no',
                'a1.name AS transfer_account_name',
                'a2.account_no AS deposit_account_no',
                'a2.name AS deposit_account_name',
                DB::raw("'send' as type")
            )
                ->leftJoin('accountnumbers as a1', 'a1.id', '=', 'transaction_sends.transfer_account_id')
                ->leftJoin('accountnumbers as a2', 'a2.id', '=', 'transaction_sends.deposit_account_id')
                ->where(function ($query) use ($request) {
                    if ($request->has('search')) {
                        $query->where('transaction_sends.no_transaction', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('a1.account_no', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('a1.name', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_sends.amount', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_sends.description', 'LIKE', '%' . $request->search . '%');
                    }
                });

            $receivedata = Transaction_receive::select(
                'transaction_receives.*',
                'a1.account_no AS transfer_account_no',
                'a1.name AS transfer_account_name',
                'a2.account_no AS deposit_account_no',
                'a2.name AS deposit_account_name',
                DB::raw("'receive' as type")
            )
                ->leftJoin('accountnumbers as a1', 'a1.id', '=', 'transaction_receives.transfer_account_id')
                ->leftJoin('accountnumbers as a2', 'a2.id', '=', 'transaction_receives.deposit_account_id')
                ->where(function ($query) use ($request) {
                    if ($request->has('search')) {
                        $query->where('transaction_receives.no_transaction', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('a1.account_no', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('a1.name', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_receives.amount', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('transaction_receives.description', 'LIKE', '%' . $request->search . '%');
                    }
                });

            // Filter data by date
            if ($request->filled('date')) {
                $searchDate = date('Y-m-d', strtotime($request->date));
                $transferdata->whereDate('transaction_transfers.date', $searchDate);
                $senddata->whereDate('transaction_sends.date', $searchDate);
                $receivedata->whereDate('transaction_receives.date', $searchDate);
            }

            if ($request->filled('sort')) {
                // Get order from request
                $order = $request->order;
                $transferdata->orderBy($request->sort, $order);
                $senddata->orderBy($request->sort, $order);
                $receivedata->orderBy($request->sort, $order);
            } else {
                // Default sort by date
                $transferdata->orderBy('date', $form->order);
                $senddata->orderBy('date', $form->order);
                $receivedata->orderBy('date', $form->order);
            }

            // Ambil data lebih banyak dari setiap jenis transaksi
            $transfer = $transferdata->take(10)->get();
            $send = $senddata->take(10)->get();
            $receive = $receivedata->take(10)->get();

            // Combine results
            $combinedData = collect([]);

            // Ambil 2 data transfer
            $combinedData = $combinedData->merge($transfer->take(2));

            // Ambil 2 data send
            $combinedData = $combinedData->merge($send->take(2));

            // Ambil 2 data receive
            $combinedData = $combinedData->merge($receive->take(2));

            // Sort combined results
            if ($form->sort) {
                $combinedData = $combinedData->sortBy($form->sort, SORT_REGULAR, $form->order === 'desc');
            }

            // Paginate combined results
            $perPage = 6;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();

            $path = $request->url(); // Menggunakan URL saat ini sebagai jalur

            // Ubah menjadi seperti ini
            $paginatedData = new LengthAwarePaginator(
                $combinedData->forPage($currentPage, $perPage),
                $combinedData->count(),
                $perPage,
                $currentPage,
                [
                    'path' => $path,
                    'pageName' => 'page',
                ]
            );

            return view('components.journal.index', [
                'transfer' => $transfer,
                'send' => $send,
                'receive' => $receive,
                'allData' => $paginatedData,
                'form' => $form,
            ]);
        } catch (Exception $err) {
            return dd($err);
        }
    }


    public function showJournalDetail($id, $type)
    {
        try {
            // Variabel untuk menyimpan data detail transaksi
            $transactionDetails = [];

            // Sesuaikan pengecekan berdasarkan tipe transaksi
            if ($type === 'transaction_transfer') {
                $transaction = Transaction_transfer::find($id);

                if ($transaction) {
                    // Mengambil data transfer account
                    $transferAccount = $transaction->transferAccount;
                    // Mengambil data deposit account
                    $depositAccount = $transaction->depositAccount;

                    $transactionDetails = [
                        [
                            'account_number' => $transferAccount->account_no,
                            'account_name' => $transferAccount->name,
                            'debit' => 0,
                            'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ],
                        [
                            'account_number' => $depositAccount->account_no,
                            'account_name' => $depositAccount->name,
                            'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'credit' => 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ]
                    ];
                }
            } elseif ($type === 'transaction_send') {
                $transaction = Transaction_send::find($id);

                if ($transaction) {
                    $transferAccount = $transaction->transferAccount;
                    $depositAccount = $transaction->depositAccount;

                    $transactionDetails = [
                        [
                            'account_number' => $transferAccount->account_no,
                            'account_name' => $transferAccount->name,
                            'debit' => 0,
                            'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ],
                        [
                            'account_number' => $depositAccount->account_no,
                            'account_name' => $depositAccount->name,
                            'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'credit' => 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ]
                    ];
                }
            } elseif ($type === 'transaction_receive') {
                $transaction = Transaction_receive::find($id);

                if ($transaction) {
                    $transferAccount = $transaction->transferAccount;
                    $depositAccount = $transaction->depositAccount;

                    $transactionDetails = [
                        [
                            'account_number' => $transferAccount->account_no,
                            'account_name' => $transferAccount->name,
                            'debit' => 0,
                            'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ],
                        [
                            'account_number' => $depositAccount->account_no,
                            'account_name' => $depositAccount->name,
                            'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'credit' => 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ]
                    ];
                }
            } else {
                // Jika tipe transaksi tidak valid, kembalikan ke halaman index dengan pesan error
                return redirect()->route('journal.index')->with('error', 'Invalid transaction type.');
            }

            // Jika data ditemukan, tampilkan detailnya
            if ($transaction !== null) {
                return view('components.journal.detail', [
                    'transaction' => $transaction,
                    'transactionDetails' => $transactionDetails,
                    'type' => $type,
                ]);
            } else {
                // Jika data tidak ditemukan, kembalikan ke halaman index dengan pesan error
                return redirect()->route('journal.index')->with('error', 'Transaction details not found.');
            }
        } catch (Exception $e) {
            // Tangani kesalahan dengan mengembalikan ke halaman index dengan pesan error
            return redirect()->route('journal.index')->with('error', 'Failed to fetch transaction details.');
        }
    }

    // public function showJournalDetail($id, $type)
    // {
    //     try {
    //         // Variabel untuk menyimpan data detail transaksi
    //         $transactionDetails = [];

    //         // Sesuaikan pengecekan berdasarkan tipe transaksi
    //         $transaction = null;
    //         if ($type === 'transaction_transfer') {
    //             $transaction = Transaction_transfer::find($id);
    //         } elseif ($type === 'transaction_send') {
    //             $transaction = Transaction_send::find($id);
    //         } elseif ($type === 'transaction_receive') {
    //             $transaction = Transaction_receive::find($id);
    //         }

    //         if ($transaction) {
    //             // Mengambil data transfer account
    //             $transferAccount = $transaction->transferAccount;
    //             // Mengambil data deposit account
    //             $depositAccount = $transaction->depositAccount;

    //             $transactionDetails = [
    //                 [
    //                     'account_number' => $transferAccount->account_no,
    //                     'account_name' => $transferAccount->name,
    //                     'debit' => 0,
    //                     'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                     'date' => $transaction->date,
    //                     'description' => $transaction->description,
    //                     'created_at' => $transaction->created_at
    //                 ],
    //                 [
    //                     'account_number' => $depositAccount->account_no,
    //                     'account_name' => $depositAccount->name,
    //                     'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                     'credit' => 0,
    //                     'date' => $transaction->date,
    //                     'description' => $transaction->description,
    //                     'created_at' => $transaction->created_at
    //                 ]
    //             ];
    //         } else {
    //             // Jika tipe transaksi tidak valid, kembalikan ke halaman index dengan pesan error
    //             return redirect()->route('journal.index')->with('error', 'Invalid transaction type.');
    //         }

    //         // Jika data ditemukan, tampilkan detailnya
    //         if ($transaction !== null) {
    //             if (request()->has('pdf')) {
    //                 // Debugging
    //                 Log::info('Generating PDF for transaction ID: ' . $id . ' with type: ' . $type);

    //                 // Buat tampilan PDF
    //                 $pdf = app('dompdf.wrapper');
    //                 $pdf->loadView('components.journal.detail-pdf', [
    //                     'transaction' => $transaction,
    //                     'transactionDetails' => $transactionDetails,
    //                 ]);

    //                 // Debugging
    //                 Log::info('PDF generated successfully for transaction ID: ' . $id . ' with type: ' . $type);

    //                 // Download PDF
    //                 return $pdf->download('journal_detail.pdf');
    //             }

    //             // Jika tidak ada parameter 'pdf', tampilkan view biasa
    //             return view('components.journal.detail', [
    //                 'transaction' => $transaction,
    //                 'transactionDetails' => $transactionDetails,
    //                 'type' => $type, // Tambahkan variabel $type ke dalam array data
    //             ]);
    //         } else {
    //             // Jika data tidak ditemukan, kembalikan ke halaman index dengan pesan error
    //             return redirect()->route('journal.index')->with('error', 'Transaction details not found.');
    //         }
    //     } catch (Exception $e) {
    //         // Tangani kesalahan dengan mengembalikan ke halaman index dengan pesan error
    //         return redirect()->route('journal.index')->with('error', 'Failed to fetch transaction details.');
    //     }
    // }





    public function generatePdfJournalDetail($id, $type)
    {
        session()->flash('page', (object)[
            'page' => 'Journal',
            'child' => 'Journal Details'
        ]);

        try {
            // Variabel untuk menyimpan data detail transaksi
            $transactionDetails = [];

            // Sesuaikan pengecekan berdasarkan tipe transaksi
            if ($type === 'transaction_transfer') {
                $transaction = Transaction_transfer::find($id);

                if ($transaction) {
                    // Mengambil data transfer account
                    $transferAccount = $transaction->transferAccount;
                    // Mengambil data deposit account
                    $depositAccount = $transaction->depositAccount;

                    $transactionDetails = [
                        [
                            'account_number' => $transferAccount->account_no,
                            'account_name' => $transferAccount->name,
                            'debit' => 0,
                            'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ],
                        [
                            'account_number' => $depositAccount->account_no,
                            'account_name' => $depositAccount->name,
                            'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'credit' => 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ]
                    ];
                }
            } elseif ($type === 'transaction_send') {
                $transaction = Transaction_send::find($id);

                if ($transaction) {
                    $transferAccount = $transaction->transferAccount;
                    $depositAccount = $transaction->depositAccount;

                    $transactionDetails = [
                        [
                            'account_number' => $transferAccount->account_no,
                            'account_name' => $transferAccount->name,
                            'debit' => 0,
                            'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ],
                        [
                            'account_number' => $depositAccount->account_no,
                            'account_name' => $depositAccount->name,
                            'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'credit' => 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ]
                    ];
                }
            } elseif ($type === 'transaction_receive') {
                $transaction = Transaction_receive::find($id);

                if ($transaction) {
                    $transferAccount = $transaction->transferAccount;
                    $depositAccount = $transaction->depositAccount;

                    $transactionDetails = [
                        [
                            'account_number' => $transferAccount->account_no,
                            'account_name' => $transferAccount->name,
                            'debit' => 0,
                            'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ],
                        [
                            'account_number' => $depositAccount->account_no,
                            'account_name' => $depositAccount->name,
                            'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'credit' => 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ]
                    ];
                }
            } else {
                // Jika tipe transaksi tidak valid, kembalikan ke halaman index dengan pesan error
                return redirect()->route('journal.index')->with('error', 'Invalid transaction type.');
            }

            $nameFormatPdf = Carbon::now()->format('YmdHis') . mt_rand(1000, 9999) . '_journal_detail.pdf';

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('components.journal.detail-pdf', [
                'transaction' => $transaction,
                'transactionDetails' => $transactionDetails,
                'type' => $type,
            ])->setPaper('a4', 'landscape');

            return $pdf->stream($nameFormatPdf);
        } catch (Exception $e) {
            return abort(500, 'Failed to fetch transaction details.');
        }
    }
}
