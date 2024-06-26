<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\SupplierData;
use Illuminate\Http\Request;
use App\Models\Accountnumber;
use App\Models\Accountcategory;
use App\Models\InvoiceSupplier;
use App\Models\Transaction_send;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\TransactionSendSupplier;

class InvoiceSupplierController extends Controller
{
    // Punya Invoice Supplier
    public function indexInvoiceSupplier(Request $request)
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

            $invoices = InvoiceSupplier::all();


            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where('supplier_name', 'LIKE', $searchTerm)
                    ->orWhere('no_invoice', 'LIKE', $searchTerm)
                    ->orWhere('nota', 'LIKE', $searchTerm)
                    ->orWhere('amount', 'LIKE', $searchTerm)
                    ->orWhere('date', 'LIKE', $searchTerm);
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
            return view('components.supplier.invoice.index', compact('data', 'form', 'invoices'));
        } catch (Exception $err) {
            // Menampilkan pesan error jika terjadi kesalahan
            return dd($err);
        }
    }

    public function uploadProofOfPayment(Request $request, $id)
    {
        try {
            // Ambil invoice berdasarkan ID
            $invoice = InvoiceSupplier::findOrFail($id);

            // Validasi data request
            $request->validate([
                'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string',
                'payment_status' => 'required|in:Paid,Not Yet',
            ]);

            // Handle file upload
            if ($request->hasFile('image_path')) {
                $image = $request->file('image_path');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);

                // Update invoice supplier data
                $invoice->update([
                    'image_path' => $imageName,
                    'payment_status' => $request->payment_status,
                    'description' => $request->description,
                ]);

                // Debugging log
                Log::info('Invoice updated: ', $invoice->toArray());

                return redirect()->route('invoice-supplier.index')->with('success', 'Proof of payment uploaded successfully.');
            } else {
                return back()->withErrors(['error' => 'Failed to upload proof of payment. Please try again.']);
            }
        } catch (\Exception $err) {
            Log::error('Error uploading proof of payment: ' . $err->getMessage());
            return back()->withErrors(['error' => 'Failed to upload proof of payment. Please try again.']);
        }
    }


    public function createInvoiceSupplier()
    {
        $supplierDatas = SupplierData::all();

        return view('components.supplier.invoice.create', [
            'supplierDatas' => $supplierDatas,
        ]);
    }

    public function storeInvoiceSupplier(Request $request)
    {
        $request->validate([
            'no_invoice' => 'required',
            'supplier_name' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date_format:Y-m-d',
            'nota' => 'required',
            'deadline_invoice' => 'required|date_format:Y-m-d',
            'ppn_status' => 'required'
        ]);

        $amount = $request->amount;
        $pph_percentage = 0;

        // Calculate the amount after PPH deduction
        if ($request->ppn_status === '2%') {
            $pph_percentage = 2;
            $amount *= 0.98; // Deduct 2%
        } else if ($request->ppn_status === '15%') {
            $pph_percentage = 15;
            $amount *= 0.85; // Deduct 15%
        }

        $invoice = new InvoiceSupplier();
        $invoice->no_invoice = $request->no_invoice;
        $invoice->supplier_name = $request->supplier_name;
        $invoice->amount = $amount;
        $invoice->pph_percentage = $pph_percentage;
        $invoice->date = Carbon::parse($request->date)->format('Y-m-d');
        $invoice->nota = $request->nota;
        $invoice->deadline_invoice = Carbon::parse($request->deadline_invoice)->format('Y-m-d');
        $invoice->save();

        return redirect()->route('invoice-supplier.index')->with('success', 'Invoice Supplier Created Successfully!');
    }



    public function destroyInvoiceSupplier($id)
    {
        try {
            $invoice = InvoiceSupplier::findOrFail($id);

            $invoice->delete();

            return response()->json(['message' => 'Invoice supplier deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete invoice supplier.']);
        }
    }



    // Punya Supplier
    public function indexsupplier(Request $request)
    {
        session()->flash('page', (object)[
            'page' => 'Supplier',
            'child' => 'database Supplier Data',
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
            $query = SupplierData::query();

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
                if ($request->sort === 'created_at') {
                    $query->orderBy('created_at', $request->order);
                } else {
                    $query->orderBy($request->sort, $request->order);
                }
            }

            // Filter data berdasarkan tanggal
            if ($request->filled('created_at')) {
                $searchDate = date('Y-m-d', strtotime($request->date));
                $query->whereDate('created_at', $searchDate);
            }

            // Memuat data dengan pagination
            $data = $query->paginate(10);

            // Menampilkan view dengan data dan form
            return view('components.supplier.data.index', compact('data', 'form'));
        } catch (Exception $err) {
            // Menampilkan pesan error jika terjadi kesalahan
            return dd($err);
        }
    }

    public function createSupplier()
    {
        return view('components.supplier.data.create');
    }

    public function storeSupplier(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'instansi_name' => 'required',
            'no_rek' => 'required|numeric',
        ]);

        SupplierData::create([
            'name' => $request->name,
            'instansi_name' => $request->instansi_name,
            'no_rek' => $request->no_rek,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Invoice Supplier Created successfully!');
    }

    public function destroySupplier($id)
    {
        try {
            $invoice = SupplierData::findOrFail($id);

            $invoice->delete();

            return response()->json(['message' => 'Supplier Data deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete Supplier Data.']);
        }
    }
}
