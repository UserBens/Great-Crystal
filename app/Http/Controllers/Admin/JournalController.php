<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;

use App\Models\Cash;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Models\Accountnumber;
use App\Imports\JournalImport;
use App\Models\Accountcategory;
use App\Models\Transaction_send;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction_receive;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\AccountnumberChanges;
use App\Models\Bill;
use App\Models\InvoiceSupplier;
use App\Models\Transaction_transfer;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;

class JournalController extends Controller
{
    // public function indexJournal(Request $request)
    // {
    //     session()->flash('page', (object) [
    //         'page' => 'Journal',
    //         'child' => 'database Journal',
    //     ]);

    //     $selectedItems = $request->id ?? [];

    //     $form = (object) [
    //         'sort' => $request->sort ?? 'date', // Default sort by date
    //         'order' => $request->order ?? 'desc', // Default descending
    //         'status' => $request->status ?? null,
    //         'search' => $request->search ?? null,
    //         'type' => $request->type ?? null,
    //         'start_date' => $request->start_date ?? null,
    //         'end_date' => $request->end_date ?? null,
    //     ];


    //     $transactionTransfers = DB::table('transaction_transfers')
    //         ->select(
    //             'transaction_transfers.id',
    //             'transaction_transfers.no_transaction',
    //             'accountnumbers_transfer.name as transfer_account_name',
    //             'accountnumbers_transfer.account_no as transfer_account_no',
    //             'accountnumbers_deposit.name as deposit_account_name',
    //             'accountnumbers_deposit.account_no as deposit_account_no',
    //             'transaction_transfers.amount',
    //             'transaction_transfers.date',
    //             'transaction_transfers.created_at',
    //             DB::raw('"transaction_transfer" as type')
    //         )
    //         ->join('accountnumbers as accountnumbers_transfer', 'transaction_transfers.transfer_account_id', '=', 'accountnumbers_transfer.id')
    //         ->join('accountnumbers as accountnumbers_deposit', 'transaction_transfers.deposit_account_id', '=', 'accountnumbers_deposit.id');

    //     $transactionSends = DB::table('transaction_sends')
    //         ->select(
    //             'transaction_sends.id',
    //             'transaction_sends.no_transaction',
    //             'accountnumbers_transfer.name as transfer_account_name',
    //             'accountnumbers_transfer.account_no as transfer_account_no',
    //             'accountnumbers_deposit.name as deposit_account_name',
    //             'accountnumbers_deposit.account_no as deposit_account_no',
    //             'transaction_sends.amount',
    //             'transaction_sends.date',
    //             'transaction_sends.created_at',
    //             DB::raw('"transaction_send" as type')
    //         )
    //         ->join('accountnumbers as accountnumbers_transfer', 'transaction_sends.transfer_account_id', '=', 'accountnumbers_transfer.id')
    //         ->join('accountnumbers as accountnumbers_deposit', 'transaction_sends.deposit_account_id', '=', 'accountnumbers_deposit.id');

    //     $transactionReceives = DB::table('transaction_receives')
    //         ->select(
    //             'transaction_receives.id',
    //             'transaction_receives.no_transaction',
    //             'accountnumbers_transfer.name as transfer_account_name',
    //             'accountnumbers_transfer.account_no as transfer_account_no',
    //             'accountnumbers_deposit.name as deposit_account_name',
    //             'accountnumbers_deposit.account_no as deposit_account_no',
    //             'transaction_receives.amount',
    //             'transaction_receives.date',
    //             'transaction_receives.created_at',
    //             DB::raw('"transaction_receive" as type')
    //         )
    //         ->join('accountnumbers as accountnumbers_transfer', 'transaction_receives.transfer_account_id', '=', 'accountnumbers_transfer.id')
    //         ->join('accountnumbers as accountnumbers_deposit', 'transaction_receives.deposit_account_id', '=', 'accountnumbers_deposit.id');

    //     $unionQuery = $transactionTransfers->unionAll($transactionSends)->unionAll($transactionReceives);

    //     $query = DB::table(DB::raw("({$unionQuery->toSql()}) as sub"))
    //         ->mergeBindings($unionQuery);

    //     if ($form->type) {
    //         $query->where('type', $form->type);
    //     }

    //     if ($form->start_date && $form->end_date) {
    //         $query->whereBetween('date', [$form->start_date, $form->end_date]);
    //     }


    //     if ($form->search) {
    //         $query->where(function ($query) use ($form) {
    //             $query->where('no_transaction', 'like', '%' . $form->search . '%')
    //                 ->orWhere('transfer_account_name', 'like', '%' . $form->search . '%')
    //                 ->orWhere('deposit_account_name', 'like', '%' . $form->search . '%')
    //                 ->orWhere('transfer_account_no', 'like', '%' . $form->search . '%')
    //                 ->orWhere('deposit_account_no', 'like', '%' . $form->search . '%');
    //         });
    //     }

    //     if ($form->sort) {
    //         $query->orderBy($form->sort, $form->order);
    //     }

    //     $allData = $query->paginate(10);

    //     return view('components.journal.index', compact('allData', 'form', 'selectedItems'));
    // }

    // old code
    // public function indexJournal(Request $request)
    // {
    //     session()->flash('preloader', true);
    //     session()->flash('page', (object) [
    //         'page' => 'Journal',
    //         'child' => 'database Journal',
    //     ]);

    //     $selectedItems = $request->id ?? [];

    //     $form = (object) [
    //         'sort' => $request->sort ?? 'date', // Default sort by date
    //         'order' => $request->order ?? 'desc', // Default descending
    //         'status' => $request->status ?? null,
    //         'search' => $request->search ?? null,
    //         'type' => $request->type ?? null,
    //         'start_date' => $request->start_date ?? null,
    //         'end_date' => $request->end_date ?? null,
    //     ];

    //     $transactionTransfers = DB::table('transaction_transfers')
    //         ->select(
    //             'transaction_transfers.id',
    //             'transaction_transfers.no_transaction',
    //             'accountnumbers_transfer.name as transfer_account_name',
    //             'accountnumbers_transfer.account_no as transfer_account_no',
    //             'accountnumbers_deposit.name as deposit_account_name',
    //             'accountnumbers_deposit.account_no as deposit_account_no',
    //             'transaction_transfers.amount',
    //             'transaction_transfers.date',
    //             'transaction_transfers.created_at',
    //             DB::raw('"transaction_transfer" as type')
    //         )
    //         ->join('accountnumbers as accountnumbers_transfer', 'transaction_transfers.transfer_account_id', '=', 'accountnumbers_transfer.id')
    //         ->join('accountnumbers as accountnumbers_deposit', 'transaction_transfers.deposit_account_id', '=', 'accountnumbers_deposit.id');

    //     $transactionSends = DB::table('transaction_sends')
    //         ->select(
    //             'transaction_sends.id',
    //             'transaction_sends.no_transaction',
    //             'accountnumbers_transfer.name as transfer_account_name',
    //             'accountnumbers_transfer.account_no as transfer_account_no',
    //             'accountnumbers_deposit.name as deposit_account_name',
    //             'accountnumbers_deposit.account_no as deposit_account_no',
    //             'transaction_sends.amount',
    //             'transaction_sends.date',
    //             'transaction_sends.created_at',
    //             DB::raw('"transaction_send" as type')
    //         )
    //         ->join('accountnumbers as accountnumbers_transfer', 'transaction_sends.transfer_account_id', '=', 'accountnumbers_transfer.id')
    //         ->join('accountnumbers as accountnumbers_deposit', 'transaction_sends.deposit_account_id', '=', 'accountnumbers_deposit.id');

    //     $transactionReceives = DB::table('transaction_receives')
    //         ->select(
    //             'transaction_receives.id',
    //             'transaction_receives.no_transaction',
    //             'accountnumbers_transfer.name as transfer_account_name',
    //             'accountnumbers_transfer.account_no as transfer_account_no',
    //             'accountnumbers_deposit.name as deposit_account_name',
    //             'accountnumbers_deposit.account_no as deposit_account_no',
    //             'transaction_receives.amount',
    //             'transaction_receives.date',
    //             'transaction_receives.created_at',
    //             DB::raw('"transaction_receive" as type')
    //         )
    //         ->join('accountnumbers as accountnumbers_transfer', 'transaction_receives.transfer_account_id', '=', 'accountnumbers_transfer.id')
    //         ->join('accountnumbers as accountnumbers_deposit', 'transaction_receives.deposit_account_id', '=', 'accountnumbers_deposit.id');

    //     $invoiceSuppliers = DB::table('invoice_suppliers')
    //         ->select(
    //             'invoice_suppliers.id',
    //             'invoice_suppliers.no_invoice as no_transaction',
    //             'accountnumbers_transfer.name as transfer_account_name',
    //             'accountnumbers_transfer.account_no as transfer_account_no',
    //             'accountnumbers_deposit.name as deposit_account_name',
    //             'accountnumbers_deposit.account_no as deposit_account_no',
    //             'invoice_suppliers.amount',
    //             'invoice_suppliers.date',
    //             'invoice_suppliers.created_at',
    //             DB::raw('"invoice_supplier" as type')
    //         )
    //         ->leftJoin('accountnumbers as accountnumbers_transfer', 'invoice_suppliers.transfer_account_id', '=', 'accountnumbers_transfer.id')
    //         ->leftJoin('accountnumbers as accountnumbers_deposit', 'invoice_suppliers.deposit_account_id', '=', 'accountnumbers_deposit.id');

    //     $bills = DB::table('bills')
    //         ->select(
    //             'bills.id',
    //             'bills.number_invoice as no_transaction', // Pastikan number_invoice disertakan
    //             'accountnumbers_transfer.name as transfer_account_name',
    //             'accountnumbers_transfer.account_no as transfer_account_no',
    //             'accountnumbers_deposit.name as deposit_account_name',
    //             'accountnumbers_deposit.account_no as deposit_account_no',
    //             'bills.amount',
    //             'bills.deadline_invoice as date',
    //             'bills.created_at',
    //             DB::raw('"bill" as type')
    //         )
    //         ->leftJoin('accountnumbers as accountnumbers_transfer', 'bills.transfer_account_id', '=', 'accountnumbers_transfer.id')
    //         ->leftJoin('accountnumbers as accountnumbers_deposit', 'bills.deposit_account_id', '=', 'accountnumbers_deposit.id');

    //     $unionQuery = $transactionTransfers
    //         ->unionAll($transactionSends)
    //         ->unionAll($transactionReceives)
    //         ->unionAll($invoiceSuppliers)
    //         ->unionAll($bills); // Include bills in the union

    //     $query = DB::table(DB::raw("({$unionQuery->toSql()}) as sub"))
    //         ->mergeBindings($unionQuery);

    //     Log::info('Data sebelum filter tanggal:', $query->get()->toArray());

    //     if ($form->start_date && $form->end_date) {
    //         $start_date = Carbon::createFromFormat('Y-m-d', $form->start_date)->startOfDay();
    //         $end_date = Carbon::createFromFormat('Y-m-d', $form->end_date)->endOfDay();

    //         $query->whereBetween('date', [$start_date, $end_date]);
    //     }

    //     Log::info('Data setelah filter tanggal:', $query->get()->toArray());

    //     if ($form->type) {
    //         $query->where('type', $form->type);
    //     }

    //     if ($form->search) {
    //         $query->where(function ($query) use ($form) {
    //             $query->where('no_transaction', 'like', '%' . $form->search . '%')
    //                 ->orWhere('transfer_account_name', 'like', '%' . $form->search . '%')
    //                 ->orWhere('deposit_account_name', 'like', '%' . $form->search . '%')
    //                 ->orWhere('transfer_account_no', 'like', '%' . $form->search . '%')
    //                 ->orWhere('deposit_account_no', 'like', '%' . $form->search . '%');
    //         });
    //     }

    //     if ($form->sort) {
    //         $query->orderBy($form->sort, $form->order);
    //     }

    //     $allData = $query->paginate(10);

    //     return view('components.journal.index', compact('allData', 'form', 'selectedItems'));
    // }

    // code from gpt
    public function indexJournal(Request $request)
    {
        session()->flash('preloader', true);
        session()->flash('page', (object) [
            'page' => 'Journal',
            'child' => 'database Journal',
        ]);

        $form = (object) [
            'sort' => $request->sort ?? 'date',
            'order' => $request->order ?? 'desc',
            'status' => $request->status ?? null,
            'search' => $request->search ?? null,
            'type' => $request->type ?? null,
            'start_date' => $request->start_date ?? null,
            'end_date' => $request->end_date ?? null,
        ];

        $transactionTransfers = DB::table('transaction_transfers')
            ->select(
                'transaction_transfers.id',
                'transaction_transfers.no_transaction',
                'accountnumbers_transfer.name as transfer_account_name',
                'accountnumbers_transfer.account_no as transfer_account_no',
                'accountnumbers_deposit.name as deposit_account_name',
                'accountnumbers_deposit.account_no as deposit_account_no',
                'transaction_transfers.amount',
                'transaction_transfers.date',
                'transaction_transfers.created_at',
                DB::raw('"transaction_transfer" as type')
            )
            ->join('accountnumbers as accountnumbers_transfer', 'transaction_transfers.transfer_account_id', '=', 'accountnumbers_transfer.id')
            ->join('accountnumbers as accountnumbers_deposit', 'transaction_transfers.deposit_account_id', '=', 'accountnumbers_deposit.id');

        $transactionSends = DB::table('transaction_sends')
            ->select(
                'transaction_sends.id',
                'transaction_sends.no_transaction',
                'accountnumbers_transfer.name as transfer_account_name',
                'accountnumbers_transfer.account_no as transfer_account_no',
                'accountnumbers_deposit.name as deposit_account_name',
                'accountnumbers_deposit.account_no as deposit_account_no',
                'transaction_sends.amount',
                'transaction_sends.date',
                'transaction_sends.created_at',
                DB::raw('"transaction_send" as type')
            )
            ->join('accountnumbers as accountnumbers_transfer', 'transaction_sends.transfer_account_id', '=', 'accountnumbers_transfer.id')
            ->join('accountnumbers as accountnumbers_deposit', 'transaction_sends.deposit_account_id', '=', 'accountnumbers_deposit.id');

        $transactionReceives = DB::table('transaction_receives')
            ->select(
                'transaction_receives.id',
                'transaction_receives.no_transaction',
                'accountnumbers_transfer.name as transfer_account_name',
                'accountnumbers_transfer.account_no as transfer_account_no',
                'accountnumbers_deposit.name as deposit_account_name',
                'accountnumbers_deposit.account_no as deposit_account_no',
                'transaction_receives.amount',
                'transaction_receives.date',
                'transaction_receives.created_at',
                DB::raw('"transaction_receive" as type')
            )
            ->join('accountnumbers as accountnumbers_transfer', 'transaction_receives.transfer_account_id', '=', 'accountnumbers_transfer.id')
            ->join('accountnumbers as accountnumbers_deposit', 'transaction_receives.deposit_account_id', '=', 'accountnumbers_deposit.id');

        $invoiceSuppliers = DB::table('invoice_suppliers')
            ->select(
                'invoice_suppliers.id',
                'invoice_suppliers.no_invoice as no_transaction',
                'accountnumbers_transfer.name as transfer_account_name',
                'accountnumbers_transfer.account_no as transfer_account_no',
                'accountnumbers_deposit.name as deposit_account_name',
                'accountnumbers_deposit.account_no as deposit_account_no',
                'invoice_suppliers.amount',
                'invoice_suppliers.date',
                'invoice_suppliers.created_at',
                DB::raw('"invoice_supplier" as type')
            )
            ->leftJoin('accountnumbers as accountnumbers_transfer', 'invoice_suppliers.transfer_account_id', '=', 'accountnumbers_transfer.id')
            ->leftJoin('accountnumbers as accountnumbers_deposit', 'invoice_suppliers.deposit_account_id', '=', 'accountnumbers_deposit.id');

        $bills = DB::table('bills')
            ->select(
                'bills.id',
                'bills.number_invoice as no_transaction',
                'accountnumbers_transfer.name as transfer_account_name',
                'accountnumbers_transfer.account_no as transfer_account_no',
                DB::raw('COALESCE(accountnumbers_new_deposit.name, accountnumbers_deposit.name) as deposit_account_name'),
                DB::raw('COALESCE(accountnumbers_new_deposit.account_no, accountnumbers_deposit.account_no) as deposit_account_no'),
                'bills.amount',
                'bills.deadline_invoice as date',
                'bills.created_at',
                DB::raw('"bill" as type')
            )
            ->leftJoin('accountnumbers as accountnumbers_transfer', 'bills.transfer_account_id', '=', 'accountnumbers_transfer.id')
            ->leftJoin('accountnumbers as accountnumbers_deposit', 'bills.deposit_account_id', '=', 'accountnumbers_deposit.id')
            ->leftJoin('accountnumbers as accountnumbers_new_deposit', 'bills.new_deposit_account_id', '=', 'accountnumbers_new_deposit.id');


        $unionQuery = $transactionTransfers
            ->unionAll($transactionSends)
            ->unionAll($transactionReceives)
            ->unionAll($invoiceSuppliers)
            ->unionAll($bills);

        $query = DB::table(DB::raw("({$unionQuery->toSql()}) as sub"))
            ->mergeBindings($unionQuery);

        if ($form->start_date && $form->end_date) {
            $start_date = Carbon::createFromFormat('Y-m-d', $form->start_date)->startOfDay();
            $end_date = Carbon::createFromFormat('Y-m-d', $form->end_date)->endOfDay();

            $query->whereBetween('date', [$start_date, $end_date]);
        }

        if ($form->type) {
            $query->where('type', $form->type);
        }

        if ($form->search) {
            $query->where(function ($query) use ($form) {
                $query->where('no_transaction', 'like', '%' . $form->search . '%')
                    ->orWhere('transfer_account_name', 'like', '%' . $form->search . '%')
                    ->orWhere('deposit_account_name', 'like', '%' . $form->search . '%')
                    ->orWhere('transfer_account_no', 'like', '%' . $form->search . '%')
                    ->orWhere('deposit_account_no', 'like', '%' . $form->search . '%');
            });
        }

        if ($form->sort) {
            $query->orderBy($form->sort, $form->order);
        } else {
            $query->orderBy('date', 'desc');
        }

        $allData = $query->paginate(25);

        return view('components.journal.index', compact('allData', 'form'));
    }



    // public function showFilterJournalDetail(Request $request)
    // {
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $type = $request->input('type');
    //     $search = $request->input('search');
    //     $sort = $request->input('sort');
    //     $order = $request->input('order');

    //     // Log debugging
    //     Log::info('Request Data: ', $request->all());

    //     // Check for correct date format and parse dates
    //     if ($startDate) {
    //         $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
    //     }
    //     if ($endDate) {
    //         $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
    //     }

    //     $transactionDetails = [];

    //     // Define variables as empty collections
    //     $transactionTransfers = collect();
    //     $transactionReceives = collect();
    //     $transactionSends = collect();

    //     // Logika untuk mengambil transaksi berdasarkan jenis transaksi
    //     if ($type === 'transaction_transfer' || empty($type)) {
    //         $transactionTransfers = Transaction_transfer::with(['transferAccount', 'depositAccount'])
    //             ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
    //                 return $query->whereBetween('date', [$startDate, $endDate]);
    //             })
    //             ->when($search, function ($query, $search) {
    //                 return $query->where('no_transaction', 'LIKE', "%{$search}%");
    //             })
    //             ->when($sort && $order, function ($query) use ($sort, $order) {
    //                 return $query->orderBy($sort, $order);
    //             })
    //             ->get();

    //         foreach ($transactionTransfers as $transaction) {
    //             $transferAccount = $transaction->transferAccount;
    //             $depositAccount = $transaction->depositAccount;

    //             $transactionDetails[] = [
    //                 [
    //                     'no_transaction' => $transaction->no_transaction ?? 'N/A',
    //                     'account_number' => $transferAccount->account_no,
    //                     'account_name' => $transferAccount->name,
    //                     'debit' => 0,
    //                     'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                     'date' => $transaction->date,
    //                     'description' => $transaction->description,
    //                     'created_at' => $transaction->created_at
    //                 ],
    //                 [
    //                     'no_transaction' => $transaction->no_transaction ?? 'N/A',
    //                     'account_number' => $depositAccount->account_no,
    //                     'account_name' => $depositAccount->name,
    //                     'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                     'credit' => 0,
    //                     'date' => $transaction->date,
    //                     'description' => $transaction->description,
    //                     'created_at' => $transaction->created_at
    //                 ]
    //             ];
    //         }
    //     }

    //     if ($type === 'transaction_send' || empty($type)) {
    //         $transactionSends = Transaction_send::with(['transferAccount', 'depositAccount'])
    //             ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
    //                 return $query->whereBetween('date', [$startDate, $endDate]);
    //             })
    //             ->get();

    //         foreach ($transactionSends as $transaction) {
    //             $transferAccount = $transaction->transferAccount;
    //             $depositAccount = $transaction->depositAccount;

    //             $transactionDetails[] = [
    //                 [
    //                     'no_transaction' => $transaction->no_transaction ?? 'N/A',
    //                     'account_number' => $transferAccount->account_no,
    //                     'account_name' => $transferAccount->name,
    //                     'debit' => 0,
    //                     'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                     'date' => $transaction->date,
    //                     'description' => $transaction->description,
    //                     'created_at' => $transaction->created_at
    //                 ],
    //                 [
    //                     'no_transaction' => $transaction->no_transaction ?? 'N/A',
    //                     'account_number' => $depositAccount->account_no,
    //                     'account_name' => $depositAccount->name,
    //                     'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                     'credit' => 0,
    //                     'date' => $transaction->date,
    //                     'description' => $transaction->description,
    //                     'created_at' => $transaction->created_at
    //                 ]
    //             ];
    //         }
    //     }

    //     if ($type === 'transaction_receive' || empty($type)) {
    //         $transactionReceives = Transaction_receive::with(['transferAccount', 'depositAccount'])
    //             ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
    //                 return $query->whereBetween('date', [$startDate, $endDate]);
    //             })
    //             ->get();

    //         foreach ($transactionReceives as $transaction) {
    //             $transferAccount = $transaction->transferAccount;
    //             $depositAccount = $transaction->depositAccount;

    //             $transactionDetails[] = [
    //                 [
    //                     'no_transaction' => $transaction->no_transaction ?? 'N/A',
    //                     'account_number' => $transferAccount->account_no,
    //                     'account_name' => $transferAccount->name,
    //                     'debit' => 0,
    //                     'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                     'date' => $transaction->date,
    //                     'description' => $transaction->description,
    //                     'created_at' => $transaction->created_at
    //                 ],
    //                 [
    //                     'no_transaction' => $transaction->no_transaction ?? 'N/A',
    //                     'account_number' => $depositAccount->account_no,
    //                     'account_name' => $depositAccount->name,
    //                     'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                     'credit' => 0,
    //                     'date' => $transaction->date,
    //                     'description' => $transaction->description,
    //                     'created_at' => $transaction->created_at
    //                 ]
    //             ];
    //         }
    //     }

    //     $allTransactions = $transactionTransfers->merge($transactionSends)->merge($transactionReceives);

    //     // Debugging data yang akan dikirim ke view
    //     Log::info('Transaction Details: ' . print_r($transactionDetails, true));

    //     return view('components.journal.selected-detail', [
    //         'transactionDetails' => $transactionDetails,
    //         'selectedNoTransactions' => $allTransactions->pluck('no_transaction')->toArray(),
    //     ]);
    // }

    public function showFilterJournalDetail(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $type = $request->input('type');
        $search = $request->input('search');
        $sort = $request->input('sort');
        $order = $request->input('order');

        // Log debugging
        Log::info('Request Data: ', $request->all());

        // Check for correct date format and parse dates
        if ($startDate) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        }
        if ($endDate) {
            $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
        }

        $transactionDetails = [];

        // Define variables as empty collections
        $transactionTransfers = collect();
        $transactionReceives = collect();
        $transactionSends = collect();
        $invoiceSuppliers = collect();
        $bills = collect();

        // Logika untuk mengambil transaksi berdasarkan jenis transaksi
        if ($type === 'transaction_transfer' || empty($type)) {
            $transactionTransfers = Transaction_transfer::with(['transferAccount', 'depositAccount'])
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->when($search, function ($query, $search) {
                    return $query->where('no_transaction', 'LIKE', "%{$search}%");
                })
                ->when($sort && $order, function ($query) use ($sort, $order) {
                    return $query->orderBy($sort, $order);
                })
                ->get();

            foreach ($transactionTransfers as $transaction) {
                $transferAccount = $transaction->transferAccount;
                $depositAccount = $transaction->depositAccount;

                $transactionDetails[] = [
                    [
                        'no_transaction' => $transaction->no_transaction ?? 'N/A',
                        'account_number' => $transferAccount->account_no,
                        'account_name' => $transferAccount->name,
                        'debit' => 0,
                        'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                        'date' => $transaction->date,
                        'description' => $transaction->description,
                        'created_at' => $transaction->created_at
                    ],
                    [
                        'no_transaction' => $transaction->no_transaction ?? 'N/A',
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
        }


        if ($type === 'transaction_send' || empty($type)) {
            $transactionSends = Transaction_send::with(['transferAccount', 'depositAccount'])
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->get();

            foreach ($transactionSends as $transaction) {
                $transferAccount = $transaction->transferAccount;
                $depositAccount = $transaction->depositAccount;

                $transactionDetails[] = [
                    [
                        'no_transaction' => $transaction->no_transaction ?? 'N/A',
                        'account_number' => $transferAccount->account_no,
                        'account_name' => $transferAccount->name,
                        'debit' => 0,
                        'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                        'date' => $transaction->date,
                        'description' => $transaction->description,
                        'created_at' => $transaction->created_at
                    ],
                    [
                        'no_transaction' => $transaction->no_transaction ?? 'N/A',
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
        }


        // Logika untuk mengambil transaksi berdasarkan jenis transaksi
        if ($type === 'transaction_receive' || empty($type)) {
            $transactionReceives = Transaction_receive::with(['transferAccount', 'depositAccount'])
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->get();

            foreach ($transactionReceives as $transaction) {
                $transferAccount = $transaction->transferAccount;
                $depositAccount = $transaction->depositAccount;

                // Log debugging untuk memeriksa data
                Log::info('Transaction Receive Data:', [
                    'no_transaction' => $transaction->no_transaction,
                    'transfer_account' => $transferAccount ? $transferAccount->account_no : 'N/A',
                    'deposit_account' => $depositAccount ? $depositAccount->account_no : 'N/A'
                ]);

                $transactionDetails[] = [
                    [
                        'no_transaction' => $transaction->no_transaction ?? 'N/A',
                        'account_number' => $transferAccount->account_no,
                        'account_name' => $transferAccount->name,
                        'debit' => 0,
                        'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                        'date' => $transaction->date,
                        'description' => $transaction->description,
                        'created_at' => $transaction->created_at
                    ],
                    [
                        'no_transaction' => $transaction->no_transaction ?? 'N/A',
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
        }



        if ($type === 'invoice_supplier' || empty($type)) {
            $invoiceSuppliers = InvoiceSupplier::with(['transferAccount', 'depositAccount'])
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->get();

            foreach ($invoiceSuppliers as $invoice) {
                $transferAccount = $invoice->transferAccount;
                $depositAccount = $invoice->depositAccount;

                $transactionDetails[] = [
                    [
                        'no_transaction' => $invoice->no_invoice ?? 'N/A',
                        'account_number' => $transferAccount->account_no,
                        'account_name' => $transferAccount->name,
                        'debit' => 0,
                        'credit' => $invoice->amount > 0 ? $invoice->amount : 0,
                        'date' => $invoice->invoice_date,
                        'description' => $invoice->description,
                        'created_at' => $invoice->created_at
                    ],
                    [
                        'no_transaction' => $invoice->no_invoice ?? 'N/A',
                        'account_number' => $depositAccount->account_no,
                        'account_name' => $depositAccount->name,
                        'debit' => $invoice->amount > 0 ? $invoice->amount : 0,
                        'credit' => 0,
                        'date' => $invoice->date,
                        'description' => $invoice->description,
                        'created_at' => $invoice->created_at
                    ]
                ];
            }
        }


        if ($type === 'bill' || empty($type)) {
            $bills = Bill::with(['transferAccount', 'depositAccount'])
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('deadline_invoice', [$startDate, $endDate]);
                })
                ->get();

            foreach ($bills as $bill) {
                $transferAccount = $bill->transferAccount;
                $depositAccount = $bill->depositAccount;

                $transactionDetails[] = [
                    [
                        'no_transaction' => $bill->number_invoice ?? 'N/A',
                        'account_number' => $transferAccount->account_no,
                        'account_name' => $transferAccount->name,
                        'debit' => 0,
                        'credit' => $bill->amount > 0 ? $bill->amount : 0,
                        'date' => $bill->date,
                        'description' => $bill->description,
                        'created_at' => $bill->created_at
                    ],
                    [
                        'no_transaction' => $bill->number_invoice ?? 'N/A',
                        'account_number' => $depositAccount->account_no,
                        'account_name' => $depositAccount->name,
                        'debit' => $bill->amount > 0 ? $bill->amount : 0,
                        'credit' => 0,
                        'date' => $bill->date,
                        'description' => $bill->description,
                        'created_at' => $bill->created_at
                    ]
                ];
            }
        }

        // Store transaction details in session for export
        session(['transactionDetails' => $transactionDetails]);

        return view('components.journal.selected-detail', [
            'transactionDetails' => $transactionDetails,
            'selectedNoTransactions' => $transactionDetails,
        ]);
    }

    // public function showFilterJournalDetailpdf(Request $request)
    // {
    //     $selectedNoTransactions = $request->selectedNoTransactions ?? [];

    //     // Retrieve selected transactions based on no_transaction
    //     $transactionDetails = [];

    //     foreach ($selectedNoTransactions as $noTransaction) {
    //         $transaction = null;

    //         // Search the transaction across the three types
    //         $transaction = Transaction_transfer::with(['transferAccount', 'depositAccount'])->where('no_transaction', $noTransaction)->first();
    //         if (!$transaction) {
    //             $transaction = Transaction_send::with(['transferAccount', 'depositAccount'])->where('no_transaction', $noTransaction)->first();
    //         }
    //         if (!$transaction) {
    //             $transaction = Transaction_receive::with(['transferAccount', 'depositAccount'])->where('no_transaction', $noTransaction)->first();
    //         }

    //         if ($transaction) {
    //             $transferAccount = $transaction->transferAccount;
    //             $depositAccount = $transaction->depositAccount;

    //             $transactionDetails[] = [
    //                 'no_transaction' => $transaction->no_transaction ?? 'N/A',
    //                 'account_number' => $transferAccount->account_no,
    //                 'account_name' => $transferAccount->name,
    //                 'debit' => 0,
    //                 'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                 'date' => $transaction->date,
    //                 'description' => $transaction->description,
    //                 'created_at' => $transaction->created_at
    //             ];

    //             $transactionDetails[] = [
    //                 'no_transaction' => $transaction->no_transaction ?? 'N/A',
    //                 'account_number' => $depositAccount->account_no,
    //                 'account_name' => $depositAccount->name,
    //                 'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                 'credit' => 0,
    //                 'date' => $transaction->date,
    //                 'description' => $transaction->description,
    //                 'created_at' => $transaction->created_at
    //             ];
    //         }
    //     }

    //     $nameFormatPdf = Carbon::now()->format('YmdHis') . mt_rand(1000, 9999) . '_journal_detail.pdf';

    //     $pdf = app('dompdf.wrapper');
    //     $pdf->loadView(
    //         'components.journal.selected-detail-pdf',
    //         [
    //             'transactionDetails' => $transactionDetails,
    //             'selectedNoTransactions' => $selectedNoTransactions, // Tambahkan variabel ini
    //         ]
    //     )->setPaper('a4', 'landscape');

    //     return $pdf->stream($nameFormatPdf);
    // }

    public function showFilterJournalDetailpdf(Request $request)
    {
        $selectedNoTransactions = $request->selectedNoTransactions ?? [];
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $type = $request->input('type');
        $search = $request->input('search');
        $sort = $request->input('sort');
        $order = $request->input('order');

        // Log debugging
        Log::info('Request Data: ', $request->all());

        // Check for correct date format and parse dates
        if ($startDate) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        }
        if ($endDate) {
            $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
        }

        $transactionDetails = [];

        // Define variables as empty collections
        $transactionTransfers = collect();
        $transactionReceives = collect();
        $transactionSends = collect();
        $invoiceSuppliers = collect();
        $bills = collect();

        // Logika untuk mengambil transaksi berdasarkan jenis transaksi
        if ($type === 'transaction_transfer' || empty($type)) {
            $transactionTransfers = Transaction_transfer::with(['transferAccount', 'depositAccount'])
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->when($search, function ($query, $search) {
                    return $query->where('no_transaction', 'LIKE', "%{$search}%");
                })
                ->when($sort && $order, function ($query) use ($sort, $order) {
                    return $query->orderBy($sort, $order);
                })
                ->get();

            foreach ($transactionTransfers as $transaction) {
                $transferAccount = $transaction->transferAccount;
                $depositAccount = $transaction->depositAccount;

                $transactionDetails[] = [
                    'no_transaction' => $transaction->no_transaction ?? 'N/A',
                    'account_number' => $transferAccount->account_no,
                    'account_name' => $transferAccount->name,
                    'debit' => 0,
                    'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                    'date' => $transaction->date,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at
                ];

                $transactionDetails[] = [
                    'no_transaction' => $transaction->no_transaction ?? 'N/A',
                    'account_number' => $depositAccount->account_no,
                    'account_name' => $depositAccount->name,
                    'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
                    'credit' => 0,
                    'date' => $transaction->date,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at
                ];
            }
        }

        if ($type === 'transaction_send' || empty($type)) {
            $transactionSends = Transaction_send::with(['transferAccount', 'depositAccount'])
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->get();

            foreach ($transactionSends as $transaction) {
                $transferAccount = $transaction->transferAccount;
                $depositAccount = $transaction->depositAccount;

                $transactionDetails[] = [
                    'no_transaction' => $transaction->no_transaction ?? 'N/A',
                    'account_number' => $transferAccount->account_no,
                    'account_name' => $transferAccount->name,
                    'debit' => 0,
                    'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                    'date' => $transaction->date,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at
                ];

                $transactionDetails[] = [
                    'no_transaction' => $transaction->no_transaction ?? 'N/A',
                    'account_number' => $depositAccount->account_no,
                    'account_name' => $depositAccount->name,
                    'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
                    'credit' => 0,
                    'date' => $transaction->date,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at
                ];
            }
        }

        if ($type === 'transaction_receive' || empty($type)) {
            $transactionReceives = Transaction_receive::with(['transferAccount', 'depositAccount'])
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->get();

            foreach ($transactionReceives as $transaction) {
                $transferAccount = $transaction->transferAccount;
                $depositAccount = $transaction->depositAccount;

                $transactionDetails[] = [
                    'no_transaction' => $transaction->no_transaction ?? 'N/A',
                    'account_number' => $transferAccount->account_no,
                    'account_name' => $transferAccount->name,
                    'debit' => 0,
                    'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                    'date' => $transaction->date,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at
                ];

                $transactionDetails[] = [
                    'no_transaction' => $transaction->no_transaction ?? 'N/A',
                    'account_number' => $depositAccount->account_no,
                    'account_name' => $depositAccount->name,
                    'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
                    'credit' => 0,
                    'date' => $transaction->date,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at
                ];
            }
        }

        // Invoice Supplier
        if ($type === 'invoice_supplier' || empty($type)) {
            $invoiceSuppliers = InvoiceSupplier::with(['supplier', 'transferAccount', 'depositAccount'])
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->when($search, function ($query, $search) {
                    return $query->where('no_invoice', 'LIKE', "%{$search}%");
                })
                ->when($sort && $order, function ($query) use ($sort, $order) {
                    return $query->orderBy($sort, $order);
                })
                ->get();

            foreach ($invoiceSuppliers as $invoice) {
                // $supplier = $invoice->supplier;
                $transferAccount = $invoice->transferAccount;
                $depositAccount = $invoice->depositAccount;

                $transactionDetails[] = [
                    'no_transaction' => $invoice->no_invoice ?? 'N/A',
                    'account_number' => $transferAccount->account_no,
                    'account_name' => $transferAccount->name,
                    'debit' => 0,
                    'credit' => $invoice->amount > 0 ? $invoice->amount : 0,
                    'date' => $invoice->date,
                    'description' => $invoice->description,
                    'created_at' => $invoice->created_at
                ];

                $transactionDetails[] = [
                    'no_transaction' => $invoice->no_invoice ?? 'N/A',
                    'account_number' => $depositAccount->account_no,
                    'account_name' => $depositAccount->name,
                    'debit' => $invoice->amount > 0 ? $invoice->amount : 0,
                    'credit' => 0,
                    'date' => $invoice->date,
                    'description' => $invoice->description,
                    'created_at' => $invoice->created_at
                ];
            }
        }

        if ($type === 'bill' || empty($type)) {
            $bills = Bill::with(['transferAccount', 'depositAccount'])
                // ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                //     return $query->whereBetween('created_at', [$startDate, $endDate]);
                // })
                // ->when($search, function ($query, $search) {
                //     return $query->where('number_invoice', 'LIKE', "%{$search}%");
                // })
                ->get();

            foreach ($bills as $bill) {
                $transferAccount = $bill->transferAccount;
                $depositAccount = $bill->depositAccount;

                $transactionDetails[] = [
                    'no_transaction' => $bill->number_invoice ?? 'N/A',
                    'account_number' => $transferAccount->account_no,
                    'account_name' => $transferAccount->name,
                    'debit' => 0,
                    'credit' => $bill->amount > 0 ? $bill->amount : 0,
                    'date' => $bill->date,
                    'description' => $bill->description,
                    'created_at' => $bill->created_at
                ];

                $transactionDetails[] = [
                    'no_transaction' => $bill->number_invoice ?? 'N/A',
                    'account_number' => $depositAccount->account_no,
                    'account_name' => $depositAccount->name,
                    'debit' => $bill->amount > 0 ? $bill->amount : 0,
                    'credit' => 0,
                    'date' => $bill->date,
                    'description' => $bill->description,
                    'created_at' => $bill->created_at
                ];
            }
        }

        $allTransactions = $transactionTransfers->merge($transactionSends)->merge($transactionReceives)->merge($invoiceSuppliers)->merge($bills);

        // Load the PDF view with the transaction details
        $nameFormatPdf = Carbon::now()->format('YmdHis') . mt_rand(1000, 9999) . '_journal_detail.pdf';

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('components.journal.selected-detail-pdf', [
            'transactionDetails' => $transactionDetails,
            'selectedNoTransactions' => $allTransactions->pluck('no_transaction')->toArray(),

        ])->setPaper('a4', 'landscape');

        return $pdf->stream($nameFormatPdf);
    }


    // tampilan detail satu type 
    public function showJournalDetail(Request $request, $id, $type)
    {
        $selectedItems = $request->id ?? [];

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
                        'no_transaction' => $transferAccount->no_transaction,
                        'account_number' => $transferAccount->account_no,
                        'account_name' => $transferAccount->name,
                        'debit' => 0,
                        'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                        'date' => $transaction->date,
                        'description' => $transaction->description,
                        'created_at' => $transaction->created_at
                    ],
                    [
                        'no_transaction' => $depositAccount->no_transaction,
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
        } elseif ($type === 'invoice_supplier') {
            $transaction = InvoiceSupplier::with(['transferAccount', 'depositAccount', 'oldAccount', 'newAccount'])->find($id);

            if ($transaction) {
                $transferAccount = $transaction->transferAccount;
                $depositAccount = $transaction->depositAccount;

                $transactionDetails = [
                    [
                        'no_transaction' => $transaction->no_invoice ?? 'N/A',
                        'account_number' => $transferAccount->account_no,
                        'account_name' => $transferAccount->name,
                        'debit' => 0,
                        'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                        'date' => $transaction->date,
                        'description' => $transaction->description,
                        'created_at' => $transaction->created_at,
                        'old_account_number' => $transaction->oldAccount->account_no ?? 'N/A',
                        'new_account_number' => $transaction->newAccount->account_no ?? 'N/A'
                    ],
                    [
                        'no_transaction' => $transaction->no_invoice ?? 'N/A',
                        'account_number' => $depositAccount->account_no ?? "NULL",
                        'account_name' => $depositAccount->name ?? "NULL",
                        'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
                        'credit' => 0,
                        'date' => $transaction->date,
                        'description' => $transaction->description,
                        'created_at' => $transaction->created_at
                    ]
                ];
            }
        } elseif ($type === 'bill') {
            $transaction = Bill::with(['transferAccount', 'depositAccount', 'newAccount'])->find($id);

            $transferAccount = $transaction->transferAccount;
            $depositAccount = $transaction->depositAccount;

            $transactionDetails = [
                [
                    'no_transaction' => $transaction->number_invoice ?? 'N/A',
                    'account_number' => $transferAccount->account_no,
                    'account_name' => $transferAccount->name,
                    'debit' => 0,
                    'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                    'date' => $transaction->bill_date,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at
                ],
                [
                    'no_transaction' => $transaction->number_invoice ?? 'N/A',
                    'account_number' => $depositAccount->account_no,
                    'account_name' => $depositAccount->name,
                    'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
                    'credit' => 0,
                    'date' => $transaction->date,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                ]
            ];
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
                'selectedItems' => $selectedItems

            ]);
        } else {
            // Jika data tidak ditemukan, kembalikan ke halaman index dengan pesan error
            return redirect()->route('journal.index')->with('error', 'Transaction details not found.');
        }
    }


    // print pdf satu per satu 
    // public function generatePdfJournalDetail($id, $type)
    // {
    //     session()->flash('page', (object)[
    //         'page' => 'Journal',
    //         'child' => 'Journal Details'
    //     ]);

    //     try {
    //         // Variabel untuk menyimpan data detail transaksi
    //         $transactionDetails = [];

    //         // Sesuaikan pengecekan berdasarkan tipe transaksi
    //         if ($type === 'transaction_transfer') {
    //             $transaction = Transaction_transfer::find($id);

    //             if ($transaction) {
    //                 // Mengambil data transfer account
    //                 $transferAccount = $transaction->transferAccount;
    //                 // Mengambil data deposit account
    //                 $depositAccount = $transaction->depositAccount;

    //                 $transactionDetails = [
    //                     [
    //                         'account_number' => $transferAccount->account_no,
    //                         'account_name' => $transferAccount->name,
    //                         'debit' => 0,
    //                         'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                         'date' => $transaction->date,
    //                         'description' => $transaction->description,
    //                         'created_at' => $transaction->created_at
    //                     ],
    //                     [
    //                         'account_number' => $depositAccount->account_no,
    //                         'account_name' => $depositAccount->name,
    //                         'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                         'credit' => 0,
    //                         'date' => $transaction->date,
    //                         'description' => $transaction->description,
    //                         'created_at' => $transaction->created_at
    //                     ]
    //                 ];
    //             }
    //         } elseif ($type === 'transaction_send') {
    //             $transaction = Transaction_send::find($id);

    //             if ($transaction) {
    //                 $transferAccount = $transaction->transferAccount;
    //                 $depositAccount = $transaction->depositAccount;

    //                 $transactionDetails = [
    //                     [
    //                         'account_number' => $transferAccount->account_no,
    //                         'account_name' => $transferAccount->name,
    //                         'debit' => 0,
    //                         'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                         'date' => $transaction->date,
    //                         'description' => $transaction->description,
    //                         'created_at' => $transaction->created_at
    //                     ],
    //                     [
    //                         'account_number' => $depositAccount->account_no,
    //                         'account_name' => $depositAccount->name,
    //                         'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                         'credit' => 0,
    //                         'date' => $transaction->date,
    //                         'description' => $transaction->description,
    //                         'created_at' => $transaction->created_at
    //                     ]
    //                 ];
    //             }
    //         } elseif ($type === 'transaction_receive') {
    //             $transaction = Transaction_receive::find($id);

    //             if ($transaction) {
    //                 $transferAccount = $transaction->transferAccount;
    //                 $depositAccount = $transaction->depositAccount;

    //                 $transactionDetails = [
    //                     [
    //                         'account_number' => $transferAccount->account_no,
    //                         'account_name' => $transferAccount->name,
    //                         'debit' => 0,
    //                         'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                         'date' => $transaction->date,
    //                         'description' => $transaction->description,
    //                         'created_at' => $transaction->created_at
    //                     ],
    //                     [
    //                         'account_number' => $depositAccount->account_no,
    //                         'account_name' => $depositAccount->name,
    //                         'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                         'credit' => 0,
    //                         'date' => $transaction->date,
    //                         'description' => $transaction->description,
    //                         'created_at' => $transaction->created_at
    //                     ]
    //                 ];
    //             }
    //         } elseif ($type === 'invoice_supplier') {
    //             $transaction = InvoiceSupplier::find($id);

    //             if ($transaction) {
    //                 $transferAccount = $transaction->transferAccount;
    //                 $depositAccount = $transaction->depositAccount;

    //                 $transactionDetails = [
    //                     [
    //                         'no_transaction' => $transaction->no_invoice ?? 'N/A',
    //                         'account_number' => $transferAccount->account_no,
    //                         'account_name' => $transferAccount->name,
    //                         'debit' => 0,
    //                         'credit' => $transaction->amount > 0 ? $transaction->amount : 0 ,
    //                         'date' => $transaction->date,
    //                         'description' => $transaction->description,
    //                         'created_at' => $transaction->created_at
    //                     ],
    //                     [
    //                         'no_transaction' => $transaction->no_invoice ?? 'N/A',
    //                         'account_number' => $depositAccount->account_no,
    //                         'account_name' => $depositAccount->name,
    //                         'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                         'credit' => 0,
    //                         'date' => $transaction->date,
    //                         'description' => $transaction->description,
    //                         'created_at' => $transaction->created_at
    //                     ]
    //                 ];
    //             }
    //         } elseif ($type === 'bill') {
    //             $transaction = Bill::find($id);

    //             if ($transaction) {
    //                 $transferAccount = $transaction->transferAccount;
    //                 $depositAccount = $transaction->depositAccount;

    //                 $transactionDetails = [
    //                     [
    //                         'no_transaction' => $transaction->number_invoice ?? 'N/A',
    //                         'account_number' => $transferAccount->account_no,
    //                         'account_name' => $transferAccount->name,
    //                         'debit' => 0,
    //                         'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                         'date' => $transaction->deadline_invoice,
    //                         'description' => $transaction->description,
    //                         'created_at' => $transaction->created_at
    //                     ],
    //                     [
    //                         'no_transaction' => $transaction->number_invoice ?? 'N/A',
    //                         'account_number' => $depositAccount->account_no,
    //                         'account_name' => $depositAccount->name,
    //                         'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
    //                         'credit' => 0,
    //                         'date' => $transaction->deadline_invoice,
    //                         'description' => $transaction->description,
    //                         'created_at' => $transaction->created_at
    //                     ]
    //                 ];
    //             }
    //         } else {
    //             // Jika tipe transaksi tidak valid, kembalikan ke halaman index dengan pesan error
    //             return redirect()->route('journal.index')->with('error', 'Invalid transaction type.');
    //         }

    //         $nameFormatPdf = Carbon::now()->format('YmdHis') . mt_rand(1000, 9999) . '_journal_detail.pdf';

    //         $pdf = app('dompdf.wrapper');
    //         $pdf->loadView('components.journal.detail-pdf', [
    //             'transaction' => $transaction,
    //             'transactionDetails' => $transactionDetails,
    //             'type' => $type,
    //         ])->setPaper('a4', 'landscape');

    //         return $pdf->stream($nameFormatPdf);
    //     } catch (Exception $e) {
    //         return abort(500, 'Failed to fetch transaction details.');
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
            $transaction = null;

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
                            'no_transaction' => $transaction->no_transaction ?? 'N/A',
                            'account_number' => $transferAccount->account_no,
                            'account_name' => $transferAccount->name,
                            'debit' => 0,
                            'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ],
                        [
                            'no_transaction' => $transaction->no_transaction ?? 'N/A',
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
                            'no_transaction' => $transaction->no_transaction ?? 'N/A',
                            'account_number' => $transferAccount->account_no,
                            'account_name' => $transferAccount->name,
                            'debit' => 0,
                            'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ],
                        [
                            'no_transaction' => $transaction->no_transaction ?? 'N/A',
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
                            'no_transaction' => $transaction->no_transaction ?? 'N/A',
                            'account_number' => $transferAccount->account_no,
                            'account_name' => $transferAccount->name,
                            'debit' => 0,
                            'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ],
                        [
                            'no_transaction' => $transaction->no_transaction ?? 'N/A',
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
            } elseif ($type === 'invoice_supplier') {
                $transaction = InvoiceSupplier::find($id);

                if ($transaction) {
                    $transferAccount = $transaction->transferAccount;
                    $depositAccount = $transaction->depositAccount;

                    $transactionDetails = [
                        [
                            'no_transaction' => $transaction->no_invoice ?? 'N/A',
                            'account_number' => $transferAccount->account_no,
                            'account_name' => $transferAccount->name,
                            'debit' => 0,
                            'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'date' => $transaction->date,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ],
                        [
                            'no_transaction' => $transaction->no_invoice ?? 'N/A',
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
            } elseif ($type === 'bill') {
                $transaction = Bill::find($id);

                if ($transaction) {
                    $transferAccount = $transaction->transferAccount;
                    $depositAccount = $transaction->depositAccount;

                    $transactionDetails = [
                        [
                            'no_transaction' => $transaction->number_invoice ?? 'N/A',
                            'account_number' => $transferAccount->account_no,
                            'account_name' => $transferAccount->name,
                            'debit' => 0,
                            'credit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'date' => $transaction->deadline_invoice,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at
                        ],
                        [
                            'no_transaction' => $transaction->number_invoice ?? 'N/A',
                            'account_number' => $depositAccount->account_no,
                            'account_name' => $depositAccount->name,
                            'debit' => $transaction->amount > 0 ? $transaction->amount : 0,
                            'credit' => 0,
                            'date' => $transaction->deadline_invoice,
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
