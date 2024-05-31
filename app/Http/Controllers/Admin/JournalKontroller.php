<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;
use Carbon\Carbon;
use App\Models\Cash;
use Barryvdh\DomPDF\PDF;
use App\Models\Accountnumber;
use App\Models\Accountcategory;
use App\Models\Transaction_send;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction_receive;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction_transfer;
use Illuminate\Pagination\LengthAwarePaginator;

class JournalKontroller extends Controller
{
    public function indexJournal(Request $request)
    {
        session()->flash('page', (object) [
            'page' => 'Journal',
            'child' => 'database Journal',
        ]);

        $form = (object) [
            'sort' => $request->sort ?? 'date', // Default sort by date
            'order' => $request->order ?? 'desc', // Default descending
            'status' => $request->status ?? null,
            'search' => $request->search ?? null,
            'type' => $request->type ?? null,
            'date' => $request->date ?? null,
        ];

        $selectedItems = $request->id ?? [];

        $selectedTypes = $request->type ?? [];


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

        $unionQuery = $transactionTransfers->unionAll($transactionSends)->unionAll($transactionReceives);

        $query = DB::table(DB::raw("({$unionQuery->toSql()}) as sub"))
            ->mergeBindings($unionQuery);

        if ($form->type) {
            $query->where('type', $form->type);
        }

        if ($form->date) {
            $query->whereDate('date', $form->date);
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
        }

        $allData = $query->paginate(10);

        return view('components.journal.index', compact('allData', 'form', 'selectedItems', 'selectedTypes'));
    }


    public function showJournalDetail(Request $request, $id, $type)
    {
        $selectedItems = $request->id ?? [];

        // try {
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
        // } catch (Exception $e) {
        //     // Tangani kesalahan dengan mengembalikan ke halaman index dengan pesan error
        //     // return redirect()->route('journal.index')->with('error', 'Failed to fetch transaction details.');
        // }
    }


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

    public function showSelectedJournalDetail(Request $request)
    {
        $selectedNoTransactions = $request->no_transaction ?? [];

        // Debugging data yang diterima
        Log::info('Selected No Transactions: ' . print_r($selectedNoTransactions, true));

        if (empty($selectedNoTransactions)) {
            return redirect()->route('journal.index')->with('error', 'No items selected.');
        }

        $transactionDetails = [];

        foreach ($selectedNoTransactions as $noTransaction) {
            $transaction = null;

            // Search the transaction across the three types
            $transaction = Transaction_transfer::with(['transferAccount', 'depositAccount'])->where('no_transaction', $noTransaction)->first();
            if (!$transaction) {
                $transaction = Transaction_send::with(['transferAccount', 'depositAccount'])->where('no_transaction', $noTransaction)->first();
            }
            if (!$transaction) {
                $transaction = Transaction_receive::with(['transferAccount', 'depositAccount'])->where('no_transaction', $noTransaction)->first();
            }

            if ($transaction) {
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

        // Debugging data yang akan dikirim ke view
        Log::info('Transaction Details: ' . print_r($transactionDetails, true));

        return view('components.journal.selected-detail', [
            'transactionDetails' => $transactionDetails
        ]);
    }

}
