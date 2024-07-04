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
        Schema::create('balance_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accountnumber_id'); // Tambahkan kolom relasi
            $table->foreign('accountnumber_id')->references('id')->on('accountnumbers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->bigInteger('beginning_balance');
            $table->bigInteger('ending_balance')->default(0);
            $table->boolean('posted')->default(false); // Kolom untuk menandai posting
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_accounts');
    }
};
