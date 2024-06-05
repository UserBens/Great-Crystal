<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionReceiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transaction_receives')->insert([
            [
                'transfer_account_id' => 1,
                'deposit_account_id' => 2,
                'student_id' => 1,
                'no_transaction' => 'RX001',
                'amount' => 2000000,
                'date' => $this->randomDate(),
                'description' => 'Transaction receive from account 1 to account 2 for student 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transfer_account_id' => 2,
                'deposit_account_id' => 3,
                'student_id' => 2,
                'no_transaction' => 'RX002',
                'amount' => 2000000,
                'date' => $this->randomDate(),
                'description' => 'Transaction receive from account 2 to account 3 for student 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transfer_account_id' => 3,
                'deposit_account_id' => 1,
                'student_id' => 3,
                'no_transaction' => 'RX003',
                'amount' => 2000000,
                'date' => $this->randomDate(),
                'description' => 'Transaction receive from account 3 to account 1 for student 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transfer_account_id' => 3,
                'deposit_account_id' => 1,
                'student_id' => 3,
                'no_transaction' => 'RX004',
                'amount' => 2000000,
                'date' => $this->randomDate(),
                'description' => 'Transaction receive from account 3 to account 1 for student 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    private function randomDate()
    {
        $start = strtotime('2024-05-01');
        $end = strtotime('2024-05-30');
        $timestamp = mt_rand($start, $end);
        return date('Y-m-d H:i:s', $timestamp);
    }
}
