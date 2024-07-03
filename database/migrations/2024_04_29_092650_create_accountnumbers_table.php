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
        Schema::create('accountnumbers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('account_no');
            $table->unsignedBigInteger('account_category_id');
            $table->foreign('account_category_id')->references('id')->on('accountcategories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->bigInteger('amount');
            // $table->bigInteger('beginning_balance');
            // $table->bigInteger('ending_balance')->default(0);
            // $table->bigInteger('transactions_total')->default(0);
            $table->string('description');
            $table->enum('position', ['debit', 'credit'])->nullable(); // Ubah default ke nullable
            $table->timestamps();
            // Add unique constraints
            // $table->unique('name');
            $table->unique('account_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountnumbers');
    }
};
