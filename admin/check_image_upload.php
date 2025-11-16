<?php
/**
 * Check Image Upload Status
 * 
 * This script checks what's happening with image uploads
 */

// Start output buffering
if (!ob_get_level()) {
    ob_start();
}

// Load OpenCart
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Database
$db = new \DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

$prefix = DB_PREFIX;
$logs_dir = DIR_SYSTEM . 'storage/logs/';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Check Image Upload Status</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        h2 { color: #666; margin-top: 30px; }
        .success { color: #4CAF50; font-weight: bold; padding: 10px; background: #e8f5e9; border-left: 4px solid #4CAF50; margin: 10px 0; }
        .error { color: #f44336; font-weight: bold; padding: 10px; background: #ffebee; border-left: 4px solid #f44336; margin: 10px 0; }
        .info { color: #2196F3; padding: 10px; background: #e3f2fd; border-left: 4px solid #2196F3; margin: 10px 0; }
        .warning { color: #ff9800; padding: 10px; background: #fff3e0; border-left: 4px solid #ff9800; margin: 10px 0; }
        .code { background: #f4f4f4; padding: 15px; border-radius: 4px; font-family: monospace; margin: 15px 0; border: 1px solid #ddd; white-space: pre-wrap; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🔍 Check Image Upload Status</h1>";

// Check database state
echo "<div class='info'>";
echo "<h2>1. Database State</h2>";

// Check for product_image_id = 0
$check_zero = $db->query("SELECT COUNT(*) as count FROM {$prefix}product_image WHERE product_image_id = 0");
$zero_count = $check_zero->row['count'] ?? 0;

if ($zero_count > 0) {
    echo "<p class='error'>❌ Found {$zero_count} record(s) with product_image_id = 0</p>";
    $zero_records = $db->query("SELECT * FROM {$prefix}product_image WHERE product_image_id = 0");
    echo "<table><tr><th>product_image_id</th><th>product_id</th><th>image</th><th>sort_order</th></tr>";
    foreach ($zero_records->rows as $row) {
        echo "<tr><td>{$row['product_image_id']}</td><td>{$row['product_id']}</td><td>" . htmlspecialchars(substr($row['image'], 0, 50)) . "...</td><td>{$row['sort_order']}</td></tr>";
    }
    echo "</table>";
    
    // Auto-fix button
    if (isset($_POST['auto_fix'])) {
        echo "<div class='info'>";
        echo "<h3>Fixing...</h3>";
        
        // Delete product_image_id = 0
        $delete_result = $db->query("DELETE FROM {$prefix}product_image WHERE product_image_id = 0");
        $check_after = $db->query("SELECT COUNT(*) as count FROM {$prefix}product_image WHERE product_image_id = 0");
        $count_after = $check_after->row['count'] ?? 0;
        $deleted = $zero_count - $count_after;
        
        if ($deleted > 0) {
            echo "<p class='success'>✓ Deleted {$deleted} record(s) with product_image_id = 0</p>";
        }
        
        // Fix AUTO_INCREMENT - MUST be MAX + 1, not just 1
        $max_result = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image WHERE product_image_id > 0");
        $max_id = $max_result->row['max_id'] ?? 0;
        $next_id = max($max_id + 1, 1);
        
        echo "<p class='info'>Max product_image_id found: {$max_id}, Setting AUTO_INCREMENT to: {$next_id}</p>";
        
        $fix_result = $db->query("ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_id}");
        
        if ($fix_result !== false) {
            // Verify it was set correctly
            $verify_ai = $db->query("SHOW TABLE STATUS LIKE '{$prefix}product_image'");
            $verified_value = 'N/A';
            if ($verify_ai && $verify_ai->num_rows) {
                $row = $verify_ai->row;
                if (isset($row['Auto_increment'])) {
                    $verified_value = $row['Auto_increment'];
                } elseif (isset($row['AUTO_INCREMENT'])) {
                    $verified_value = $row['AUTO_INCREMENT'];
                }
            }
            
            echo "<p class='success'>✓ Set AUTO_INCREMENT to {$next_id} (Verified: {$verified_value})</p>";
            echo "<p class='success'><strong>✅ FIXED! Page will refresh in 2 seconds...</strong></p>";
            echo "<meta http-equiv='refresh' content='2'>";
        } else {
            echo "<p class='error'>❌ Error setting AUTO_INCREMENT. Please run SQL manually:</p>";
            echo "<div class='code'>ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_id};</div>";
        }
        
        echo "</div>";
    } else {
        echo "<p class='warning'><strong>FIX REQUIRED:</strong></p>";
        echo "<form method='POST' style='margin: 15px 0;'>";
        echo "<button type='submit' name='auto_fix' style='background: #4CAF50; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold;'>🔧 Auto-Fix Now</button>";
        echo "</form>";
        echo "<p>Or run this SQL manually in phpMyAdmin (replace X with MAX(product_image_id) + 1):</p>";
        echo "<div class='code'>DELETE FROM {$prefix}product_image WHERE product_image_id = 0;
SELECT MAX(product_image_id) + 1 as next_id FROM {$prefix}product_image;
ALTER TABLE {$prefix}product_image AUTO_INCREMENT = X;</div>";
    }
} else {
    echo "<p class='success'>✓ No records with product_image_id = 0</p>";
}

// Check AUTO_INCREMENT
$ai_check = $db->query("SHOW TABLE STATUS LIKE '{$prefix}product_image'");
$current_ai = 'N/A';
if ($ai_check && $ai_check->num_rows) {
    $row = $ai_check->row;
    if (isset($row['Auto_increment'])) {
        $current_ai = $row['Auto_increment'];
    } elseif (isset($row['AUTO_INCREMENT'])) {
        $current_ai = $row['AUTO_INCREMENT'];
    } else {
        foreach ($row as $key => $value) {
            if (stripos($key, 'increment') !== false && is_numeric($value)) {
                $current_ai = $value;
                break;
            }
        }
    }
}

$max_check = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image WHERE product_image_id > 0");
$max_id = $max_check->row['max_id'] ?? 0;
$expected_next = max($max_id + 1, 1);

echo "<p><strong>Current AUTO_INCREMENT:</strong> {$current_ai}</p>";
echo "<p><strong>Max product_image_id:</strong> {$max_id}</p>";
echo "<p><strong>Expected next ID:</strong> {$expected_next}</p>";

if ($current_ai != 'N/A' && $current_ai != $expected_next) {
    echo "<p class='warning'>⚠️ AUTO_INCREMENT mismatch! Should be {$expected_next} but is {$current_ai}</p>";
}

echo "</div>";

// Check recent logs
echo "<div class='info'>";
echo "<h2>2. Recent Log Files</h2>";

$log_files = [
    'product_insert_debug.log',
    'product_insert_error.log'
];

foreach ($log_files as $log_file) {
    $log_path = $logs_dir . $log_file;
    if (file_exists($log_path)) {
        $log_content = file_get_contents($log_path);
        $lines = explode("\n", $log_content);
        $recent_lines = array_slice($lines, -50); // Last 50 lines
        echo "<h3>{$log_file}</h3>";
        echo "<div class='code'>" . htmlspecialchars(implode("\n", $recent_lines)) . "</div>";
    } else {
        echo "<p class='info'>Log file {$log_file} does not exist yet.</p>";
    }
}

echo "</div>";

// Check a specific product's images
if (isset($_GET['product_id']) && $_GET['product_id'] > 0) {
    $product_id = (int)$_GET['product_id'];
    echo "<div class='info'>";
    echo "<h2>3. Images for Product ID: {$product_id}</h2>";
    
    $product_images = $db->query("SELECT * FROM {$prefix}product_image WHERE product_id = '{$product_id}' ORDER BY sort_order, product_image_id");
    
    if ($product_images && $product_images->num_rows) {
        echo "<p class='success'>Found {$product_images->num_rows} image(s) for this product:</p>";
        echo "<table><tr><th>product_image_id</th><th>image</th><th>sort_order</th></tr>";
        foreach ($product_images->rows as $img) {
            echo "<tr><td>{$img['product_image_id']}</td><td>" . htmlspecialchars(substr($img['image'], 0, 60)) . "...</td><td>{$img['sort_order']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warning'>No images found for product ID {$product_id}</p>";
    }
    
    echo "</div>";
}

// Form to check product
echo "<div class='info'>";
echo "<h2>3. Check Product Images</h2>";
echo "<form method='GET'>";
echo "<p>Product ID: <input type='number' name='product_id' value='" . (isset($_GET['product_id']) ? $_GET['product_id'] : '') . "' min='1' required></p>";
echo "<button type='submit' style='background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;'>Check Product Images</button>";
echo "</form>";
echo "</div>";

echo "</div></body></html>";
?>

