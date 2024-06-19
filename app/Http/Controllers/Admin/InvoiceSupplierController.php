<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Accountnumber;
use App\Models\Accountcategory;
use App\Models\InvoiceSupplier;
use App\Models\Transaction_send;
use App\Http\Controllers\Controller;
use App\Models\TransactionSendSupplier;

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
            $query = InvoiceSupplier::query();

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
        $suppliers = TransactionSendSupplier::all();

        return view('components.supplier.create', [
            'suppliers' => $suppliers,
        ]);
    }

    public function storeSupplier(Request $request)
    {
        $request->validate([
            'no_invoice' => 'required',
            'supplier_name' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date_format:Y-m-d',
            'nota' => 'required',
            'deadline_invoice' => 'required|date_format:Y-m-d',
        ]);

        $invoice = new InvoiceSupplier();
        $invoice->no_invoice = $request->no_invoice;
        $invoice->supplier_name = $request->supplier_name;
        $invoice->amount = $request->amount;
        $invoice->date = Carbon::parse($request->date)->format('Y-m-d');
        $invoice->nota = $request->nota;
        $invoice->deadline_invoice = Carbon::parse($request->deadline_invoice)->format('Y-m-d');
        $invoice->save();

        return redirect()->route('supplier.index')->with('success', 'Invoice Supplier berhasil dibuat!');
    }


    public function destroySupplier($id)
    {
        try {
            // Cari data transaksi transfer berdasarkan ID
            $invoiceSupplier = InvoiceSupplier::findOrFail($id);

            // Hapus data transaksi transfer
            $invoiceSupplier->delete();

            return redirect()->back()->with('success', 'Invoice Supplier Deleted Successfully!');
        } catch (Exception $err) {
            return dd($err);
        }
    }
}
