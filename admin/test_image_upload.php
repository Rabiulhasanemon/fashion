<?php
/**
 * Test Multiple Image Upload
 * 
 * This script tests if multiple image uploads work correctly
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
    <title>Test Multiple Image Upload</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        .success { color: #4CAF50; font-weight: bold; padding: 10px; background: #e8f5e9; border-left: 4px solid #4CAF50; margin: 10px 0; }
        .error { color: #f44336; font-weight: bold; padding: 10px; background: #ffebee; border-left: 4px solid #f44336; margin: 10px 0; }
        .info { color: #2196F3; padding: 10px; background: #e3f2fd; border-left: 4px solid #2196F3; margin: 10px 0; }
        .code { background: #f4f4f4; padding: 15px; border-radius: 4px; font-family: monospace; margin: 15px 0; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🧪 Test Multiple Image Upload</h1>";

// Step 1: Check current state
echo "<div class='info'>";
echo "<h2>Step 1: Current Database State</h2>";

// Check for product_image_id = 0
$check_zero = $db->query("SELECT COUNT(*) as count FROM {$prefix}product_image WHERE product_image_id = 0");
$zero_count = $check_zero->row['count'] ?? 0;

if ($zero_count > 0) {
    echo "<p class='error'>❌ Found {$zero_count} record(s) with product_image_id = 0</p>";
    echo "<p><strong>FIX REQUIRED:</strong></p>";
    
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
        
        // Fix AUTO_INCREMENT
        $max_result = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image WHERE product_image_id > 0");
        $max_id = $max_result->row['max_id'] ?? 0;
        $next_id = max($max_id + 1, 1);
        
        $fix_result = $db->query("ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_id}");
        
        if ($fix_result !== false) {
            echo "<p class='success'>✓ Set AUTO_INCREMENT to {$next_id}</p>";
            echo "<p class='success'><strong>✅ FIXED! Page will refresh in 2 seconds...</strong></p>";
            echo "<meta http-equiv='refresh' content='2'>";
        } else {
            echo "<p class='error'>❌ Error setting AUTO_INCREMENT. Please run SQL manually.</p>";
        }
        
        echo "</div>";
    } else {
        echo "<form method='POST' style='margin: 15px 0;'>";
        echo "<button type='submit' name='auto_fix' style='background: #4CAF50; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold;'>🔧 Auto-Fix Now</button>";
        echo "</form>";
        echo "<p>Or run this SQL manually in phpMyAdmin:</p>";
        echo "<div class='code'>DELETE FROM {$prefix}product_image WHERE product_image_id = 0;<br>ALTER TABLE {$prefix}product_image AUTO_INCREMENT = 1;</div>";
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

// Get max
$max_check = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image WHERE product_image_id > 0");
$max_id = $max_check->row['max_id'] ?? 0;
$expected_next = max($max_id + 1, 1);

echo "<p><strong>Current AUTO_INCREMENT:</strong> {$current_ai}</p>";
echo "<p><strong>Max product_image_id:</strong> {$max_id}</p>";
echo "<p><strong>Expected next ID:</strong> {$expected_next}</p>";

if ($current_ai != 'N/A' && $current_ai != $expected_next) {
    echo "<p class='error'>⚠️ AUTO_INCREMENT mismatch! Should be {$expected_next} but is {$current_ai}</p>";
}

echo "</div>";

// Step 2: Test insert
if (isset($_POST['test_insert']) && $zero_count == 0) {
    echo "<div class='info'>";
    echo "<h2>Step 2: Testing Multiple Image Inserts</h2>";
    
    $test_product_id = (int)$_POST['test_product_id'];
    
    if ($test_product_id <= 0) {
        echo "<p class='error'>Invalid product ID</p>";
    } else {
        // Verify product exists
        $product_check = $db->query("SELECT product_id FROM {$prefix}product WHERE product_id = '{$test_product_id}' LIMIT 1");
        if (!$product_check->num_rows) {
            echo "<p class='error'>Product ID {$test_product_id} does not exist!</p>";
        } else {
            // Delete test images
            $db->query("DELETE FROM {$prefix}product_image WHERE product_id = '{$test_product_id}' AND image LIKE 'test_image_%'");
            
            $num_images = 5;
            $success = 0;
            $failed = 0;
            
            echo "<table>";
            echo "<tr><th>Image #</th><th>Before AI</th><th>Insert Result</th><th>Inserted ID</th><th>After AI</th><th>Status</th></tr>";
            
            for ($i = 1; $i <= $num_images; $i++) {
                // Get before state
                $before_ai = $db->query("SHOW TABLE STATUS LIKE '{$prefix}product_image'");
                $before_val = 'N/A';
                if ($before_ai && $before_ai->num_rows) {
                    $row = $before_ai->row;
                    if (isset($row['Auto_increment'])) {
                        $before_val = $row['Auto_increment'];
                    } elseif (isset($row['AUTO_INCREMENT'])) {
                        $before_val = $row['AUTO_INCREMENT'];
                    }
                }
                
                // Delete product_image_id = 0
                $db->query("DELETE FROM {$prefix}product_image WHERE product_image_id = 0");
                
                // Fix AUTO_INCREMENT
                $max_before = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image WHERE product_image_id > 0");
                $max_before_id = $max_before->row['max_id'] ?? 0;
                $next_before = max($max_before_id + 1, 1);
                $db->query("ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_before}");
                
                // Insert
                $image_path = "test_image_{$test_product_id}_{$i}.jpg";
                $insert_sql = "INSERT INTO {$prefix}product_image SET product_id = '{$test_product_id}', image = '{$image_path}', sort_order = '{$i}'";
                $result = $db->query($insert_sql);
                
                $inserted_id = $db->getLastId();
                
                // Get after state
                $after_ai = $db->query("SHOW TABLE STATUS LIKE '{$prefix}product_image'");
                $after_val = 'N/A';
                if ($after_ai && $after_ai->num_rows) {
                    $row = $after_ai->row;
                    if (isset($row['Auto_increment'])) {
                        $after_val = $row['Auto_increment'];
                    } elseif (isset($row['AUTO_INCREMENT'])) {
                        $after_val = $row['AUTO_INCREMENT'];
                    }
                }
                
                // Update AUTO_INCREMENT for next
                $max_after = $db->query("SELECT MAX(product_image_id) as max_id FROM {$prefix}product_image WHERE product_image_id > 0");
                $max_after_id = $max_after->row['max_id'] ?? 0;
                $next_after = max($max_after_id + 1, 1);
                $db->query("ALTER TABLE {$prefix}product_image AUTO_INCREMENT = {$next_after}");
                
                $status = '';
                if ($result && $inserted_id > 0) {
                    $status = "<span style='color:green;'>✓ Success</span>";
                    $success++;
                } else {
                    $status = "<span style='color:red;'>❌ Failed</span>";
                    $failed++;
                    if (property_exists($db, 'link') && is_object($db->link)) {
                        $error = property_exists($db->link, 'error') ? $db->link->error : '';
                        $errno = property_exists($db->link, 'errno') ? $db->link->errno : 0;
                        $status .= "<br><small>Error: {$error} ({$errno})</small>";
                    }
                }
                
                echo "<tr>
                    <td>{$i}</td>
                    <td>{$before_val}</td>
                    <td>" . ($result ? 'OK' : 'FAIL') . "</td>
                    <td>{$inserted_id}</td>
                    <td>{$after_val}</td>
                    <td>{$status}</td>
                </tr>";
            }
            
            echo "</table>";
            
            echo "<p><strong>Results:</strong> {$success} successful, {$failed} failed out of {$num_images} attempts</p>";
            
            if ($failed == 0) {
                echo "<p class='success'>✅ All test inserts succeeded! Multiple image upload should work now.</p>";
            } else {
                echo "<p class='error'>❌ Some inserts failed. Check the errors above.</p>";
            }
            
            // Clean up
            $db->query("DELETE FROM {$prefix}product_image WHERE product_id = '{$test_product_id}' AND image LIKE 'test_image_%'");
        }
    }
    
    echo "</div>";
}

// Test form
if ($zero_count == 0) {
    echo "<div class='info'>";
    echo "<h2>Step 2: Test Multiple Image Inserts</h2>";
    echo "<form method='POST'>";
    echo "<p>Product ID: <input type='number' name='test_product_id' value='1' min='1' required> (Must be an existing product)</p>";
    echo "<button type='submit' name='test_insert' style='background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;'>Test 5 Image Inserts</button>";
    echo "</form>";
    echo "</div>";
}

echo "</div></body></html>";
?>

