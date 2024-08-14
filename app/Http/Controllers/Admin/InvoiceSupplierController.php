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
use App\Models\AccountnumberChanges;
use App\Models\TransactionSendSupplier;
use Illuminate\Support\Facades\File;


class InvoiceSupplierController extends Controller
{
    public function indexInvoiceSupplier(Request $request)
    {
        session()->flash('preloader', true);
        session()->flash('page', (object)[
            'page' => 'Supplier',
            'child' => 'database Invoice Supplier',
        ]);

        try {
            // Inisialisasi objek form dengan nilai default
            $form = (object) [
                'search' => $request->search ?? null,
                'sort' => $request->sort ?? null,
                'status' => $request->status ?? null,
                'date' => $request->date ?? null,
                'type' => $request->type ?? null,
                'order' => $request->order ?? null,
            ];

            // Query data berdasarkan parameter pencarian yang diberikan
            $query = InvoiceSupplier::with(['transferAccount', 'depositAccount', 'supplier']);

            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where('no_invoice', 'LIKE', $searchTerm)
                    ->orWhere('nota', 'LIKE', $searchTerm)
                    ->orWhere('amount', 'LIKE', $searchTerm)
                    ->orWhere('date', 'LIKE', $searchTerm)
                    ->orWhereHas('supplier', function ($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', $searchTerm);
                    });
            }

            // Filter data berdasarkan tanggal
            if ($request->filled('date')) {
                $searchDate = date('Y-m-d', strtotime($request->date));
                $query->whereDate('date', $searchDate);
            }

            // Mengatur urutan berdasarkan parameter yang dipilih
            if ($request->filled('sort')) {
                if ($request->sort === 'oldest') {
                    $query->orderBy('date', 'asc');
                } elseif ($request->sort === 'newest') {
                    $query->orderBy('date', 'desc');
                }
            }

            // Filter data berdasarkan status pembayaran
            if ($request->filled('status')) {
                if ($request->status === 'Paid') {
                    $query->where('payment_status', 'Paid');
                } elseif ($request->status === 'Not Yet') {
                    $query->where('payment_status', 'Not Yet');
                }
            }

            // Memuat data dengan pagination dan menambahkan parameter filter ke URL paginasi
            $data = $query->paginate(25)->appends([
                'search' => $request->search,
                'sort' => $request->sort,
                'status' => $request->status,
                'date' => $request->date,
            ]);

            // Menampilkan view dengan data dan form
            return view('components.supplier.invoice.index', compact('data', 'form'));
        } catch (Exception $err) {
            // Menampilkan pesan error jika terjadi kesalahan
            return dd($err);
        }
    }


    public function createInvoiceSupplier()
    {
        $supplierDatas = SupplierData::all();
        $accountNumbers = Accountnumber::all();
        $accountCategory = Accountcategory::all();

        return view('components.supplier.invoice.create', [
            'supplierDatas' => $supplierDatas,
            'accountNumbers' => $accountNumbers,
            'accountCategory' => $accountCategory,
        ]);
    }


    // kodingan awal store invoice supplier berjalan
    // public function storeInvoiceSupplier(Request $request)
    // {
    //     try {
    //         // Validasi input
    //         $request->validate([
    //             'no_invoice' => 'required|unique:invoice_suppliers,no_invoice',
    //             'supplier_id' => 'required|exists:supplier_data,id',
    //             'amount' => 'required|numeric',
    //             'date' => 'required|date_format:Y-m-d',
    //             'nota' => 'required',
    //             'deadline_invoice' => 'required|date_format:Y-m-d',
    //             // 'pph' => 'required|integer',
    //             // 'pph_percentage' => 'required|numeric|min:0|max:100',
    //         ]);

    //         $amount = $request->amount;
    //         $pph_percentage = $request->pph_percentage;

    //         // Mengurangi amount berdasarkan persentase PPH
    //         $amount -= ($amount * ($pph_percentage / 100));

    //         // Handle file upload
    //         $imageName = null;
    //         if ($request->hasFile('image_invoice')) {
    //             $image = $request->file('image_invoice');
    //             $imageName = time() . '.' . $image->getClientOriginalExtension();
    //             $image->move(public_path('uploads'), $imageName);
    //         }

    //         // Simpan data invoice
    //         $invoice = new InvoiceSupplier();
    //         $invoice->no_invoice = $request->no_invoice;
    //         $invoice->supplier_id = $request->supplier_id;
    //         $invoice->amount = $amount;
    //         $invoice->pph = $request->pph;
    //         $invoice->pph_percentage = $pph_percentage;
    //         $invoice->date = Carbon::parse($request->date)->format('Y-m-d');
    //         $invoice->nota = $request->nota;
    //         $invoice->deadline_invoice = Carbon::parse($request->deadline_invoice)->format('Y-m-d');
    //         $invoice->payment_status = 'Not Yet';
    //         $invoice->payment_method = 'Cash';
    //         $invoice->description = $request->description;
    //         $invoice->image_invoice = $imageName;
    //         $invoice->save();

    //         return redirect()->route('invoice-supplier.index')->with('success', 'Invoice Supplier Created Successfully!');
    //     } catch (\Illuminate\Database\QueryException $ex) {
    //         $errorMessage = 'Database error occurred. Please try again later.';
    //         if ($ex->errorInfo[1] == 1062) {
    //             $errorMessage = "The account name or number already exists.";
    //         }
    //         return redirect()->back()->withInput()->with('error', $errorMessage);
    //     }
    // }

    // testing kodingan invoice supplier gpt
    public function storeInvoiceSupplier(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'no_invoice' => 'required|unique:invoice_suppliers,no_invoice',
                'supplier_id' => 'required|exists:supplier_data,id',
                'amount' => 'required|numeric',
                'date' => 'required|date_format:Y-m-d',
                'nota' => 'required',
                'deadline_invoice' => 'required|date_format:Y-m-d',
                'transfer_account_id' => 'required',
                'deposit_account_id' => 'required',               
            ]);

            $amount = $request->amount;
            $pph_percentage = $request->pph_percentage;

            // Mengurangi amount berdasarkan persentase PPH jika ada
            if ($pph_percentage) {
                $amount -= ($amount * ($pph_percentage / 100));
            }

            // Handle file upload
            $imageName = null;
            if ($request->hasFile('image_invoice')) {
                $image = $request->file('image_invoice');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);
            }

            // Simpan data invoice
            $invoice = new InvoiceSupplier();
            $invoice->no_invoice = $request->no_invoice;
            $invoice->supplier_id = $request->supplier_id;
            $invoice->amount = $amount;
            $invoice->pph = $request->pph;
            $invoice->pph_percentage = $pph_percentage;
            $invoice->date = Carbon::parse($request->date)->format('Y-m-d');
            $invoice->nota = $request->nota;
            $invoice->deadline_invoice = Carbon::parse($request->deadline_invoice)->format('Y-m-d');
            $invoice->transfer_account_id = $request->transfer_account_id;
            $invoice->deposit_account_id = $request->deposit_account_id;
            $invoice->payment_status = 'Not Yet';
            $invoice->payment_method = 'Cash';
            $invoice->description = $request->description;
            $invoice->image_invoice = $imageName;
            $invoice->save();

            // Jika ada PPH, lakukan pemotongan
            if ($request->pph && $pph_percentage) {
                $pphAccount = AccountNumber::where('name', 'like', "%{$request->pph}%")->first();
                if ($pphAccount) {
                    // Simpan potongan PPH ke akun terkait
                    $pphAmount = ($request->amount * ($pph_percentage / 100));
                    // Update balance untuk akun PPH
                    // Misalnya, tambahkan ke saldo debit atau kredit tergantung tipe akun
                    // Anda bisa menyesuaikan sesuai logika dan struktur data Anda
                    $pphAccount->amount += $pphAmount; // Misalnya tambahkan ke saldo
                    $pphAccount->save();
                }
            }

            return redirect()->route('invoice-supplier.index')->with('success', 'Invoice Supplier Created Successfully!');
        } catch (\Illuminate\Database\QueryException $ex) {
            $errorMessage = 'Database error occurred. Please try again later.';
            if ($ex->errorInfo[1] == 1062) {
                $errorMessage = "The account name or number already exists.";
            }
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }


    public function storeSupplierAtInvoice(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'no_telp' => 'required',          
        ]);

        try {
            SupplierData::create([
                'name' => $request->name,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'post_code' => $request->post_code,
                'accountnumber' => $request->accountnumber,
                'accountnumber_holders_name' => $request->accountnumber_holders_name,
                'bank_name' => $request->bank_name,
                'description' => $request->description,
            ]);

            return redirect()->route('create-invoice-supplier.create')->with('success', 'Supplier Data Created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create Supplier Data: ' . $e->getMessage()]);
        }
    }


    public function uploadProofOfPaymentView($id)
    {
        $invoice = InvoiceSupplier::findOrFail($id);
        $accountNumbers = Accountnumber::all();
        $accountCategory = Accountcategory::all();


        return view('components.supplier.invoice.upload-proof', compact('invoice', 'accountNumbers', 'accountCategory'));
    }


    // update history coa
    public function uploadProofOfPayment(Request $request, $id)
    {
        try {
            // Ambil invoice berdasarkan ID
            $invoice = InvoiceSupplier::findOrFail($id);

            // Validasi data request
            $request->validate([
                'image_proof' => 'required',
                'payment_status' => 'required|in:Paid,Not Yet',
                'payment_method' => 'required|in:Cash,Bank',
                'transfer_account_id' => 'required',
            ]);

            // Handle perubahan transfer_account_id
            $oldTransferAccountId = $invoice->transfer_account_id;
            $newTransferAccountId = $request->input('transfer_account_id');

            if ($oldTransferAccountId != $newTransferAccountId) {
                // Simpan riwayat perubahan pada invoice supplier
                $invoice->update([
                    'old_transfer_account_id' => $oldTransferAccountId,
                    'new_transfer_account_id' => $newTransferAccountId,
                ]);
            }

            // Handle file upload
            if ($request->hasFile('image_proof')) {
                $image = $request->file('image_proof');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);

                // Update invoice supplier data
                $invoice->update([
                    'image_proof' => $imageName,
                    'payment_status' => $request->payment_status,
                    'payment_method' => $request->payment_method,
                    'description' => $request->description,
                    'transfer_account_id' => $newTransferAccountId, // Menggunakan nilai transfer_account_id baru
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


    public function storeAccountatUploadProof(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'name' => 'required',
                'account_no' => ['required', 'regex:/^\d{3}\.\d{3}$/'],
                'account_category_id' => 'required',
                // 'description' => 'required',
            ]);

            // Buat data akun baru
            Accountnumber::create([
                'name' => $request->name,
                'account_no' => $request->account_no,
                'account_category_id' => $request->account_category_id,
                'description' => $request->description,
            ]);

            // Redirect ke halaman indeks akun dengan pesan sukses
            return redirect()->route('invoice-supplier.upload-proof', $request->invoice_id)
                ->with('success', 'Account Number created successfully!');
        } catch (\Illuminate\Database\QueryException $ex) {
            $errorMessage = 'Database error occurred. Please try again later.';
            if ($ex->errorInfo[1] == 1062) {
                $errorMessage = "The account name or number already exists.";
            }
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }


    public function storeAccountatCreateInvoice(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'name' => 'required',
                'account_no' => ['required', 'regex:/^\d{3}\.\d{3}$/'],
                'account_category_id' => 'required',
                // 'description' => 'required',
            ]);

            // Buat data akun baru
            Accountnumber::create([
                'name' => $request->name,
                'account_no' => $request->account_no,
                'account_category_id' => $request->account_category_id,
                'description' => $request->description,
            ]);

            // Redirect ke halaman indeks akun dengan pesan sukses
            return redirect()->route('create-invoice-supplier.create')
                ->with('success', 'Account Number created successfully!');
        } catch (\Illuminate\Database\QueryException $ex) {
            $errorMessage = 'Database error occurred. Please try again later.';
            if ($ex->errorInfo[1] == 1062) {
                $errorMessage = "The account name or number already exists.";
            }
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }


    public function destroyInvoiceSupplier($id)
    {
        try {
            $invoice = InvoiceSupplier::findOrFail($id);

            // Hapus file gambar image_invoice jika ada
            if ($invoice->image_invoice) {
                $imagePath = public_path('uploads/' . $invoice->image_invoice);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            // Hapus file gambar image_proof jika ada
            if ($invoice->image_proof) {
                $proofPath = public_path('uploads/' . $invoice->image_proof);
                if (File::exists($proofPath)) {
                    File::delete($proofPath);
                }
            }

            // Hapus data invoice dari database
            $invoice->delete();

            return response()->json(['message' => 'Invoice supplier deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete invoice supplier.']);
        }
    }


    // Punya Supplier
    public function indexsupplier(Request $request)
    {
        session()->flash('preloader', true);
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
                'created_at' => $request->created_at ?? null,
            ];

            // Query data berdasarkan parameter pencarian yang diberikan
            $query = SupplierData::query();

            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where('name', 'LIKE', $searchTerm)
                    ->orWhere('email', 'LIKE', $searchTerm)
                    ->orWhere('no_telp', 'LIKE', $searchTerm)
                    ->orWhere('created_at', 'LIKE', $searchTerm);
            }

            // Filter data berdasarkan tanggal
            if ($request->filled('created_at')) {
                $searchDate = date('Y-m-d', strtotime($request->created_at));
                $query->whereDate('created_at', $searchDate);
            }

            // Mengatur urutan berdasarkan parameter yang dipilih
            if ($request->filled('sort')) {
                if ($request->sort === 'oldest') {
                    $query->orderBy('created_at', 'asc');
                } elseif ($request->sort === 'newest') {
                    $query->orderBy('created_at', 'desc');
                }
            }

            // Memuat data dengan pagination
            $data = $query->paginate(15);

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
            'no_telp' => 'required',            
        ]);

        try {
            SupplierData::create([
                'name' => $request->name,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'post_code' => $request->post_code,
                'accountnumber' => $request->accountnumber,
                'accountnumber_holders_name' => $request->accountnumber_holders_name,
                'bank_name' => $request->bank_name,
                'description' => $request->description,
            ]);

            return redirect()->route('supplier.index')->with('success', 'Supplier Data Created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create Supplier Data: ' . $e->getMessage()]);
        }
    }

    public function destroySupplier($id)
    {
        try {
            $suppliers = SupplierData::findOrFail($id);

            $suppliers->delete();

            return response()->json(['message' => 'Supplier Data deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete Supplier Data.']);
        }
    }

    public function viewupdateSupplier($id)
    {
        $suppliers = SupplierData::findOrFail($id);

        return view('components.supplier.data.edit-supplier', [
            'suppliers' => $suppliers,
        ]);
    }

    public function updateSupplier(Request $request, $id)
    {
        $suppliers = SupplierData::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'no_telp' => 'required',           
        ]);

        try {
            $suppliers->update([
                'name' => $request->name,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'post_code' => $request->post_code,
                'accountnumber' => $request->accountnumber,
                'accountnumber_holders_name' => $request->accountnumber_holders_name,
                'bank_name' => $request->bank_name,
                'description' => $request->description,
            ]);

            return redirect()->route('supplier.index')->with('success', 'Supplier Data Created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create Supplier Data: ' . $e->getMessage()]);
        }
    }
}
