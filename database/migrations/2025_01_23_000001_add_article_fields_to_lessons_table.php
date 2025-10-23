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
        Schema::table('lessons', function (Blueprint $table) {
            // Article content field
            $table->longText('article_body')->nullable()->after('description');
            
            // Poster image fields
            $table->string('poster_source')->nullable()->after('video_url'); // 'upload' or 'url'
            $table->string('poster_file')->nullable()->after('poster_source'); // path to uploaded image
            $table->string('poster_url')->nullable()->after('poster_file'); // external image URL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['article_body', 'poster_source', 'poster_file', 'poster_url']);
        });
    }
};
