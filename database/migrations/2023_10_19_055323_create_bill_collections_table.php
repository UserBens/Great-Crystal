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
        Schema::create('bill_collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id');
            $table->foreign('bill_id')->references('id')->on('bills')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('book_id')->nullable()->default(NULL);
            $table->foreign('book_id')->references('id')->on('books')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('type')->default('Book');
            $table->string('name');
            $table->bigInteger('amount');
            $table->integer('discount')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_collections');
    }
};
