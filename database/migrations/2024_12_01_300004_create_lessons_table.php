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
        Schema::create('lessons', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->string('title');
            $table->string('content_type')->default('video');
            $table->string('description')->default('No description');
            $table->unsignedBigInteger('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->unsignedBigInteger('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->integer('sort_order')->nullable();
            $table->string('slug')->nullable();
            $table->string('duration')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_file')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('preview_video')->nullable();
            $table->string('lecture_file')->nullable();
            $table->string('presentation')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
