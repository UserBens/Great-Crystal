<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supplier_data', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->index();
            $table->string('no_telp');
            $table->string('email');
            $table->string('fax');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('post_code');
            $table->string('accountnumber');
            $table->string('accountnumber_holders_name');
            $table->string('bank_name');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_data');
    }
};
