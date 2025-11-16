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
    // Try different possible field names
    $auto_increment = 'N/A';
    if (isset($auto_inc->row['Auto_increment'])) {
        $auto_increment = $auto_inc->row['Auto_increment'];
    } elseif (isset($auto_inc->row['AUTO_INCREMENT'])) {
        $auto_increment = $auto_inc->row['AUTO_INCREMENT'];
    } elseif (isset($auto_inc->row['auto_increment'])) {
        $auto_increment = $auto_inc->row['auto_increment'];
    }
    
    echo "<p>Current AUTO_INCREMENT: <strong>" . $auto_increment . "</strong></p>";
    
    // If N/A, try alternative method
    if ($auto_increment == 'N/A' || $auto_increment == null || $auto_increment == '') {
        echo "<p style='color:orange;'>⚠️ Could not read AUTO_INCREMENT using SHOW TABLE STATUS. Trying alternative method...</p>";
        
        // Alternative: Get next auto increment value
        $next_ai_query = $db->query("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product'");
        if ($next_ai_query && $next_ai_query->num_rows) {
            $auto_increment = isset($next_ai_query->row['AUTO_INCREMENT']) ? $next_ai_query->row['AUTO_INCREMENT'] : 'N/A';
            echo "<p>Alternative method - AUTO_INCREMENT: <strong>" . $auto_increment . "</strong></p>";
        }
        
        // If still N/A, this is a problem
        if ($auto_increment == 'N/A' || $auto_increment == null || $auto_increment == '') {
            echo "<p style='color:red;'><strong>⚠️ CRITICAL:</strong> Cannot determine AUTO_INCREMENT value. This may cause insert errors!</p>";
            echo "<p><strong>Fix:</strong> Run this SQL command in phpMyAdmin:</p>";
            echo "<code>ALTER TABLE " . DB_PREFIX . "product AUTO_INCREMENT = " . ($next_id) . ";</code>";
        }
    } else {
        // Check if AUTO_INCREMENT is correct
        if ($auto_increment < $next_id) {
            echo "<p style='color:red;'><strong>⚠️ WARNING:</strong> AUTO_INCREMENT (" . $auto_increment . ") is less than next expected ID (" . $next_id . ")!</p>";
            echo "<p>This will cause duplicate key errors. <strong>Fix:</strong></p>";
            echo "<code>ALTER TABLE " . DB_PREFIX . "product AUTO_INCREMENT = " . ($next_id) . ";</code>";
        } elseif ($auto_increment == $next_id) {
            echo "<p style='color:green;'>✓ AUTO_INCREMENT is correct.</p>";
        }
    }
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

// Check 6: Check log files
echo "<h3>6. Checking log files:</h3>";
$log_dir = DIR_LOGS;
$debug_log = $log_dir . 'product_insert_debug.log';
$error_log = $log_dir . 'product_insert_error.log';

if (file_exists($debug_log)) {
    $debug_size = filesize($debug_log);
    echo "<p>Debug log exists: <strong>" . $debug_log . "</strong> (" . number_format($debug_size) . " bytes)</p>";
    echo "<p><a href='../system/storage/logs/product_insert_debug.log' target='_blank'>View Debug Log</a></p>";
} else {
    echo "<p style='color:orange;'>Debug log not found: " . $debug_log . "</p>";
}

if (file_exists($error_log)) {
    $error_size = filesize($error_log);
    echo "<p>Error log exists: <strong>" . $error_log . "</strong> (" . number_format($error_size) . " bytes)</p>";
    echo "<p><a href='../system/storage/logs/product_insert_error.log' target='_blank'>View Error Log</a></p>";
    echo "<p><strong>Last 10 lines of error log:</strong></p>";
    echo "<pre style='background:#f5f5f5;padding:10px;max-height:200px;overflow:auto;'>";
    $lines = file($error_log);
    echo htmlspecialchars(implode('', array_slice($lines, -10)));
    echo "</pre>";
} else {
    echo "<p style='color:green;'>✓ No error log found (no errors yet)</p>";
}

// Check 7: Verify product table structure
echo "<h3>7. Checking product table structure:</h3>";
$structure = $db->query("SHOW CREATE TABLE " . DB_PREFIX . "product");
if ($structure && $structure->num_rows) {
    $create_table = isset($structure->row['Create Table']) ? $structure->row['Create Table'] : (isset($structure->row[1]) ? $structure->row[1] : '');
    if ($create_table) {
        // Check if AUTO_INCREMENT is in the CREATE TABLE statement
        if (stripos($create_table, 'AUTO_INCREMENT') !== false) {
            preg_match('/AUTO_INCREMENT=(\d+)/i', $create_table, $matches);
            if (isset($matches[1])) {
                $ai_from_structure = $matches[1];
                echo "<p>AUTO_INCREMENT from table structure: <strong>" . $ai_from_structure . "</strong></p>";
                if ($ai_from_structure < $next_product_id) {
                    echo "<p style='color:red;'><strong>⚠️ PROBLEM FOUND:</strong> AUTO_INCREMENT in table structure (" . $ai_from_structure . ") is less than next expected ID (" . $next_product_id . ")!</p>";
                }
            }
        } else {
            echo "<p style='color:orange;'>⚠️ AUTO_INCREMENT not found in CREATE TABLE statement. This is unusual.</p>";
        }
    }
}

// Recommendations
echo "<hr>";
echo "<h3>How to Fix:</h3>";
echo "<ol>";
echo "<li><strong>If product_id = 0 exists:</strong><br>";
echo "<code>DELETE FROM " . DB_PREFIX . "product WHERE product_id = 0;</code></li>";
echo "<li><strong>Fix AUTO_INCREMENT (IMPORTANT - Run this first):</strong><br>";
echo "<code>ALTER TABLE " . DB_PREFIX . "product AUTO_INCREMENT = " . ($next_product_id) . ";</code></li>";
echo "<li><strong>Delete orphaned records:</strong><br>";
echo "<code>DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = 0;<br>";
echo "DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = 0;<br>";
echo "DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = 0;<br>";
echo "DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = 0;<br>";
echo "DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=0';</code></li>";
echo "<li><strong>Check log files:</strong> Look in <code>" . $log_dir . "</code> for detailed error messages</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>⚠️ SECURITY WARNING:</strong> Delete this file (<code>admin/test_product_insert.php</code>) after debugging!</p>";
?>

