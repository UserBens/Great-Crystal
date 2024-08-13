<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transaction_sends')->insert([
            [
                'transfer_account_id' => 1,
                'deposit_account_id' => 2,
                'recipient_name' => 'supri',
                'no_transaction' => 'SX001',
                'amount' => 2000000,
                'date' => $this->randomDate(),
                'description' => 'Transaction send from account 1 to account 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transfer_account_id' => 2,
                'deposit_account_id' => 3,
                'recipient_name' => 'ngatno',
                'no_transaction' => 'SX002',
                'amount' => 2000000,
                'date' => $this->randomDate(),
                'description' => 'Transaction send from account 2 to account 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transfer_account_id' => 3,
                'deposit_account_id' => 1,
                'recipient_name' => 'greg',
                'no_transaction' => 'SX003',
                'amount' => 2000000,
                'date' => $this->randomDate(),
                'description' => 'Transaction send from account 3 to account 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transfer_account_id' => 3,
                'deposit_account_id' => 1,
                'recipient_name' => 'nwokolo',
                'no_transaction' => 'SX004',
                'amount' => 2000000,
                'date' => $this->randomDate(),
                'description' => 'Transaction send from account 3 to account 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    private function randomDate()
    {
        $start = strtotime('2024-05-01');
        $end = strtotime('2024-06-30');
        $timestamp = mt_rand($start, $end);
        return date('Y-m-d H:i:s', $timestamp);
    }
}
