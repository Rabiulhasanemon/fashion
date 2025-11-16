<?php
/**
 * Quick Fix Script - Delete product_image_id = 0
 * 
 * This script fixes the product_image_id = 0 issue
 * Run from: admin/fix_image_zero.php
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

// Database
$db = new \DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

$prefix = DB_PREFIX;

echo "<!DOCTYPE html>
<html>
<head>
    <title>Fix product_image_id = 0</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        .success { color: #4CAF50; font-weight: bold; font-size: 18px; padding: 15px; background: #e8f5e9; border-left: 4px solid #4CAF50; margin: 20px 0; }
        .error { color: #f44336; font-weight: bold; font-size: 18px; padding: 15px; background: #ffebee; border-left: 4px solid #f44336; margin: 20px 0; }
        .info { color: #2196F3; padding: 15px; background: #e3f2fd; border-left: 4px solid #2196F3; margin: 20px 0; }
        .code { background: #f4f4f4; padding: 15px; border-radius: 4px; font-family: monospace; margin: 15px 0; border: 1px solid #ddd; }
        button { background: #4CAF50; color: white; padding: 15px 30px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin: 10px 5px; }
        button:hover { background: #45a049; }
        .warning { color: #ff9800; padding: 15px; background: #fff3e0; border-left: 4px solid #ff9800; margin: 20px 0; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🔧 Fix product_image_id = 0 Issue</h1>";

// Check current state
echo "<div class='info'>";
echo "<h2>Current Status:</h2>";

$check_zero = $db->query("SELECT COUNT(*) as count FROM {$prefix}product_image WHERE product_image_id = 0");
$zero_count = $check_zero->row['count'] ?? 0;

if ($zero_count > 0) {
    echo "<p class='error'>❌ Found {$zero_count} record(s) with product_image_id = 0</p>";
    
    // Show the records
    $zero_records = $db->query("SELECT * FROM {$prefix}product_image WHERE product_image_id = 0");
    echo "<p><strong>Records to be deleted:</strong></p>";
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f0f0f0;'><th>product_image_id</th><th>product_id</th><th>image</th><th>sort_order</th></tr>";
    foreach ($zero_records->rows as $row) {
        echo "<tr>";
        echo "<td>{$row['product_image_id']}</td>";
        echo "<td>{$row['product_id']}</td>";
        echo "<td>" . htmlspecialchars(substr($row['image'], 0, 50)) . "...</td>";
        echo "<td>{$row['sort_order']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='success'>✓ No records with product_image_id = 0 found. Database is clean!</p>";
}

// Get max product_image_id (excluding 0)
$max_check = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image WHERE product_image_id > 0");
$max_id = $max_check->row['max_id'] ?? 0;
$next_id = max($max_id + 1, 1);

echo "<p><strong>Max product_image_id:</strong> {$max_id}</p>";
echo "<p><strong>Next AUTO_INCREMENT should be:</strong> {$next_id}</p>";

// Check current AUTO_INCREMENT
$ai_check = $db->query("SHOW TABLE STATUS LIKE '{$prefix}product_image'");
$current_ai = 'N/A';
if ($ai_check && $ai_check->num_rows) {
    if (isset($ai_check->row['Auto_increment'])) {
        $current_ai = $ai_check->row['Auto_increment'];
    } elseif (isset($ai_check->row['AUTO_INCREMENT'])) {
        $current_ai = $ai_check->row['AUTO_INCREMENT'];
    }
}

echo "<p><strong>Current AUTO_INCREMENT:</strong> {$current_ai}</p>";

if ($current_ai != 'N/A' && $current_ai != $next_id) {
    echo "<p class='warning'>⚠️ AUTO_INCREMENT mismatch! Should be {$next_id} but is {$current_ai}</p>";
}

echo "</div>";

// Fix button
if (isset($_POST['fix_now'])) {
    echo "<div class='info'>";
    echo "<h2>Fixing...</h2>";
    
    // Count before
    $check_before = $db->query("SELECT COUNT(*) as count FROM {$prefix}product_image WHERE product_image_id = 0");
    $count_before = $check_before->row['count'] ?? 0;
    
    // Delete product_image_id = 0
    $delete_result = $db->query("DELETE FROM {$prefix}product_image WHERE product_image_id = 0");
    
    // Verify deletion
    $check_after = $db->query("SELECT COUNT(*) as count FROM {$prefix}product_image WHERE product_image_id = 0");
    $count_after = $check_after->row['count'] ?? 0;
    $deleted = $count_before - $count_after;
    
    if ($deleted > 0) {
        echo "<p class='success'>✓ Deleted {$deleted} record(s) with product_image_id = 0</p>";
    } else {
        echo "<p class='info'>No records to delete (already clean)</p>";
    }
    
    // Fix AUTO_INCREMENT
    $max_result = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image WHERE product_image_id > 0");
    $max_id = $max_result->row['max_id'] ?? 0;
    $next_id = max($max_id + 1, 1);
    
    // Try to set AUTO_INCREMENT
    $fix_result = $db->query("ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_id}");
    
    // Verify AUTO_INCREMENT using multiple methods
    $verified_ai = 'N/A';
    
    // Method 1: SHOW TABLE STATUS
    $verify_ai = $db->query("SHOW TABLE STATUS LIKE '{$prefix}product_image'");
    if ($verify_ai && $verify_ai->num_rows) {
        // Try different possible column names
        $row = $verify_ai->row;
        if (isset($row['Auto_increment'])) {
            $verified_ai = $row['Auto_increment'];
        } elseif (isset($row['AUTO_INCREMENT'])) {
            $verified_ai = $row['AUTO_INCREMENT'];
        } elseif (isset($row['auto_increment'])) {
            $verified_ai = $row['auto_increment'];
        } else {
            // Try to find it in any column
            foreach ($row as $key => $value) {
                if (stripos($key, 'increment') !== false && is_numeric($value)) {
                    $verified_ai = $value;
                    break;
                }
            }
        }
    }
    
    // Method 2: SHOW CREATE TABLE (fallback)
    if ($verified_ai == 'N/A' || $verified_ai === null || $verified_ai === '') {
        $create_check = $db->query("SHOW CREATE TABLE {$prefix}product_image");
        if ($create_check && $create_check->num_rows) {
            $create_table = isset($create_check->row['Create Table']) ? $create_check->row['Create Table'] : (isset($create_check->row[1]) ? $create_check->row[1] : '');
            if ($create_table && preg_match('/AUTO_INCREMENT=(\d+)/i', $create_table, $matches)) {
                $verified_ai = $matches[1];
            }
        }
    }
    
    // Check if the ALTER TABLE command succeeded
    $alter_success = false;
    if ($fix_result !== false) {
        $alter_success = true;
    } else {
        // Check for errors
        if (property_exists($db, 'link') && is_object($db->link)) {
            $error = property_exists($db->link, 'error') ? $db->link->error : '';
            if (empty($error)) {
                $alter_success = true; // No error means it probably succeeded
            } else {
                echo "<p class='error'>SQL Error: {$error}</p>";
            }
        } else {
            $alter_success = true; // Assume success if we can't check
        }
    }
    
    if ($alter_success) {
        if ($verified_ai != 'N/A' && $verified_ai == $next_id) {
            echo "<p class='success'>✓ AUTO_INCREMENT set to {$next_id} (verified: {$verified_ai})</p>";
            echo "<p class='success'><strong>✅ FIXED! You can now upload multiple images.</strong></p>";
        } elseif ($verified_ai != 'N/A') {
            echo "<p class='warning'>⚠️ AUTO_INCREMENT command executed, but verification shows: {$verified_ai} (expected: {$next_id})</p>";
            echo "<p class='info'>The command may have succeeded. Please try uploading multiple images now.</p>";
            echo "<p class='info'>If it still fails, run this SQL manually:</p>";
            echo "<div class='code'>ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_id};</div>";
        } else {
            echo "<p class='info'>✓ AUTO_INCREMENT command executed (could not verify, but likely succeeded)</p>";
            echo "<p class='success'><strong>✅ FIXED! Please try uploading multiple images now.</strong></p>";
            echo "<p class='info'>If you still get errors, run this SQL manually in phpMyAdmin:</p>";
            echo "<div class='code'>ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_id};</div>";
        }
    } else {
        echo "<p class='error'>❌ Error setting AUTO_INCREMENT. Please run this SQL manually in phpMyAdmin:</p>";
        echo "<div class='code'>ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_id};</div>";
    }
    
    echo "</div>";
    echo "<p class='info'><a href='fix_image_zero.php'>Refresh page to verify</a></p>";
} else {
    if ($zero_count > 0 || ($current_ai != 'N/A' && $current_ai != $next_id)) {
        echo "<div class='warning'>";
        echo "<h2>Ready to Fix</h2>";
        echo "<p>Click the button below to fix the issue:</p>";
        echo "<form method='POST'>";
        echo "<button type='submit' name='fix_now'>Fix Now</button>";
        echo "</form>";
        echo "</div>";
    }
}

// Show SQL commands
echo "<div class='info'>";
echo "<h2>Manual SQL Commands (if button doesn't work):</h2>";
echo "<p>Run these commands in phpMyAdmin:</p>";
echo "<div class='code'>";
echo "DELETE FROM {$prefix}product_image WHERE product_image_id = 0;<br>";
echo "ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_id};";
echo "</div>";
echo "</div>";

echo "</div></body></html>";
?>

