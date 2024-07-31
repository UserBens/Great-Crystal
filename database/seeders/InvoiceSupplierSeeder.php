<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvoiceSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed supplier_data table first
        $suppliers = [];
        for ($i = 1; $i <= 20; $i++) {
            $suppliers[] = [
                'name' => "Supplier $i",
                'no_telp' => '08123456789' . $i, // Example phone number
                'email' => "supplier$i@example.com",
                'address' => "Address of Supplier $i",
                'city' => "City $i",
                'province' => "Province $i",
                'post_code' => '12345' . $i, // Example post code
                'accountnumber' => '1234567890' . $i, // Example account number
                'accountnumber_holders_name' => "Holder Name $i",
                'bank_name' => "Bank $i",
                'description' => "Description of Supplier $i",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('supplier_data')->insert($suppliers);

        // Get all supplier ids
        $supplierIds = DB::table('supplier_data')->pluck('id')->toArray();

        // Seed invoice_suppliers table
        $invoices = [];
        $accountIds = DB::table('accountnumbers')->pluck('id'); // Get all account ids

        for ($i = 1; $i <= 20; $i++) {
            $invoices[] = [
                'no_invoice' => 'INV-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'supplier_id' => $supplierIds[$i - 1], // Use the correct supplier id
                'amount' => rand(100000, 1000000),
                'pph' => rand(5000, 150000), // Adjusted to match the new column 'pph'
                'pph_percentage' => rand(5, 15),
                'date' => Carbon::now()->subDays(rand(0, 365))->format('Y-m-d H:i:s'),
                'nota' => 'Nota ' . $i,
                'deadline_invoice' => Carbon::now()->addDays(rand(0, 30))->format('Y-m-d H:i:s'),
                'payment_status' => rand(0, 1) ? 'Paid' : 'Not Yet',
                'description' => 'Description for invoice ' . $i,
                'payment_method' => 'Cash', // Added to match the new column 'payment_method'
                'transfer_account_id' => $accountIds->random(), // Randomly select one transfer account ID
                'image_invoice' => 'images/invoice_' . $i . '.jpg',
                'image_proof' => 'images/proof_' . $i . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('invoice_suppliers')->insert($invoices);
    }
}
