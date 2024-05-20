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
            $table->integer('account_no');  
            $table->unsignedBigInteger('account_category_id');
            $table->foreign('account_category_id')->references('id')->on('accountcategories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('type', ['cash', 'bank']);
            $table->string('bank_name');
            $table->bigInteger('amount');
            $table->string('description');
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
