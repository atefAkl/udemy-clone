<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Clearing queue jobs...\n";

$count = DB::table('jobs')->count();
echo "Found $count jobs\n";

DB::table('jobs')->truncate();

echo "✅ Queue cleared!\n";
