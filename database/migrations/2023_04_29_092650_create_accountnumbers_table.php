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
            $table->string('name')->unique();
            $table->string('account_no')->unique();
            $table->unsignedBigInteger('account_category_id');
            $table->foreign('account_category_id')->references('id')->on('accountcategories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('debit')->nullable();
            $table->bigInteger('credit')->nullable();
            $table->date('date')->nullable(); // Changed to date type
            $table->date('posted_date')->nullable(); // Menambahkan kolom posted_date
            $table->boolean('posted')->default(false); // Menambahkan kolom posted
            $table->enum('type', ['Debit', 'Kredit'])->default(null);
            $table->string('description')->nullable();
            $table->timestamps();
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
