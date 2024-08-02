<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.\
     * 
     * 
     */
    public function up(): void
    {
        date_default_timezone_set('Asia/Jakarta');
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('type');
            $table->string('subject')->nullable()->default(null);
            $table->bigInteger('amount');
            $table->bigInteger('dp')->default(0);
            $table->boolean('paidOf')->default(false);
            $table->integer('discount')->nullable()->default(null);
            $table->date('deadline_invoice')->default(date('Y-m-t'));
            $table->integer('installment')->nullable()->default(null);
            $table->integer('amount_installment')->default(0);
            $table->dateTime('date_change_bill')->default(null)->nullable();
            $table->unsignedBigInteger('transfer_account_id')->nullable();
            $table->foreign('transfer_account_id')->references('id')->on('accountnumbers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('deposit_account_id')->nullable();
            $table->foreign('deposit_account_id')->references('id')->on('accountnumbers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};