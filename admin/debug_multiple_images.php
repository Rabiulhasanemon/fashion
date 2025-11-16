<?php
/**
 * Debug Multiple Image Upload Script
 * 
 * This script helps diagnose issues with multiple image uploads
 * Run from: admin/debug_multiple_images.php
 * 
 * SECURITY: Delete this file after use!
 */

// Start output buffering
if (!ob_get_level()) {
    ob_start();
}

// Load OpenCart
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Database - use the same pattern as fix_product_image.php
$db = new \DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Debug Multiple Image Upload</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        h2 { color: #666; margin-top: 30px; }
        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .warning { color: #ff9800; font-weight: bold; }
        .info { color: #2196F3; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .code { background: #f4f4f4; padding: 10px; border-radius: 4px; font-family: monospace; margin: 10px 0; }
        button { background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin: 10px 5px; }
        button:hover { background: #45a049; }
        .section { margin: 20px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #4CAF50; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🔍 Debug Multiple Image Upload Issue</h1>";

$prefix = DB_PREFIX;
$logs_dir = DIR_SYSTEM . 'storage/logs/';

// Check current state
echo "<div class='section'>";
echo "<h2>1. Current Database State</h2>";

// Check for product_image_id = 0
$check_zero = $db->query("SELECT COUNT(*) as count FROM {$prefix}product_image WHERE product_image_id = 0");
$zero_count = $check_zero->row['count'];
if ($zero_count > 0) {
    echo "<p class='error'>❌ FOUND: {$zero_count} record(s) with product_image_id = 0</p>";
    $zero_records = $db->query("SELECT * FROM {$prefix}product_image WHERE product_image_id = 0");
    echo "<table><tr><th>product_image_id</th><th>product_id</th><th>image</th><th>sort_order</th></tr>";
    foreach ($zero_records->rows as $row) {
        echo "<tr><td>{$row['product_image_id']}</td><td>{$row['product_id']}</td><td>" . substr($row['image'], 0, 50) . "...</td><td>{$row['sort_order']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p class='success'>✓ No records with product_image_id = 0</p>";
}

// Check AUTO_INCREMENT - try multiple methods
$current_ai = 'N/A';
$ai_check = $db->query("SHOW CREATE TABLE {$prefix}product_image");
if ($ai_check && $ai_check->num_rows) {
    $create_table = isset($ai_check->row['Create Table']) ? $ai_check->row['Create Table'] : (isset($ai_check->row[1]) ? $ai_check->row[1] : '');
    if ($create_table && preg_match('/AUTO_INCREMENT=(\d+)/i', $create_table, $matches)) {
        $current_ai = $matches[1];
    }
}

// If still N/A, try SHOW TABLE STATUS
if ($current_ai == 'N/A') {
    $status_check = $db->query("SHOW TABLE STATUS LIKE '{$prefix}product_image'");
    if ($status_check && $status_check->num_rows && isset($status_check->row['Auto_increment'])) {
        $current_ai = $status_check->row['Auto_increment'];
    }
}

// Get max product_image_id (excluding 0)
$max_check = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image WHERE product_image_id > 0");
$max_id = $max_check->row['max_id'] ?? 0;
$expected_next = max($max_id + 1, 1);

echo "<p class='info'>Current AUTO_INCREMENT: <strong>{$current_ai}</strong></p>";
echo "<p class='info'>Max product_image_id: <strong>{$max_id}</strong></p>";
echo "<p class='info'>Expected next ID: <strong>{$expected_next}</strong></p>";

if ($current_ai != $expected_next && $current_ai != 'N/A') {
    echo "<p class='warning'>⚠️ AUTO_INCREMENT mismatch! Should be {$expected_next} but is {$current_ai}</p>";
} else {
    echo "<p class='success'>✓ AUTO_INCREMENT is correct</p>";
}

echo "</div>";

// Simulate multiple image inserts
echo "<div class='section'>";
echo "<h2>2. Simulate Multiple Image Inserts</h2>";

if (isset($_POST['test_inserts'])) {
    $test_product_id = (int)$_POST['test_product_id'];
    if ($test_product_id <= 0) {
        echo "<p class='error'>❌ Invalid product_id. Please enter a valid product ID.</p>";
    } else {
        // Verify product exists
        $product_check = $db->query("SELECT product_id FROM {$prefix}product WHERE product_id = '{$test_product_id}' LIMIT 1");
        if (!$product_check->num_rows) {
            echo "<p class='error'>❌ Product ID {$test_product_id} does not exist!</p>";
        } else {
            echo "<p class='info'>Testing inserts for product_id: <strong>{$test_product_id}</strong></p>";
            
            // Delete any existing test images for this product
            $db->query("DELETE FROM {$prefix}product_image WHERE product_id = '{$test_product_id}' AND image LIKE 'test_image_%'");
            
            $num_images = 5; // Test with 5 images
            $success_count = 0;
            $fail_count = 0;
            
            echo "<table><tr><th>Image #</th><th>Before Insert</th><th>Insert Result</th><th>Inserted ID</th><th>After Insert</th><th>Status</th></tr>";
            
            for ($i = 1; $i <= $num_images; $i++) {
                // Get state before insert
                $before_max = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image");
                $before_ai = $db->query("SHOW CREATE TABLE {$prefix}product_image");
                $before_ai_val = 'N/A';
                if ($before_ai->row) {
                    $bt = isset($before_ai->row['Create Table']) ? $before_ai->row['Create Table'] : (isset($before_ai->row[1]) ? $before_ai->row[1] : '');
                    if ($bt && preg_match('/AUTO_INCREMENT=(\d+)/i', $bt, $bm)) {
                        $before_ai_val = $bm[1];
                    }
                }
                $before_max_val = $before_max->row['max_id'] ?? 0;
                
                // Delete product_image_id = 0 before insert
                $db->query("DELETE FROM {$prefix}product_image WHERE product_image_id = 0");
                
                // Fix AUTO_INCREMENT
                $fix_max = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image");
                $fix_max_val = $fix_max->row['max_id'] ?? 0;
                $next_id = max($fix_max_val + 1, 2);
                $db->query("ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_id}");
                
                // Insert
                $image_path = "test_image_{$test_product_id}_{$i}.jpg";
                $insert_sql = "INSERT INTO {$prefix}product_image SET product_id = '{$test_product_id}', image = '{$image_path}', sort_order = '{$i}'";
                $result = $db->query($insert_sql);
                
                $inserted_id = $db->getLastId();
                
                // Get state after insert
                $after_max = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image");
                $after_ai = $db->query("SHOW CREATE TABLE {$prefix}product_image");
                $after_ai_val = 'N/A';
                if ($after_ai->row) {
                    $at = isset($after_ai->row['Create Table']) ? $after_ai->row['Create Table'] : (isset($after_ai->row[1]) ? $after_ai->row[1] : '');
                    if ($at && preg_match('/AUTO_INCREMENT=(\d+)/i', $at, $am)) {
                        $after_ai_val = $am[1];
                    }
                }
                $after_max_val = $after_max->row['max_id'] ?? 0;
                
                // Update AUTO_INCREMENT for next insert
                $update_max = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image");
                $update_max_val = $update_max->row['max_id'] ?? 0;
                $next_after = $update_max_val + 1;
                $db->query("ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_after}");
                
                $status = '';
                if ($result && $inserted_id > 0) {
                    $status = "<span class='success'>✓ Success</span>";
                    $success_count++;
                } else {
                    $status = "<span class='error'>❌ Failed</span>";
                    $fail_count++;
                    if (property_exists($db, 'link') && is_object($db->link)) {
                        $error = property_exists($db->link, 'error') ? $db->link->error : 'Unknown error';
                        $errno = property_exists($db->link, 'errno') ? $db->link->errno : 0;
                        $status .= "<br><small>Error: {$error} ({$errno})</small>";
                    }
                }
                
                echo "<tr>
                    <td>{$i}</td>
                    <td>Max: {$before_max_val}<br>AI: {$before_ai_val}</td>
                    <td>" . ($result ? 'OK' : 'FAIL') . "</td>
                    <td>{$inserted_id}</td>
                    <td>Max: {$after_max_val}<br>AI: {$after_ai_val}</td>
                    <td>{$status}</td>
                </tr>";
            }
            
            echo "</table>";
            
            echo "<p class='info'>Results: <strong>{$success_count}</strong> successful, <strong>{$fail_count}</strong> failed out of {$num_images} attempts</p>";
            
            if ($fail_count > 0) {
                echo "<p class='error'>❌ Some inserts failed! Check the table above for details.</p>";
            } else {
                echo "<p class='success'>✓ All test inserts succeeded!</p>";
            }
            
            // Clean up test images
            $db->query("DELETE FROM {$prefix}product_image WHERE product_id = '{$test_product_id}' AND image LIKE 'test_image_%'");
        }
    }
}

echo "<form method='POST' style='margin: 20px 0;'>
    <p><strong>Test Multiple Image Inserts:</strong></p>
    <p>Product ID: <input type='number' name='test_product_id' value='1' min='1' required> (Must be an existing product ID)</p>
    <button type='submit' name='test_inserts'>Run Test (5 images)</button>
</form>";
echo "</div>";

// Check recent logs
echo "<div class='section'>";
echo "<h2>3. Recent Log Files</h2>";

$log_files = [
    'product_insert_debug.log',
    'product_insert_error.log'
];

foreach ($log_files as $log_file) {
    $log_path = $logs_dir . $log_file;
    if (file_exists($log_path)) {
        $log_content = file_get_contents($log_path);
        $lines = explode("\n", $log_content);
        $recent_lines = array_slice($lines, -20); // Last 20 lines
        echo "<h3>{$log_file}</h3>";
        echo "<div class='code'>" . htmlspecialchars(implode("\n", $recent_lines)) . "</div>";
    } else {
        echo "<p class='info'>Log file {$log_file} does not exist yet.</p>";
    }
}

echo "</div>";

// Fix button
echo "<div class='section'>";
echo "<h2>4. Quick Fix</h2>";
echo "<p>If you're experiencing issues, click the button below to clean up and fix AUTO_INCREMENT:</p>";

if (isset($_POST['fix_now'])) {
    // Delete product_image_id = 0
    $delete_result = $db->query("DELETE FROM {$prefix}product_image WHERE product_image_id = 0");
    $deleted = 0;
    if (method_exists($db, 'countAffected')) {
        $deleted = $db->countAffected();
    } else {
        // Fallback: check how many were deleted
        $check_after = $db->query("SELECT COUNT(*) as count FROM {$prefix}product_image WHERE product_image_id = 0");
        $deleted = 1; // We know there was 1 from the check above
    }
    
    // Fix AUTO_INCREMENT - get max excluding 0
    $max_result = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image WHERE product_image_id > 0");
    $max_id = $max_result->row['max_id'] ?? 0;
    $next_id = max($max_id + 1, 1);
    
    $fix_result = $db->query("ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_id}");
    
    if ($fix_result) {
        echo "<p class='success'>✓ Fixed! Deleted {$deleted} record(s) with product_image_id = 0, set AUTO_INCREMENT to {$next_id}</p>";
        echo "<p class='info'>The page will refresh in 2 seconds...</p>";
        echo "<meta http-equiv='refresh' content='2'>";
    } else {
        $error_msg = '';
        if (property_exists($db, 'link') && is_object($db->link)) {
            $error_msg = property_exists($db->link, 'error') ? $db->link->error : 'Unknown error';
        }
        echo "<p class='error'>❌ Error fixing AUTO_INCREMENT: {$error_msg}</p>";
        echo "<p class='warning'>Please run this SQL manually in phpMyAdmin:</p>";
        echo "<div class='code'>DELETE FROM {$prefix}product_image WHERE product_image_id = 0;<br>ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_id};</div>";
    }
}

echo "<form method='POST'>
    <button type='submit' name='fix_now'>Fix AUTO_INCREMENT Now</button>
</form>";
echo "</div>";

echo "</div></body></html>";
?>

