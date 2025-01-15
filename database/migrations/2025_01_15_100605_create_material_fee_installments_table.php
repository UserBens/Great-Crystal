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
        Schema::create('material_fee_installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_fee_id');
            $table->foreign('material_fee_id')->references('id')->on('payment_materialfees')->cascadeOnDelete();
            $table->unsignedBigInteger('bill_id');
            $table->foreign('bill_id')->references('id')->on('bills')->cascadeOnDelete();
            $table->integer('installment_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_fee_installments');
    }
};
