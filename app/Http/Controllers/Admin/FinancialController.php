<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Bill;
use App\Models\Expenditure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InvoiceSupplier;

class FinancialController extends Controller
{

    public function indexIncome()
    {
        session()->flash('preloader', true);
        session()->flash('page', (object)[
            'page' => 'Financial',
            'child' => 'database income',
        ]);

        // $bills = Bill::where('paidOf', true)->get()->all();
        $bills = Bill::where('paidOf', true)->paginate(10);

        $totalPaid = Bill::where('paidOf', true)->sum('amount');

        return view('components.financial.income-index', [
            'totalpaid' => $totalPaid,
            'bills' => $bills,
        ]);
    }


    public function indexExpenditure(Request $request)
    {
        $totalExpenditure = Expenditure::sum('amount_spent');

        session()->flash('preloader', true);
        session()->flash('page', (object)[
            'page' => 'Financial',
            'child' => 'database expense',
        ]);

        // $bills = Bill::where('paidOf', true)->get()->all();
        $invoiceSuppliers = InvoiceSupplier::paginate(25);
        $totalAmountInvoiceSupplier = InvoiceSupplier::sum('amount'); // Mengambil total pengeluaran dari field amount


        // $totalPaid = Bill::where('paidOf', true)->sum('amount');

        return view('components.financial.expenditure-index', [
            // 'totalpaid' => $totalPaid,
            'invoiceSuppliers' => $invoiceSuppliers,
            'totalAmountInvoiceSupplier' => $totalAmountInvoiceSupplier,
        ]);
    }


    // public function createExpenditure()
    // {
    //     return view('components.expenditure.create');
    // }


    // public function storeExpenditure(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'type' => 'required',
    //         'spent_at' => 'required|date_format:d/m/Y',
    //         'amount_spent' => 'required|numeric', // Ubah aturan validasi menjadi 'numeric'
    //         'description' => 'required',
    //     ]);

    //     // Konversi format tanggal menggunakan Carbon
    //     $spent_at = Carbon::createFromFormat('d/m/Y', $request->spent_at)->format('Y-m-d');

    //     // Simpan data pengeluaran ke dalam database
    //     Expenditure::create([
    //         'spent_at' => $spent_at,
    //         'amount_spent' => $request->amount_spent,
    //         'description' => $request->description,
    //         'type' => $request->type,
    //     ]);

    //     // Redirect ke halaman indeks pengeluaran dengan pesan sukses
    //     return redirect()->route('expenditure.index')->with('success', 'Expenditure created successfully!');
    // }

    // public function editExpenditure($id)
    // {
    //     $expenditure = Expenditure::findOrFail($id);
    //     return view('components.expenditure.edit', ['expenditure' => $expenditure]);
    // }

    // public function updateExpenditure(Request $request, $id)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'type' => 'required',
    //         'spent_at' => 'required|date_format:d/m/Y',
    //         'amount_spent' => 'required|numeric', // Ubah aturan validasi menjadi 'numeric'
    //         'description' => 'required',
    //     ]);

    //     // Temukan data yang akan diupdate
    //     $expenditure = Expenditure::findOrFail($id);

    //     // Konversi format tanggal menggunakan Carbon
    //     $spent_at = Carbon::createFromFormat('d/m/Y', $request->spent_at)->format('Y-m-d');

    //     // Update data pengeluaran
    //     $expenditure->update([
    //         'spent_at' => $spent_at,
    //         'amount_spent' => $request->amount_spent,
    //         'description' => $request->description,
    //         'type' => $request->type,
    //     ]);

    //     // Redirect ke halaman indeks pengeluaran dengan pesan sukses
    //     return redirect()->route('expenditure.index')->with('success', 'Expenditure updated successfully!');
    // }


    // // public function destroyExpenditure($id)
    // // {
    // //     // Temukan data yang akan dihapus
    // //     $expenditure = Expenditure::findOrFail($id);

    // //     // Simpan nilai total pengeluaran sebelum penghapusan
    // //     $totalExpenditureBeforeDeletion = Expenditure::sum('amount_spent');

    // //     // Lakukan soft delete
    // //     $expenditure->delete();

    // //     // Hitung total pengeluaran setelah penghapusan
    // //     $totalExpenditureAfterDeletion = Expenditure::sum('amount_spent');

    // //     // Kurangi jumlah pengeluaran yang dihapus dari total pengeluaran sebelumnya
    // //     $totalExpenditure = $totalExpenditureBeforeDeletion - $expenditure->amount_spent;

    // //     // Simpan total pengeluaran yang baru
    // //     // Misalnya, Anda ingin menyimpannya dalam session
    // //     session()->put('totalExpenditure', $totalExpenditure);

    // //     // Redirect ke halaman indeks pengeluaran dengan pesan sukses
    // //     return redirect()->route('expenditure.index')->with('success', 'Expenditure deleted successfully!');
    // // }

    // public function destroyExpenditure($id)
    // {
    //     try {
    //         // Temukan data yang akan dihapus
    //         $expenditure = Expenditure::findOrFail($id);

    //         // Simpan nilai total pengeluaran sebelum penghapusan
    //         $totalExpenditureBeforeDeletion = Expenditure::sum('amount_spent');

    //         // Lakukan soft delete
    //         $expenditure->delete();

    //         // Hitung total pengeluaran setelah penghapusan
    //         $totalExpenditureAfterDeletion = Expenditure::sum('amount_spent');

    //         // Kurangi jumlah pengeluaran yang dihapus dari total pengeluaran sebelumnya
    //         $totalExpenditure = $totalExpenditureBeforeDeletion - $expenditure->amount_spent;

    //         // Simpan total pengeluaran yang baru
    //         session()->put('totalExpenditure', $totalExpenditure);

    //         // Kembalikan respons JSON dengan pesan sukses
    //         return response()->json(['message' => 'Expenditure deleted successfully.']);
    //     } catch (\Exception $e) {
    //         // Tangani pengecualian jika terjadi kesalahan
    //         return response()->json(['error' => 'Failed to delete Expenditure.'], 500);
    //     }
    // }
}
