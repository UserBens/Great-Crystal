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
        for ($i = 1; $i <= 10; $i++) {
            $suppliers[] = [
                'name' => "Supplier $i",
                'instansi_name' => "Instansi $i",
                'no_rek' => '1234567890' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('supplier_data')->insert($suppliers);

        // Seed invoice_suppliers table
        $invoices = [];
        for ($i = 1; $i <= 10; $i++) {
            $invoices[] = [
                'no_invoice' => 'INV-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'supplier_name' => "Supplier $i",
                'amount' => rand(100000, 1000000),
                'pph_percentage' => rand(5, 15),
                'date' => Carbon::now()->subDays(rand(0, 365))->format('Y-m-d H:i:s'),
                'nota' => 'Nota ' . $i,
                'deadline_invoice' => Carbon::now()->addDays(rand(0, 30))->format('Y-m-d H:i:s'),
                'payment_status' => rand(0, 1) ? 'Paid' : 'Not Yet',
                'description' => 'Description for invoice ' . $i,
                'image_path' => 'images/invoice_' . $i . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('invoice_suppliers')->insert($invoices);
    }
}
