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
        Schema::create('transaction_receives', function (Blueprint $table) {
            $table->id();
            $table->string('transfer');
            $table->integer('deposit');
            $table->bigInteger('amount');
            $table->dateTime('date')->default(now());
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_receives');
    }
};
