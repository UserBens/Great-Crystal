<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSendSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transaction_send_suppliers')->insert([
            [
                'supplier_name' => 'maulana',
                'supplier_role' => 'supplier',
                'created_at' => now(),
                'updated_at' => now(),
               
            ],
            [
                'supplier_name' => 'kurniawan',
                'supplier_role' => 'supplier',
                'created_at' => now(),
                'updated_at' => now(),
               
            ],
            [
                'supplier_name' => 'robi',
                'supplier_role' => 'supplier',
                'created_at' => now(),
                'updated_at' => now(),
               
            ],

        ]);
    }
}
