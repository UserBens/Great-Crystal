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
        Schema::create('status_invoice_mails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id');
            $table->foreign('bill_id')->on('bills')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('status')->default(true);
            $table->boolean('past_due')->default(false);
            $table->boolean('charge')->default(false);
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_change')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_invoice_mails');
    }
};
