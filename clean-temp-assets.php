<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "=== Cleaning Temp Assets Files ===\n\n";

$tempPath = 'temp/assets';

if (Storage::disk('public')->exists($tempPath)) {
    $files = Storage::disk('public')->files($tempPath);
    
    echo "Found " . count($files) . " temp files\n\n";
    
    foreach ($files as $file) {
        $filename = basename($file);
        echo "Deleting: $filename\n";
        Storage::disk('public')->delete($file);
    }
    
    echo "\n✅ All temp files deleted!\n";
} else {
    echo "❌ Temp directory not found\n";
}

echo "\n=== Done! ===\n";
