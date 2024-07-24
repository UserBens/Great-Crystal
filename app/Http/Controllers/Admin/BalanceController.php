<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Accountnumber;
use App\Models\BalanceAccount;
use App\Models\Accountcategory;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class BalanceController extends Controller
{
    // public function indexBalance(Request $request)
    // {
    //     session()->flash('preloader', true);
    //     session()->flash('page', (object)[
    //         'page' => 'AccountNumber',
    //         'child' => 'Database Balance',
    //     ]);

    //     try {
    //         $form = (object) [
    //             'sort' => $request->sort ?? null,
    //             'order' => $request->order ?? 'desc',
    //             'search' => $request->search ?? null,
    //             'date' => $request->date ?? null,
    //         ];

    //         $query = Accountnumber::query();

    //         // Filter berdasarkan parameter pencarian
    //         if ($request->filled('search')) {
    //             $query->where(function ($q) use ($request) {
    //                 $q->where('name', 'LIKE', '%' . $request->search . '%')
    //                     ->orWhere('account_no', 'LIKE', '%' . $request->search . '%')
    //                     ->orWhere('amount', 'LIKE', '%' . $request->search . '%')
    //                     ->orWhere('created_at', 'LIKE', '%' . $request->search . '%');
    //             });
    //         }

    //         // Filter berdasarkan tanggal
    //         if ($request->filled('date')) {
    //             $searchDate = date('Y-m-d', strtotime($request->date));
    //             $query->whereDate('created_at', $searchDate);
    //         }

    //         // Mengatur urutan berdasarkan parameter yang dipilih
    //         if ($request->filled('sort') && $request->filled('order')) {
    //             $query->orderBy($request->sort, $request->order);
    //         } else {
    //             $query->orderBy('created_at', 'desc');
    //         }

    //         $data = $query->paginate(25);

    //         $categories = Accountcategory::all();

    //         return view('components.account.balance-index')->with('data', $data)->with('categories', $categories)->with('form', $form);
    //     } catch (Exception $err) {
    //         return dd($err);
    //     }
    // }






    public function indexBalance(Request $request)
    {
        session()->flash('preloader', true);
        session()->flash('page', (object) [
            'page' => 'AccountNumber',
            'child' => 'Database Balance',
        ]);

        try {
            $form = (object) [
                'sort' => $request->sort ?? 'id',
                'order' => $request->order ?? 'asc',
                'search' => $request->search ?? null,
                'date' => $request->date ?? null,
            ];

            $query = Accountnumber::query();

            // Filter berdasarkan parameter pencarian
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('account_no', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('amount', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('created_at', 'LIKE', '%' . $request->search . '%');
                });
            }

            // Filter berdasarkan tanggal konversi
            if ($request->filled('date')) {
                $searchDate = date('Y-m', strtotime($request->date)); // YYYY-MM
                $query->where('date', 'LIKE', $searchDate . '%'); // Use LIKE for pattern matching
            }

            // Mengatur urutan berdasarkan parameter yang dipilih
            if ($request->filled('sort') && $request->filled('order')) {
                $query->orderBy($request->sort, $request->order);
            } else {
                $query->orderBy('id', 'asc'); // Urutkan berdasarkan id
            }

            $data = $query->paginate(25);
            $categories = Accountcategory::all();

            return view('components.account.balance-index')
                ->with('data', $data)
                ->with('categories', $categories)
                ->with('form', $form);
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function saveBalances(Request $request)
    {
        // Manipulasi nilai debit dan kredit sebelum validasi
        $balances = $request->input('balances', []);
        foreach ($balances as $accountId => $balance) {
            $balances[$accountId]['debit'] = str_replace('.', '', str_replace('Rp', '', $balance['debit'] ?? '0'));
            $balances[$accountId]['credit'] = str_replace('.', '', str_replace('Rp', '', $balance['credit'] ?? '0'));
        }

        // Validasi data
        $validator = Validator::make($balances, [
            '*.debit' => 'nullable|numeric|min:0',
            '*.credit' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ubah format bulan dari YYYY-MM menjadi YYYY-MM-DD
        $searchDate = \Carbon\Carbon::parse($request->date)->format('Y-m-d'); // Periksa format ini

        DB::beginTransaction();

        try {
            foreach ($balances as $accountId => $balance) {
                $account = Accountnumber::find($accountId);
                if ($account) {
                    // Simpan hanya jika ada perubahan
                    if ($account->debit != $balance['debit'] || $account->credit != $balance['credit']) {
                        $account->debit = $balance['debit'];
                        $account->credit = $balance['credit'];
                        $account->date = $searchDate; // YYYY-MM-DD
                        $account->save();
                    }
                }
            }

            DB::commit();

            return redirect()->route('balance.index', [
                'sort' => 'id',
                'order' => 'asc',
            ])->with('success', 'Balances saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Tambahkan logging untuk detail kesalahan
            Log::error('Failed to save balances: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to save balances.');
        }
    }









    public function indexPostBalance(Request $request)
    {
        session()->flash('preloader', true);
        session()->flash('page', (object) [
            'page' => 'AccountNumber',
            'child' => 'Database Post Balance',
        ]);

        try {
            $form = (object) [
                'sort' => $request->sort ?? 'id',
                'order' => $request->order ?? 'asc',
                'search' => $request->search ?? null,
                'date' => $request->date ?? null,
            ];

            $query = Accountnumber::query();

            // Filter berdasarkan parameter pencarian
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('account_no', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('amount', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('created_at', 'LIKE', '%' . $request->search . '%');
                });
            }

            // Filter berdasarkan tanggal konversi
            if ($request->filled('date')) {
                $searchDate = date('Y-m', strtotime($request->date)); // YYYY-MM
                $query->where('date', 'LIKE', $searchDate . '%'); // Use LIKE for pattern matching
            }

            // Mengatur urutan berdasarkan parameter yang dipilih
            if ($request->filled('sort') && $request->filled('order')) {
                $query->orderBy($request->sort, $request->order);
            } else {
                $query->orderBy('id', 'asc'); // Urutkan berdasarkan id
            }

            $data = $query->paginate(25);
            $categories = Accountcategory::all();

            return view('components.account.posting-index')
                ->with('data', $data)
                ->with('categories', $categories)
                ->with('form', $form);
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function postBalances(Request $request, $id)
    {
        $request->validate([
            'posted_date' => 'required|date_format:Y-m'
        ]);

        $postedDate = \Carbon\Carbon::parse($request->input('posted_date') . '-01')->format('Y-m-d');

        DB::beginTransaction();

        try {
            $accountnumber = Accountnumber::findOrFail($id);
            $accountnumber->posted_date = $postedDate;
            $accountnumber->posted = true;
            $accountnumber->save();

            DB::commit();

            return redirect()->route('balance-post.index')->with('success', 'Balance posted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to post balance: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to post balance.');
        }
    }









    public function unpostBalances(Request $request)
    {
        $date = $request->input('date');
        $formattedDate = \Carbon\Carbon::parse($date)->format('Y-m'); // Format YYYY-MM

        DB::beginTransaction();

        try {
            // Update kolom posted_date dan posted untuk semua akun yang memenuhi syarat
            $updatedRows = Accountnumber::where('posted_date', 'LIKE', $formattedDate . '%')
                ->update([
                    'posted_date' => null,
                    'posted' => false
                ]);

            Log::info('Number of accounts updated: ' . $updatedRows);

            DB::commit();

            return redirect()->route('balance-post.index')
                ->with('success', 'Balances unposted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to unpost balances: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to unpost balances.');
        }
    }





    // public function indexBalance(Request $request)
    // {
    //     session()->flash('preloader', true);
    //     session()->flash('page', (object) [
    //         'page' => 'AccountNumber',
    //         'child' => 'Database Balance',
    //     ]);

    //     try {
    //         $form = (object) [
    //             'sort' => $request->sort ?? null,
    //             'order' => $request->order ?? 'desc',
    //             'search' => $request->search ?? null,
    //             'date' => $request->date ?? null,
    //         ];

    //         $query = Accountnumber::query();

    //         // Filter berdasarkan parameter pencarian
    //         if ($request->filled('search')) {
    //             $query->where(function ($q) use ($request) {
    //                 $q->where('name', 'LIKE', '%' . $request->search . '%')
    //                     ->orWhere('account_no', 'LIKE', '%' . $request->search . '%')
    //                     ->orWhere('amount', 'LIKE', '%' . $request->search . '%')
    //                     ->orWhere('created_at', 'LIKE', '%' . $request->search . '%');
    //             });
    //         }

    //         // Filter berdasarkan tanggal konversi
    //         if ($request->filled('date')) {
    //             $searchDate = date('Y-m-d', strtotime($request->date));
    //             $query->where('month', $searchDate);
    //         } else {
    //             // Jika tidak ada tanggal konversi yang dipilih, tampilkan data kosong
    //             $query->where('month', null);
    //         }


    //         // Mengatur urutan berdasarkan parameter yang dipilih
    //         if ($request->filled('sort') && $request->filled('order')) {
    //             $query->orderBy($request->sort, $request->order);
    //         } else {
    //             $query->orderBy('created_at', 'desc');
    //         }

    //         $data = $query->paginate(25);
    //         $categories = Accountcategory::all();

    //         return view('components.account.balance-index')
    //             ->with('data', $data)
    //             ->with('categories', $categories)
    //             ->with('form', $form);
    //     } catch (Exception $err) {
    //         return dd($err);
    //     }
    // }

    // public function saveBalances(Request $request)
    // {
    //     // Manipulasi nilai debit dan kredit sebelum validasi
    //     $balances = $request->input('balances', []);
    //     foreach ($balances as $accountId => $balance) {
    //         $balances[$accountId]['debit'] = str_replace('.', '', str_replace('Rp', '', $balance['debit'] ?? '0'));
    //         $balances[$accountId]['credit'] = str_replace('.', '', str_replace('Rp', '', $balance['credit'] ?? '0'));
    //     }

    //     // Validasi data
    //     $validator = Validator::make($balances, [
    //         '*.debit' => 'nullable|numeric|min:0',
    //         '*.credit' => 'nullable|numeric|min:0',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $searchDate = date('Y-m', strtotime($request->date));


    //     DB::beginTransaction();

    //     try {
    //         foreach ($balances as $accountId => $balance) {
    //             $account = Accountnumber::find($accountId);
    //             if ($account) {
    //                 // Simpan hanya jika ada perubahan
    //                 if ($account->debit != $balance['debit'] || $account->credit != $balance['credit']) {
    //                     $account->debit = $balance['debit'];
    //                     $account->credit = $balance['credit'];
    //                     // Pastikan untuk menyimpan bulan yang benar di sini
    //                     $account->month = $searchDate; // $searchDate harus di-set sebelumnya dengan format yang benar
    //                     $account->save();
    //                 }
    //             }
    //         }


    //         DB::commit();

    //         return redirect()->back()->with('success', 'Balances saved successfully.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Failed to save balances.');
    //     }
    // }


}
