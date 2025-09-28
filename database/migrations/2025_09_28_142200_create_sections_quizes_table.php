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
        Schema::create('sections_quizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id')->references('id')->on('quizes')->onDelete('cascade');
            $table->unsignedBigInteger('section')->references('id')->on('sections')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections_quizes');
    }
};
