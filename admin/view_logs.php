<?php
/**
 * View Product Image Upload Logs
 * 
 * This script shows the recent logs for product image uploads
 */

// Load OpenCart
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

$logs_dir = DIR_SYSTEM . 'storage/logs/';
$debug_log = $logs_dir . 'product_insert_debug.log';
$error_log = $logs_dir . 'product_insert_error.log';

echo "<!DOCTYPE html>
<html>
<head>
    <title>View Product Image Upload Logs</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 1400px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        .log-section { margin: 20px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #4CAF50; }
        .error-section { margin: 20px 0; padding: 15px; background: #fff3f3; border-left: 4px solid #f44336; }
        .log-content { font-family: 'Courier New', monospace; font-size: 12px; white-space: pre-wrap; background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 4px; overflow-x: auto; max-height: 600px; overflow-y: auto; }
        .info { background: #e3f2fd; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .warning { color: #ff9800; font-weight: bold; }
        a { color: #4CAF50; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>📋 Product Image Upload Logs</h1>
        <p><a href='check_image_upload.php'>← Back to Image Upload Check</a></p>";

// Show Debug Log
echo "<h2>Debug Log (product_insert_debug.log)</h2>";
if (file_exists($debug_log)) {
    $debug_content = file_get_contents($debug_log);
    $lines = explode("\n", $debug_content);
    // Show last 200 lines
    $recent_lines = array_slice($lines, -200);
    $recent_content = implode("\n", $recent_lines);
    
    echo "<div class='log-section'>";
    echo "<p class='info'>Showing last 200 lines. Full log has " . count($lines) . " lines.</p>";
    echo "<div class='log-content'>" . htmlspecialchars($recent_content) . "</div>";
    echo "</div>";
} else {
    echo "<div class='log-section'><p class='warning'>Debug log file does not exist yet. Upload a product with images to generate logs.</p></div>";
}

// Show Error Log
echo "<h2>Error Log (product_insert_error.log)</h2>";
if (file_exists($error_log)) {
    $error_content = file_get_contents($error_log);
    $error_lines = explode("\n", $error_content);
    // Show last 100 lines
    $recent_errors = array_slice($error_lines, -100);
    $recent_error_content = implode("\n", $recent_errors);
    
    echo "<div class='error-section'>";
    echo "<p class='info'>Showing last 100 lines. Full error log has " . count($error_lines) . " lines.</p>";
    echo "<div class='log-content'>" . htmlspecialchars($recent_error_content) . "</div>";
    echo "</div>";
} else {
    echo "<div class='log-section'><p class='success'>No errors found. Error log file does not exist.</p></div>";
}

// Summary
echo "<h2>Summary</h2>";
echo "<div class='info'>";
echo "<p><strong>Instructions:</strong></p>";
echo "<ol>";
echo "<li>Upload multiple images to a product</li>";
echo "<li>Click Save</li>";
echo "<li>Refresh this page to see the logs</li>";
echo "<li>Look for lines that say 'Processing image #X/Y' to see how many images were received</li>";
echo "<li>Look for 'inserted successfully' or 'Failed to insert' to see what happened</li>";
echo "<li>Check the final summary line that shows 'X successful, Y failed out of Z total'</li>";
echo "</ol>";
echo "</div>";

echo "</div></body></html>";
?>

