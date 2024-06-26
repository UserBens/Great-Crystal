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
        Schema::create('invoice_supplier_statuses', function (Blueprint $table) {
            $table->id();
            $table->enum('payment_status', ['Paid', 'Not Yet'])->default('Paid');
            $table->string('description')->nullable();
            $table->string('image_path')->nullable(); 
            $table->string('no_invoice'); 
            $table->foreign('no_invoice')->references('no_invoice')->on('invoice_suppliers')->cascadeOnDelete()->cascadeOnUpdate(); 
            $table->string('supplier_name', 255);
            $table->foreign('supplier_name')->references('name')->on('supplier_data')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_supplier_statuses');
    }
};
