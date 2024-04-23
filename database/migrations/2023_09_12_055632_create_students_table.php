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
      Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active');
            $table->string('unique_id')->unique();  
            $table->string('name');
            $table->unsignedBigInteger('grade_id');
            $table->foreign('grade_id')->references('id')->on('grades')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nisn')->nullable()->default(NULL)->unique();
            $table->string('gender');
            $table->string('religion');
            $table->string('place_birth');
            $table->date('date_birth');
            $table->string('id_or_passport')->nullable()->default(null);
            $table->string('nationality');
            $table->string('place_of_issue')->nullable();
            $table->date('date_exp')->nullable();
            $table->timestamps();
      });
   }

   /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::drop('students');
    }
};