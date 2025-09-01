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
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('duration')->nullable(); // in minutes
            $table->integer('sort_order')->default(0);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_published')->default(true);
            $table->enum('content_type', ['video', 'article', 'quiz', 'assignment', 'download', 'live_session'])->default('video');
            $table->json('content_data')->nullable();
            $table->string('video_file')->nullable();
            $table->text('article_content')->nullable();
            $table->json('downloadable_resources')->nullable();
            $table->integer('estimated_duration')->nullable();
            $table->json('learning_objectives')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['course_id', 'sort_order']);
            $table->index(['module_id', 'sort_order']);
            $table->index(['is_published', 'is_free']);
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
