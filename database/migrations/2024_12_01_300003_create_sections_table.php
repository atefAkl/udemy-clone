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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->string('description');
            $table->integer('sort_order');
            $table->boolean('is_published')->default(1);
            $table->boolean('has_quiz')->default(0);
            $table->boolean('has_assignment')->default(0);
            $table->boolean('has_survey')->default(0);
            $table->boolean('has_rating')->default(0);

            $table->engine('INNODB');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
