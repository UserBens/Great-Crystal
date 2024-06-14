<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Models\Accountnumber;
use App\Models\Accountcategory;
use App\Http\Controllers\Controller;
use App\Models\InvoiceSupplier;
use App\Models\Transaction_send;
use Carbon\Carbon;

class InvoiceSupplierController extends Controller
{
    public function indexsupplier(Request $request)
    {
        session()->flash('page', (object)[
            'page' => 'Supplier',
            'child' => 'database Invoice Supplier',
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
            return view('components.supplier.index', compact('data', 'form'));
        } catch (Exception $err) {
            // Menampilkan pesan error jika terjadi kesalahan
            return dd($err);
        }
    }

    public function createSupplier()
    {
        return view('components.supplier.create');
    }

    public function storeSupplier(Request $request)
    {
        $request->validate([
            'no_invoice' => 'required',
            'supplier_name' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'nota' => 'required',
            'deadline_invoice' => 'required',
        ]);

        $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

        InvoiceSupplier::create([
            'no_invoice' => $request->no_invoice,
            'supplier_name' => $request->supplier_name,
            'amount' => $request->amount,
            'date' => $date,
            'nota' => $request->nota,
            'deadline_invoice' => $request->no_invoice,

        ]);

        return redirect()->route('components.supplier.index')->with('success', 'Invoice Supplier Created Successfully!');
    }
}
