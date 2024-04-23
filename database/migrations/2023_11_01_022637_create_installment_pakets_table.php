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
        Schema::create('installment_pakets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_id');
            $table->foreign('main_id')->on('bills')->references('id')->cascadeOnDelete();
            $table->unsignedBigInteger('child_id');
            $table->foreign('child_id')->on('bills')->references('id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_pakets');
    }
};
