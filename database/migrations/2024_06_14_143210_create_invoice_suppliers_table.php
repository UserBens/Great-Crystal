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
        Schema::create('invoice_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('supplier_data')->cascadeOnDelete()->cascadeOnUpdate();
            $table->bigInteger('amount');
            $table->integer('pph')->nullable();
            $table->integer('pph_percentage')->nullable();
            $table->dateTime('date')->default(now());
            $table->string('nota');
            $table->dateTime('deadline_invoice')->default(now());
            $table->enum('payment_status', ['Paid', 'Not Yet'])->default('Not Yet');
            $table->enum('payment_method', ['Cash', 'Bank'])->nullable();
            $table->unsignedBigInteger('transfer_account_id')->nullable();
            $table->foreign('transfer_account_id')->references('id')->on('accountnumbers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('deposit_account_id')->nullable();
            $table->foreign('deposit_account_id')->references('id')->on('accountnumbers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('old_transfer_account_id')->nullable();
            $table->foreign('old_transfer_account_id')->references('id')->on('accountnumbers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('new_transfer_account_id')->nullable();
            $table->foreign('new_transfer_account_id')->references('id')->on('accountnumbers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('description')->nullable();
            $table->string('image_invoice')->nullable();
            $table->string('image_proof')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_suppliers');
    }
};
