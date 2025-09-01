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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('discount_price', 8, 2)->nullable();
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('thumbnail')->nullable();
            $table->string('preview_video')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->string('language', 5)->default('ar');
            $table->dateTime('deleted_at')->nullable();
            $table->date('launch_date')->nullable();
            $table->boolean('is_featured')->nullable();
            $table->time('launch_time')->nullable();
            $table->json('objectives')->nullable();
            $table->json('table_of_contents')->nullable();
            $table->boolean('has_certificate')->default(false);
            $table->enum('access_duration_type', ['lifetime', 'limited'])->default('lifetime');
            $table->integer('access_duration_value')->nullable();
            $table->enum('target_level', ['beginner', 'intermediate', 'advanced', 'all'])->default('all');
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['instructor_id', 'status']);
            $table->index(['category_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
