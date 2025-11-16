<?php
/**
 * Product Insert Test & Debug Script
 * 
 * This script helps debug the "Duplicate entry '0' for key 'PRIMARY'" error
 * Run this from: admin/test_product_insert.php
 * 
 * SECURITY: Delete this file after debugging!
 */

// Load OpenCart
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new \DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);
    
    // Check for product_id = 0
    echo "<h2>Product Insert Debug Report</h2>";
    echo "<hr>";
    
    // Check 1: Look for product_id = 0
    echo "<h3>1. Checking for product_id = 0 records:</h3>";
    $check_zero = $db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = 0 LIMIT 1");
    if ($check_zero && $check_zero->num_rows) {
        echo "<p style='color:red;'><strong>ERROR:</strong> Found product with product_id = 0!</p>";
        echo "<p>This must be deleted manually from the database.</p>";
        echo "<p><strong>SQL to fix:</strong> DELETE FROM " . DB_PREFIX . "product WHERE product_id = 0;</p>";
    } else {
        echo "<p style='color:green;'>✓ No product with product_id = 0 found.</p>";
    }
    
    // Check 2: Check auto_increment value
    echo "<h3>2. Checking AUTO_INCREMENT value:</h3>";
    $auto_inc = $db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "product'");
    if ($auto_inc && $auto_inc->num_rows) {
        $auto_increment = isset($auto_inc->row['Auto_increment']) ? $auto_inc->row['Auto_increment'] : 'N/A';
        echo "<p>Current AUTO_INCREMENT: <strong>" . $auto_increment . "</strong></p>";
    }
    
    // Check 3: Get max product_id
    echo "<h3>3. Checking MAX product_id:</h3>";
    $max_check = $db->query("SELECT MAX(product_id) as max_id FROM " . DB_PREFIX . "product");
    if ($max_check && $max_check->num_rows) {
        $max_id = isset($max_check->row['max_id']) ? $max_check->row['max_id'] : 0;
        echo "<p>Maximum product_id: <strong>" . $max_id . "</strong></p>";
        $next_id = $max_id + 1;
        echo "<p>Next expected product_id: <strong>" . $next_id . "</strong></p>";
    }
    
    // Check 4: Check for orphaned records
    echo "<h3>4. Checking for orphaned records (product_id = 0):</h3>";
    $tables = array('product_image', 'product_description', 'product_to_store', 'product_to_category', 'url_alias');
    foreach ($tables as $table) {
        $check = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . $table . " WHERE product_id = 0");
        if ($check && $check->num_rows) {
            $count = isset($check->row['count']) ? $check->row['count'] : 0;
            if ($count > 0) {
                echo "<p style='color:orange;'>⚠ Found <strong>" . $count . "</strong> orphaned records in " . $table . " with product_id = 0</p>";
            } else {
                echo "<p style='color:green;'>✓ No orphaned records in " . $table . "</p>";
            }
        }
    }
    
    // Check 5: Test insert (dry run)
    echo "<h3>5. Testing INSERT (dry run - will not actually insert):</h3>";
    $test_sql = "INSERT INTO " . DB_PREFIX . "product SET 
        model = 'TEST_MODEL_" . time() . "',
        sku = 'TEST_SKU_" . time() . "',
        quantity = '1',
        status = '1',
        date_added = NOW(),
        date_modified = NOW()";
    
    echo "<p>Test SQL:</p>";
    echo "<pre>" . htmlspecialchars($test_sql) . "</pre>";
    
    // Check if we can determine next ID
    $max_query = $db->query("SELECT MAX(product_id) AS max_id FROM " . DB_PREFIX . "product");
    $next_product_id = 1;
    if ($max_query && $max_query->num_rows && isset($max_query->row['max_id']) && $max_query->row['max_id'] !== null) {
        $next_product_id = (int)$max_query->row['max_id'] + 1;
    }
    
    // Check if this ID already exists
    $check_exists = $db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$next_product_id . "' LIMIT 1");
    if ($check_exists && $check_exists->num_rows) {
        echo "<p style='color:red;'><strong>ERROR:</strong> Next product_id (" . $next_product_id . ") already exists! This will cause duplicate key error.</p>";
    } else {
        echo "<p style='color:green;'>✓ Next product_id (" . $next_product_id . ") is available.</p>";
    }
    
    // Recommendations
    echo "<hr>";
    echo "<h3>Recommendations:</h3>";
    echo "<ol>";
    echo "<li>If product_id = 0 exists, delete it: <code>DELETE FROM " . DB_PREFIX . "product WHERE product_id = 0;</code></li>";
    echo "<li>Fix AUTO_INCREMENT: <code>ALTER TABLE " . DB_PREFIX . "product AUTO_INCREMENT = " . ($next_product_id) . ";</code></li>";
    echo "<li>Delete orphaned records: <code>DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = 0;</code> (and similar for other tables)</li>";
    echo "<li>Check database logs for more detailed error messages</li>";
    echo "</ol>";
    
    echo "<hr>";
    echo "<p><strong>Note:</strong> This is a diagnostic script. Delete it after debugging for security.</p>";
} else {
    die("Access denied. This script should be run directly.");
}
?>

