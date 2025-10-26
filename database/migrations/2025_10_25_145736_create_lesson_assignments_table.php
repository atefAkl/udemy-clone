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
        Schema::create('lesson_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable()->comment('Assignment instructions for students');
            $table->integer('due_days')->nullable()->comment('Days from lesson start to complete');
            $table->decimal('max_score', 5, 2)->default(100.00);
            $table->string('allowed_file_types')->default('pdf,doc,docx,zip')->comment('Comma-separated allowed file types');
            $table->integer('max_file_size')->default(10)->comment('Maximum file size in MB');
            $table->boolean('is_required')->default(false);
            $table->timestamps();
            
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_assignments');
    }
};
