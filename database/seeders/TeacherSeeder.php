<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      // $table->string('unique_id')->unique();
      // $table->string('name');
      // $table->string('nik')->unique();
      // $table->string('religion');
      // $table->string('gender');
      // $table->string('place_birth');
      // $table->string('nationality');
      // $table->date('date_birth');
      // $table->text('home_address');
      // $table->text('temporary_address');
      // $table->string('handphone');
      // $table->string('email');
      // $table->string('last_education');
      // $table->string('major');


      DB::table('teachers')->insert([
         [
            'is_active' => 1,
            'unique_id' => date('Ym') . '0001',
            'name' => 'Awan Santoso',
            'nik' => '1234567890123456',
            'religion' => 'Islam',
            'gender' => 'Male',
            'nationality' => 'Indonesia',
            'place_birth' => 'Surabaya',
            'date_birth' => '1978-04-12',
            'home_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'temporary_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'handphone' => '082141311214',
            'email' => 'dummy1@email.com',
            'last_education' => 'bachelor degree',
            'major' => 'Informatics',
            'created_at' => date("Y-m-d"),
         ],
         [
            'is_active' => 1,
            'unique_id' => date('Ym') . '0002',
            'name' => 'Yuli Astuti',
            'nik' => '1234567890123455',
            'religion' => 'Islam',
            'gender' => 'Female',
            'nationality' => 'Indonesia',
            'place_birth' => 'Surabaya',
            'date_birth' => '1980-04-12',
            'home_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'temporary_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'handphone' => '082141311213',
            'email' => 'dummy2@email.com',
            'last_education' => 'bachelor degree',
            'major' => 'System information',
            'created_at' => date("Y-m-d"),
         ],
         [
            'is_active' => 1,
            'unique_id' => date('Ym') . '0003',
            'name' => 'Kurniawan',
            'nik' => '1234567890123454',
            'religion' => 'Islam',
            'gender' => 'Male',
            'nationality' => 'Indonesia',
            'place_birth' => 'Surabaya',
            'date_birth' => '1981-04-12',
            'home_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'temporary_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'handphone' => '082141311212',
            'email' => 'dummy3@email.com',
            'last_education' => 'bachelor degree',
            'major' => 'Comupter Science',
            'created_at' => date("Y-m-d"),
         ],
      ]);
    }
}