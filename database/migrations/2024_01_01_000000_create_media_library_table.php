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
        Schema::create('media_library', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('original_name');
            $table->string('path');
            $table->string('url');
            $table->string('thumbnail_path')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('mime_type');
            $table->string('extension');
            $table->unsignedBigInteger('size'); // in bytes
            $table->string('size_formatted'); // e.g., "1.5 MB"
            $table->enum('type', ['image', 'video']);
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->decimal('aspect_ratio', 5, 2)->nullable();
            $table->integer('duration')->nullable(); // for videos in seconds
            $table->string('duration_formatted')->nullable(); // e.g., "5:30"
            $table->json('metadata')->nullable();
            $table->boolean('is_validated')->default(false);
            $table->json('validation_errors')->nullable();
            $table->enum('status', ['pending', 'processing', 'ready', 'failed'])->default('pending');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'type']);
            $table->index(['mime_type']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_library');
    }
};
