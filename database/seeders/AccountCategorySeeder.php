<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccountCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'ASET LANCAR',
            'PAJAK DIBAYAR DIMUKA',
            'PERSEDIAAN',
            'ASET TIDAK LANCAR',
            'KEWAJIBAN LANCAR',
            'KEWAJIBAN JANGKA PANJANG',
            'EKUITAS',
            'PENDAPATAN',
            'BEBAN POKOK',
            'BEBAN PENJUALAN',
            'BEBAN UMUM & ADMINISTRASI',
            'PENDAPATAN (BEBAN) LAIN-LAIN',
            // 'Kas & Bank',
            // 'Akun Piutang',
            // 'Aktiva Lancar Lainnya',
            // 'Aktiva Tetap',
            // 'Deprestasi & Amortisasi',
            // 'Aktiva Lainnya',
            // 'Akun Hutang',
            // 'Harga Pokok Penjualan',
            // 'Pendapatan Lainnya',
            // 'Depresiasi & Amortisasi',

            // 'Cash & Bank',
            // 'Accounts Receivable',
            // 'Inventory',
            // 'Other Current Assets',
            // 'Fixed Assets',
            // 'Depreciation & Amortization',
            // 'Other Assets',
            // 'Accounts Payable',
            // 'Other Current Liabilities',
            // 'Long-term Liabilities',
            // 'Equity',
            // 'Revenue',
            // 'Cost of Goods Sold (COGS)',
            // 'Expenses',
            // 'Other Income',
            // 'Other Expenses',
        ];

        foreach ($categories as $category) {
            DB::table('accountcategories')->insert([
                'category_name' => $category,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
