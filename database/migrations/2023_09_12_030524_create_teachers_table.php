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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active');
            $table->string('unique_id')->unique();
            $table->string('name');
            $table->string('nik')->unique();
            $table->string('religion');
            $table->string('gender');
            $table->string('place_birth');
            $table->string('nationality');
            $table->date('date_birth');
            $table->text('home_address');
            $table->text('temporary_address');
            $table->string('handphone')->unique();
            $table->string('email')->unique();
            $table->string('last_education');
            $table->string('major');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};