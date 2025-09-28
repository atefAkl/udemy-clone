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
            // Add section_id column after course_id
            $table->foreignId('section_id')
                ->after('course_id')
                ->nullable()
                ->constrained('sections')
                ->onDelete('cascade');

            // Update indexes
            $table->dropIndex(['module_id', 'sort_order']);
            $table->index(['section_id', 'sort_order']);
            
            // Rename module_id to section_id if it exists
            if (Schema::hasColumn('lessons', 'module_id')) {
                $table->renameColumn('module_id', 'section_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['section_id']);
            
            // Revert indexes if needed
            $table->dropIndex(['section_id', 'sort_order']);
            $table->index(['module_id', 'sort_order']);
            
            // Revert column name if it was renamed
            if (Schema::hasColumn('lessons', 'section_id') && !Schema::hasColumn('lessons', 'module_id')) {
                $table->renameColumn('section_id', 'module_id');
            }
        });
    }
};
