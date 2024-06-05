<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\TransactionSendSupplier;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      $this->call([
         UserSeeder::class,
         TeacherSeeder::class,
         GradeSeeder::class,
         StudentRelationshipSeeder::class,
         AccountCategorySeeder::class,
         AccountNumberSeeder::class,
         TransactionSendSupplierSeeder::class,
         TransactionTransferSeeder::class,
         TransactionSendSeeder::class,
         TransactionReceiveSeeder::class,
      ]);
    }
}