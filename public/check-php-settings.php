<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Upload Settings Check</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .status-ok {
            color: #4CAF50;
            font-weight: bold;
        }
        .status-warning {
            color: #ff9800;
            font-weight: bold;
        }
        .status-error {
            color: #f44336;
            font-weight: bold;
        }
        .recommendation {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 PHP Upload & Timeout Settings</h1>
        
        <?php
        // Get current PHP settings
        $upload_max = ini_get('upload_max_filesize');
        $post_max = ini_get('post_max_size');
        $memory_limit = ini_get('memory_limit');
        $max_execution = ini_get('max_execution_time');
        $max_input_time = ini_get('max_input_time');
        $max_file_uploads = ini_get('max_file_uploads');
        
        // Convert to bytes for comparison
        function convertToBytes($value) {
            $value = trim($value);
            $last = strtolower($value[strlen($value)-1]);
            $value = (int) $value;
            switch($last) {
                case 'g': $value *= 1024;
                case 'm': $value *= 1024;
                case 'k': $value *= 1024;
            }
            return $value;
        }
        
        // Recommended values (in bytes)
        $recommended = [
            'upload_max_filesize' => convertToBytes('100M'),
            'post_max_size' => convertToBytes('100M'),
            'memory_limit' => convertToBytes('256M'),
            'max_execution_time' => 300,
            'max_input_time' => 300,
            'max_file_uploads' => 20
        ];
        
        $current = [
            'upload_max_filesize' => convertToBytes($upload_max),
            'post_max_size' => convertToBytes($post_max),
            'memory_limit' => convertToBytes($memory_limit),
            'max_execution_time' => (int) $max_execution,
            'max_input_time' => (int) $max_input_time,
            'max_file_uploads' => (int) $max_file_uploads
        ];
        
        $allGood = true;
        ?>
        
        <table>
            <thead>
                <tr>
                    <th>Setting</th>
                    <th>Current Value</th>
                    <th>Recommended</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>upload_max_filesize</strong></td>
                    <td><?php echo $upload_max; ?></td>
                    <td>100M</td>
                    <td>
                        <?php 
                        if ($current['upload_max_filesize'] >= $recommended['upload_max_filesize']) {
                            echo '<span class="status-ok">✓ OK</span>';
                        } else {
                            echo '<span class="status-error">✗ Too Low</span>';
                            $allGood = false;
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>post_max_size</strong></td>
                    <td><?php echo $post_max; ?></td>
                    <td>100M</td>
                    <td>
                        <?php 
                        if ($current['post_max_size'] >= $recommended['post_max_size']) {
                            echo '<span class="status-ok">✓ OK</span>';
                        } else {
                            echo '<span class="status-error">✗ Too Low</span>';
                            $allGood = false;
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>memory_limit</strong></td>
                    <td><?php echo $memory_limit; ?></td>
                    <td>256M</td>
                    <td>
                        <?php 
                        if ($current['memory_limit'] >= $recommended['memory_limit']) {
                            echo '<span class="status-ok">✓ OK</span>';
                        } else {
                            echo '<span class="status-warning">⚠ Low</span>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>max_execution_time</strong></td>
                    <td><?php echo $max_execution; ?> seconds</td>
                    <td>300 seconds</td>
                    <td>
                        <?php 
                        if ($current['max_execution_time'] >= $recommended['max_execution_time']) {
                            echo '<span class="status-ok">✓ OK</span>';
                        } else {
                            echo '<span class="status-error">✗ Too Low</span>';
                            $allGood = false;
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>max_input_time</strong></td>
                    <td><?php echo $max_input_time; ?> seconds</td>
                    <td>300 seconds</td>
                    <td>
                        <?php 
                        if ($current['max_input_time'] >= $recommended['max_input_time']) {
                            echo '<span class="status-ok">✓ OK</span>';
                        } else {
                            echo '<span class="status-error">✗ Too Low</span>';
                            $allGood = false;
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>max_file_uploads</strong></td>
                    <td><?php echo $max_file_uploads; ?> files</td>
                    <td>20 files</td>
                    <td>
                        <?php 
                        if ($current['max_file_uploads'] >= $recommended['max_file_uploads']) {
                            echo '<span class="status-ok">✓ OK</span>';
                        } else {
                            echo '<span class="status-warning">⚠ Low</span>';
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <?php if ($allGood): ?>
            <div class="success">
                <h3>✅ All Settings Are Configured Correctly!</h3>
                <p>Your PHP configuration is ready for uploading large files and multiple files.</p>
            </div>
        <?php else: ?>
            <div class="recommendation">
                <h3>⚠️ Action Required</h3>
                <p><strong>Follow these steps:</strong></p>
                <ol>
                    <li>Open <strong>WAMP Tray Icon</strong> → PHP → php.ini</li>
                    <li>Update the settings marked with ✗</li>
                    <li>Save the file and <strong>Restart Apache</strong></li>
                    <li>Refresh this page to verify changes</li>
                </ol>
                <p>For detailed instructions, see: <code>PHP_UPLOAD_SETTINGS.md</code></p>
            </div>
        <?php endif; ?>
        
        <p style="text-align: center; color: #666; margin-top: 30px;">
            <small>🔒 Delete this file after checking: <code>public/check-php-settings.php</code></small>
        </p>
    </div>
</body>
</html>
