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
      Schema::create('student_relations', function (Blueprint $table) {
         $table->id();
         $table->unsignedBigInteger('student_id');
         $table->unsignedBigInteger('relation_id');
         $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete()->cascadeOnUpdate();
         $table->foreign('relation_id')->references('id')->on('relationships')->cascadeOnDelete()->cascadeOnUpdate();
         $table->timestamps();
     });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('student_relations');
    }
};