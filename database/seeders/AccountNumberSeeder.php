<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccountNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = DB::table('accountcategories')->pluck('id', 'category_name');

        $accounts = [
            ['name' => 'Kas', 'account_no' => '1-10001',  'account_category_id' => 'Kas & Bank', 'amount' => 4000000.00, 'description' => 'Kas'],
            ['name' => 'Rekening Bank', 'account_no' => '1-10002', 'account_category_id' => 'Kas & Bank', 'amount' => -4000000.00, 'description' => 'Rekening Bank'],
            ['name' => 'Kas Di Mesin Kasir', 'account_no' => '1-10003', 'account_category_id' => 'Kas & Bank', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Giro', 'account_no' => '1-10004', 'account_category_id' => 'Kas & Bank', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Piutang Usaha', 'account_no' => '1-10100',  'account_category_id' => 'Akun Piutang', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Piutang Belum Ditagih', 'account_no' => '1-10101',  'account_category_id' => 'Akun Piutang', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Cadangan Kerugian Piutang', 'account_no' => '1-10102',  'account_category_id' => 'Akun Piutang', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Persediaan Barang Makanan', 'account_no' => '1-10200',  'account_category_id' => 'Persediaan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Persediaan Barang Minuman', 'account_no' => '1-10201',  'account_category_id' => 'Persediaan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Piutang Lainnya', 'account_no' => '1-10300',  'account_category_id' => 'Aktiva Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Piutang Karyawan', 'account_no' => '1-10301',  'account_category_id' => 'Aktiva Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Dana Belum Disetor', 'account_no' => '1-10400',  'account_category_id' => 'Aktiva Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Aset Lancar Lainnya', 'account_no' => '1-10401',  'account_category_id' => 'Aktiva Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Dibayar Dimuka', 'account_no' => '1-10402',  'account_category_id' => 'Aktiva Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Uang Muka', 'account_no' => '1-10403',  'account_category_id' => 'Aktiva Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'PPN Masukan', 'account_no' => '1-10500',  'account_category_id' => 'Aktiva Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pajak Dibayar Di Muka - PPh 22', 'account_no' => '1-10501',  'account_category_id' => 'Aktiva Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pajak Dibayar Di Muka - PPh 23', 'account_no' => '1-10502',  'account_category_id' => 'Aktiva Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pajak Dibayar Di Muka - PPh 25', 'account_no' => '1-10503',  'account_category_id' => 'Aktiva Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Aset Tetap - Tanah', 'account_no' => '1-10700',  'account_category_id' => 'Aktiva Tetap', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Aset Tetap - Bangunan', 'account_no' => '1-10701',  'account_category_id' => 'Aktiva Tetap', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Aset Tetap - Building Improvements', 'account_no' => '1-10702',  'account_category_id' => 'Aktiva Tetap', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Aset Tetap - Kendaraan', 'account_no' => '1-10703',  'account_category_id' => 'Aktiva Tetap', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Aset Tetap - Mesin & Peralatan', 'account_no' => '1-10704',  'account_category_id' => 'Aktiva Tetap', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Aset Tetap - Perlengkapan Kantor', 'account_no' => '1-10705',  'account_category_id' => 'Aktiva Tetap', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Aset Tetap - Aset Sewa Guna Usaha', 'account_no' => '1-10706',  'account_category_id' => 'Aktiva Tetap', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Aset Tak Berwujud', 'account_no' => '1-10707',  'account_category_id' => 'Aktiva Tetap', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Akumulasi Penyusutan - Bangunan', 'account_no' => '1-10751',  'account_category_id' => 'Depresiasi & Amortisasi', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Akumulasi Penyusutan - Building Improvements', 'account_no' => '1-10752',  'account_category_id' => 'Depresiasi & Amortisasi', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Akumulasi penyusutan - Kendaraan', 'account_no' => '1-10753',  'account_category_id' => 'Depresiasi & Amortisasi', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Akumulasi Penyusutan - Mesin & Peralatan', 'account_no' => '1-10754',  'account_category_id' => 'Depresiasi & Amortisasi', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Akumulasi Penyusutan - Peralatan Kantor', 'account_no' => '1-10755',  'account_category_id' => 'Depresiasi & Amortisasi', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Akumulasi Penyusutan - Aset Sewa Guna Usaha', 'account_no' => '1-10756',  'account_category_id' => 'Depresiasi & Amortisasi', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Akumulasi Amortisasi', 'account_no' => '1-10757',  'account_category_id' => 'Depresiasi & Amortisasi', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Investasi', 'account_no' => '1-10800',  'account_category_id' => 'Aktiva Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Usaha', 'account_no' => '2-20100',  'account_category_id' => 'Akun Hutang', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Belum Ditagih', 'account_no' => '2-20101',  'account_category_id' => 'Akun Hutang', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Lain Lain', 'account_no' => '2-20200',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Gaji', 'account_no' => '2-20201',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Deviden', 'account_no' => '2-20202',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pendapatan Diterima Di Muka', 'account_no' => '2-20203',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Konsinyasi', 'account_no' => '2-20205',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Sarana Kantor Terhutang', 'account_no' => '2-20301',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Bunga Terhutang', 'account_no' => '2-20302',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Terhutang Lainnya', 'account_no' => '2-20399',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Bank', 'account_no' => '2-20400',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'PPN Keluaran', 'account_no' => '2-20500',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Pajak - PPh 21', 'account_no' => '2-20501',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Pajak - PPh 22', 'account_no' => '2-20502',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Pajak - PPh 23', 'account_no' => '2-20503',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Pajak - PPh 29', 'account_no' => '2-20504',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Pajak', 'account_no' => '2-20599',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hutang Dari Pemegang Saham', 'account_no' => '2-20600',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Kewajiban Lancar Lainnya', 'account_no' => '2-20601',  'account_category_id' => 'Kewajiban Lancar Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Kewajiban Manfaat Karyawan', 'account_no' => '2-20700',  'account_category_id' => 'Kewajiban Jangka Panjang', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Modal Saham', 'account_no' => '3-30000',  'account_category_id' => 'Ekuitas', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Tambahan Modal Disetor', 'account_no' => '3-30001',  'account_category_id' => 'Ekuitas', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Laba Ditahan', 'account_no' => '3-30100',  'account_category_id' => 'Ekuitas', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Deviden', 'account_no' => '3-30200',  'account_category_id' => 'Ekuitas', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pendapatan Komprehensif Lainnya', 'account_no' => '3-30300',  'account_category_id' => 'Ekuitas', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Ekuitas Saldo Awal', 'account_no' => '3-30999',  'account_category_id' => 'Ekuitas', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pendapatan Makanan', 'account_no' => '4-40000',  'account_category_id' => 'Pendapatan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pendapatan Minuman', 'account_no' => '4-40001',  'account_category_id' => 'Pendapatan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pendapatan Katering', 'account_no' => '4-40002',  'account_category_id' => 'Pendapatan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Diskon Penjualan', 'account_no' => '4-40100',  'account_category_id' => 'Pendapatan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Retur Penjualan', 'account_no' => '4-40200',  'account_category_id' => 'Pendapatan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pendapatan Belum Ditagih', 'account_no' => '4-40201',  'account_category_id' => 'Pendapatan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Beban Pokok Pendapatan Makanan', 'account_no' => '5-50000',  'account_category_id' => 'Harga Pokok Penjualan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Beban Pokok Pendapatan Minuman', 'account_no' => '5-50001',  'account_category_id' => 'Harga Pokok Penjualan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Beban Pokok Pendapatan Waste', 'account_no' => '5-50002',  'account_category_id' => 'Harga Pokok Penjualan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Diskon Pembelian', 'account_no' => '5-50100',  'account_category_id' => 'Harga Pokok Penjualan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Retur Pembelian', 'account_no' => '5-50200',  'account_category_id' => 'Harga Pokok Penjualan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pengiriman & Pengangkutan', 'account_no' => '5-50300',  'account_category_id' => 'Harga Pokok Penjualan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Impor', 'account_no' => '5-50400',  'account_category_id' => 'Harga Pokok Penjualan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Produksi', 'account_no' => '5-50500',  'account_category_id' => 'Harga Pokok Penjualan', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Penjualan', 'account_no' => '6-60000',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Iklan & Promosi', 'account_no' => '6-60001',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Komisi & Fee', 'account_no' => '6-60002',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Bensin, Tol dan Parkir - Penjualan', 'account_no' => '6-60003',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Perjalanan Dinas - Penjualan', 'account_no' => '6-60004',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Komunikasi - Penjualan', 'account_no' => '6-60005',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Marketing Lainnya', 'account_no' => '6-60006',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Seragam Pegawai', 'account_no' => '6-60007',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Kemitraan', 'account_no' => '6-60008',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Royalti', 'account_no' => '6-60009',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Umum & Administratif', 'account_no' => '6-60100',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Gaji', 'account_no' => '6-60101',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Upah', 'account_no' => '6-60102',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Makanan & Transportasi', 'account_no' => '6-60103',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Lembur', 'account_no' => '6-60104',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pengobatan', 'account_no' => '6-60105',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'THR & Bonus', 'account_no' => '6-60106',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Jamsostek', 'account_no' => '6-60107',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Insentif', 'account_no' => '6-60108',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pesangon', 'account_no' => '6-60109',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Manfaat dan Tunjangan Lain', 'account_no' => '6-60110',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Donasi', 'account_no' => '6-60200',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Hiburan', 'account_no' => '6-60201',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Bensin, Tol dan Parkir - Umum', 'account_no' => '6-60202',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Perbaikan & Pemeliharaan', 'account_no' => '6-60203',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Perjalanan Dinas - Umum', 'account_no' => '6-60204',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Makanan', 'account_no' => '6-60205',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Komunikasi Umum', 'account_no' => '6-60206',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Iuran & Langganan', 'account_no' => '6-60207',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Asuransi', 'account_no' => '6-60208',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Legal & Profesional', 'account_no' => '6-60209',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Beban Manfaat Karyawan', 'account_no' => '6-60210',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Sarana Kantor', 'account_no' => '6-60211',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pelatihan & Pengembangan', 'account_no' => '6-60212',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Beban Piutang Tak Tertagih', 'account_no' => '6-60213',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pajak dan Perizinan', 'account_no' => '6-60214',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Denda', 'account_no' => '6-60215',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Listrik', 'account_no' => '6-60217',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Air', 'account_no' => '6-60218',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Sistem Kasir', 'account_no' => '6-60219',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'IPL', 'account_no' => '6-60220',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Langganan Software', 'account_no' => '6-60221',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Beban Kantor', 'account_no' => '6-60300',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Alat Tulis Kantor & Printing', 'account_no' => '6-60301',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Bea Materai', 'account_no' => '6-60302',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Keamanan dan Kebersihan', 'account_no' => '6-60303',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Supplies dan Material', 'account_no' => '6-60304',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pemborong', 'account_no' => '6-60305',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Sewa - Bangunan', 'account_no' => '6-60400',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Sewa - Kendaraan', 'account_no' => '6-60401',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Sewa - Operasional', 'account_no' => '6-60402',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Biaya Sewa - Lain - Lain', 'account_no' => '6-60403',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Penyusutan - Bangunan', 'account_no' => '6-60500',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Penyusutan - Perbaikan Bangunan', 'account_no' => '6-60501',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Penyusutan - Kendaraan', 'account_no' => '6-60502',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Penyusutan - Mesin & Peralatan', 'account_no' => '6-60503',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Penyusutan - Peralatan Kantor', 'account_no' => '6-60504',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Penyusutan - Aset Sewa Guna Usaha', 'account_no' => '6-60599',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pengeluaran Barang Rusak', 'account_no' => '6-60216',  'account_category_id' => 'Beban', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pendapatan Bunga - Bank', 'account_no' => '7-70000',  'account_category_id' => 'Pendapatan Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pendapatan Bunga - Deposito', 'account_no' => '7-70001',  'account_category_id' => 'Pendapatan Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pendapatan Komisi - Barang Konsinyasi', 'account_no' => '7-70002',  'account_category_id' => 'Pendapatan Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pembulatan', 'account_no' => '7-70003',  'account_category_id' => 'Pendapatan Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Pendapatan Lain - lain', 'account_no' => '7-70099',  'account_category_id' => 'Pendapatan Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Beban Bunga', 'account_no' => '8-80000',  'account_category_id' => 'Beban Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Provinsi', 'account_no' => '8-80001',  'account_category_id' => 'Beban Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => '(Laba)/Rugi Pelepasan Aset Tetap', 'account_no' => '8-80002',  'account_category_id' => 'Beban Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Penyesuaian Persediaan', 'account_no' => '8-80100',  'account_category_id' => 'Beban Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Beban Lain - lain', 'account_no' => '8-80999',  'account_category_id' => 'Beban Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Beban Pajak - Kini', 'account_no' => '8-90000',  'account_category_id' => 'Beban Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],
            ['name' => 'Beban Pajak - Tangguhan', 'account_no' => '8-90001',  'account_category_id' => 'Beban Lainnya', 'amount' => 0.00, 'description' => 'Kas di Mesin Kasir'],

        ];

        // Map category names to category ids
        foreach ($accounts as &$account) {
            $account['account_category_id'] = $categories[$account['account_category_id']];
        }

        // Insert accounts into the accountnumbers table
        DB::table('accountnumbers')->insert($accounts);
    }
}