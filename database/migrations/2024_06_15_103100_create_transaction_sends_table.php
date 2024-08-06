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
        Schema::create('transaction_sends', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_account_id');
            $table->foreign('transfer_account_id')->references('id')->on('accountnumbers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('deposit_account_id');
            $table->foreign('deposit_account_id')->references('id')->on('accountnumbers')->cascadeOnDelete()->cascadeOnUpdate();                    
            $table->string('recipient_name')->nullable();
            $table->string('no_transaction')->unique();
            $table->bigInteger('amount');
            $table->dateTime('date')->default(now());
            $table->dateTime('deadline_invoice')->default(now());
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_sends');
    }
};
