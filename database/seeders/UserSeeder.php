<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

      $data = [
         [
            'username' => 'superadmin',
            'password' => Hash::make('superadmin'),
            'role' => 'superadmin',
            
         ],
         [
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            
         ],
         [
            'username' => 'accounting',
            'password' => Hash::make('accounting'),
            'role' => 'HR',
            
         ],
      ];

      DB::table('users')->insert($data);
    }
}