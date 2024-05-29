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
    //         'date' => $request->date ?? null,
    //     ];

    //     try {
    //         $transferdata = Transaction_transfer::select(
    //             'transaction_transfers.*',
    //             'a1.account_no AS transfer_account_no',
    //             'a1.name AS transfer_account_name',
    //             'a2.account_no AS deposit_account_no',
    //             'a2.name AS deposit_account_name',
    //             DB::raw("'transfer' as type")
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

    //         $senddata = Transaction_send::select(
    //             'transaction_sends.*',
    //             'a1.account_no AS transfer_account_no',
    //             'a1.name AS transfer_account_name',
    //             'a2.account_no AS deposit_account_no',
    //             'a2.name AS deposit_account_name',
    //             DB::raw("'send' as type")
    //         )
    //             ->leftJoin('accountnumbers as a1', 'a1.id', '=', 'transaction_sends.transfer_account_id')
    //             ->leftJoin('accountnumbers as a2', 'a2.id', '=', 'transaction_sends.deposit_account_id')
    //             ->where(function ($query) use ($request) {
    //                 if ($request->has('search')) {
    //                     $query->where('transaction_sends.no_transaction', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('a1.account_no', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('a1.name', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('transaction_sends.amount', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('transaction_sends.description', 'LIKE', '%' . $request->search . '%');
    //                 }
    //             });

    //         $receivedata = Transaction_receive::select(
    //             'transaction_receives.*',
    //             'a1.account_no AS transfer_account_no',
    //             'a1.name AS transfer_account_name',
    //             'a2.account_no AS deposit_account_no',
    //             'a2.name AS deposit_account_name',
    //             DB::raw("'receive' as type")
    //         )
    //             ->leftJoin('accountnumbers as a1', 'a1.id', '=', 'transaction_receives.transfer_account_id')
    //             ->leftJoin('accountnumbers as a2', 'a2.id', '=', 'transaction_receives.deposit_account_id')
    //             ->where(function ($query) use ($request) {
    //                 if ($request->has('search')) {
    //                     $query->where('transaction_receives.no_transaction', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('a1.account_no', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('a1.name', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('transaction_receives.amount', 'LIKE', '%' . $request->search . '%')
    //                         ->orWhere('transaction_receives.description', 'LIKE', '%' . $request->search . '%');
    //                 }
    //             });

    //         // Filter data by date
    //         if ($request->filled('date')) {
    //             $searchDate = date('Y-m-d', strtotime($request->date));
    //             $transferdata->whereDate('transaction_transfers.date', $searchDate);
    //             $senddata->whereDate('transaction_sends.date', $searchDate);
    //             $receivedata->whereDate('transaction_receives.date', $searchDate);
    //         }

    //         if ($request->filled('sort')) {
    //             // Get order from request
    //             $order = $request->order;
    //             $transferdata->orderBy($request->sort, $order);
    //             $senddata->orderBy($request->sort, $order);
    //             $receivedata->orderBy($request->sort, $order);
    //         } else {
    //             // Default sort by date
    //             $transferdata->orderBy('date', $form->order);
    //             $senddata->orderBy('date', $form->order);
    //             $receivedata->orderBy('date', $form->order);
    //         }

    //         // Ambil data lebih banyak dari setiap jenis transaksi
    //         $transfer = $transferdata->take(2)->get();
    //         $send = $senddata->take(2)->get();
    //         $receive = $receivedata->take(2)->get();

    //         // Combine results
    //         $combinedData = collect([]);

    //         // Ambil 2 data transfer
    //         $combinedData = $combinedData->merge($transfer->take(2));

    //         // Ambil 2 data send
    //         $combinedData = $combinedData->merge($send->take(2));

    //         // Ambil 2 data receive
    //         $combinedData = $combinedData->merge($receive->take(2));

    //         // Sort combined results
    //         if ($form->sort) {
    //             $combinedData = $combinedData->sortBy($form->sort, SORT_REGULAR, $form->order === 'desc');
    //         }

    //         // Paginate combined results
    //         $perPage = 10;
    //         $currentPage = LengthAwarePaginator::resolveCurrentPage();

    //         $path = $request->url(); // Menggunakan URL saat ini sebagai jalur

    //         // Ubah menjadi seperti ini
    //         $paginatedData = new LengthAwarePaginator(
    //             $combinedData->forPage($currentPage, $perPage),
    //             $combinedData->count(),
    //             $perPage,
    //             $currentPage,
    //             [
    //                 'path' => $path,
    //                 'pageName' => 'page',
    //             ]
    //         );

    //         return view('components.journal.index', [
    //             'transfer' => $transfer,
    //             'send' => $send,
    //             'receive' => $receive,
    //             'allData' => $paginatedData,
    //             'form' => $form,
    //         ]);
    //     } catch (Exception $err) {
    //         return dd($err);
    //     }
    // }

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
    //         'date' => $request->date ?? null,
    //     ];

    //     $query = DB::table('transaction_transfers')
    //         ->select('transaction_transfers.id', 'transaction_transfers.no_transaction', 'transaction_transfers.transfer_account_id', 'transaction_transfers.deposit_account_id', 'transaction_transfers.amount', 'transaction_transfers.date', 'transaction_transfers.created_at', DB::raw("'transaction_transfer' as type"))
    //         ->union(
    //             DB::table('transaction_sends')
    //                 ->select('transaction_sends.id', 'transaction_sends.no_transaction', 'transaction_sends.transfer_account_id', 'transaction_sends.deposit_account_id', 'transaction_sends.amount', 'transaction_sends.date', 'transaction_sends.created_at', DB::raw("'transaction_send' as type"))
    //         )
    //         ->union(
    //             DB::table('transaction_receives')
    //                 ->select('transaction_receives.id', 'transaction_receives.no_transaction', 'transaction_receives.transfer_account_id', 'transaction_receives.deposit_account_id', 'transaction_receives.amount', 'transaction_receives.date', 'transaction_receives.created_at', DB::raw("'transaction_receive' as type"))
    //         );

    //     if ($form->sort && in_array($form->sort, ['date'])) {
    //         $query = $query->orderBy($form->sort, $form->order);
    //     }

    //     $allData = $query->paginate(10);

    //     return view('components.journal.index', compact('allData', 'form'));
    // }

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



    public function indexJournal(Request $request)
    {
        session()->flash('page', (object) [
            'page' => 'Journal',
            'child' => 'database Journal',
        ]);

        $selectedItems = $request->id ?? [];


        $form = (object) [
            'sort' => $request->sort ?? 'date', // Default sort by date
            'order' => $request->order ?? 'desc', // Default descending
            'status' => $request->status ?? null,
            'search' => $request->search ?? null,
            'type' => $request->type ?? null,
            'date' => $request->date ?? null,
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

        return view('components.journal.index', compact('allData', 'form', 'selectedItems'));
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
        $selectedItems = $request->id ?? [];
        $selectedTypes = $request->type ?? [];

        if (empty($selectedItems)) {
            return redirect()->route('journal.index')->with('error', 'No items selected.');
        }

        $transactionDetails = [];

        foreach ($selectedItems as $index => $id) {
            $transaction = null;
            $type = $selectedTypes[$index]; // Ensure this comes from the form

            if ($type === 'transaction_transfer') {
                $transaction = Transaction_transfer::find($id);
            } elseif ($type === 'transaction_send') {
                $transaction = Transaction_send::find($id);
            } elseif ($type === 'transaction_receive') {
                $transaction = Transaction_receive::find($id);
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

        return view('components.journal.selected-detail', [
            'transactionDetails' => $transactionDetails
        ]);
    }


}
