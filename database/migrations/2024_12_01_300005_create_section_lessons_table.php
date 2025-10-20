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
        Schema::create('section_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained()->onDelete('cascade');
            $table->string('title', 45);
            $table->string('description', 255);
            $table->string('type')->default('video'); // e.g., video, article, quiz
            $table->integer('order')->default(0);
            $table->integer('duration_minutes')->nullable();
            $table->string('video_url')->nullable();
            $table->json('display_files')->nullable();
            $table->json('downloadable_files')->nullable();
            $table->boolean('request_student_evaluation')->default(false);
            $table->boolean('request_assignment')->default(false);
            $table->text('assignment_details')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_lessons');
    }
};
