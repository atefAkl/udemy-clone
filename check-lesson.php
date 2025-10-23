<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$lessonId = $argv[1] ?? 10;

echo "=== Checking Lesson ID: $lessonId ===\n\n";

$lesson = DB::table('lessons')->where('id', $lessonId)->first();

if ($lesson) {
    echo "Title: {$lesson->title}\n";
    echo "Type: {$lesson->lesson_type}\n";
    echo "Description: {$lesson->description}\n\n";
    
    if ($lesson->lecture_file) {
        $files = json_decode($lesson->lecture_file, true);
        echo "Files (" . count($files) . "):\n";
        echo "================\n";
        foreach ($files as $index => $file) {
            echo ($index + 1) . ". {$file['original_name']}\n";
            echo "   Path: {$file['path']}\n";
            echo "   Size: " . number_format($file['size'] / 1024, 2) . " KB\n";
            echo "   Type: {$file['mime_type']}\n\n";
            
            // Check if file exists
            $fullPath = storage_path('app/public/' . $file['path']);
            if (file_exists($fullPath)) {
                echo "   ✅ File EXISTS\n";
            } else {
                echo "   ❌ File NOT FOUND\n";
            }
            echo "\n";
        }
    } else {
        echo "❌ No files attached yet\n";
    }
} else {
    echo "❌ Lesson not found\n";
}
