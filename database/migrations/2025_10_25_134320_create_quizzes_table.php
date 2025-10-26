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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('quiz_type', ['practice', 'graded', 'final'])->default('graded');
            $table->integer('duration_minutes')->nullable()->comment('Quiz duration in minutes');
            $table->decimal('pass_percentage', 5, 2)->default(70.00)->comment('Minimum percentage to pass');
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('show_results')->default(true)->comment('Show results immediately after submission');
            $table->integer('max_attempts')->nullable()->comment('Maximum number of attempts allowed');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
