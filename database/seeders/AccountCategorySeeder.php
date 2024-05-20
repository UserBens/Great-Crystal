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
            'KAS & BANK',
            'AKUN PIUTANG',
            'PERSEDIAAN',
            'AKTIVA LANCAR LAINNYA',
            'AKTIVA TETAP',
            'DEPRESIASI & AMORTISASI',
            'AKTIVA LAINNYA',
            'AKUN HUTANG',
            'KEWAJIBAN LANCAR LAINNYA',
            'KEWAJIBAN JANGKA PANJANG',
            'EKUITAS',
            'PENDAPATAN',
            'HARGA POKOK PENJUALAN',
            'BEBAN',
            'PENDAPATAN LAINNYA',
            'BEBAN LAINNYA'
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
