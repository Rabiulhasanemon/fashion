<?php
// Test script to debug product insertion
// Place this in your admin root and access via browser

// Include OpenCart configuration
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$config->load('default');
$config->load('admin');
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Load language
$language = new Language('english');
$language->load('english');
$registry->set('language', $language);

// Load model
require_once(DIR_APPLICATION . 'model/catalog/product.php');
$model = new ModelCatalogProduct($registry);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Product Add Test</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;} .container{max-width:1200px;margin:0 auto;background:white;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);} h2{color:#333;border-bottom:2px solid #007bff;padding-bottom:10px;} h3{color:#555;margin-top:20px;} .success{color:green;font-weight:bold;} .error{color:red;font-weight:bold;} .info{background:#e7f3ff;padding:10px;border-left:4px solid #2196F3;margin:10px 0;} pre{background:#f8f8f8;padding:10px;border-radius:4px;overflow-x:auto;}</style>";
echo "</head><body><div class='container'>";

echo "<h2>Product Add Debug Test</h2>";

// Test 1: Check for product_id = 0 records
echo "<h3>Test 1: Check for product_id = 0 records</h3>";
$check_zero = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product WHERE product_id = 0");
if ($check_zero && $check_zero->num_rows) {
    $count = (int)$check_zero->row['count'];
    if ($count > 0) {
        echo "<p class='error'>✗ Found $count record(s) with product_id = 0</p>";
        echo "<div class='info'><strong>Action:</strong> These will be cleaned up automatically.</div>";
    } else {
        echo "<p class='success'>✓ No product_id = 0 records found</p>";
    }
} else {
    echo "<p class='error'>✗ Could not check for product_id = 0 records</p>";
}

// Test 2: Check AUTO_INCREMENT
echo "<h3>Test 2: Check AUTO_INCREMENT</h3>";
$auto_inc = $db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "product'");
if ($auto_inc && $auto_inc->num_rows) {
    $auto_increment = isset($auto_inc->row['Auto_increment']) ? $auto_inc->row['Auto_increment'] : 'N/A';
    echo "<p>Current AUTO_INCREMENT: <strong>$auto_increment</strong></p>";
    
    $max_check = $db->query("SELECT MAX(product_id) as max_id FROM " . DB_PREFIX . "product WHERE product_id > 0");
    $max_id = 0;
    if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
        $max_id = (int)$max_check->row['max_id'];
    }
    $next_id = max($max_id + 1, 1);
    echo "<p>Max product_id: <strong>$max_id</strong></p>";
    echo "<p>Next expected product_id: <strong>$next_id</strong></p>";
    
    if ($auto_increment != 'N/A' && (int)$auto_increment < $next_id) {
        echo "<p class='error'>✗ AUTO_INCREMENT ($auto_increment) is less than expected next ID ($next_id)</p>";
        echo "<div class='info'><strong>Action:</strong> AUTO_INCREMENT will be fixed automatically.</div>";
    } else {
        echo "<p class='success'>✓ AUTO_INCREMENT looks correct</p>";
    }
} else {
    echo "<p class='error'>✗ Could not check AUTO_INCREMENT</p>";
}

// Test 3: Try to insert a test product
echo "<h3>Test 3: Try to insert a test product</h3>";

// Clean up any existing test products first
$db->query("DELETE FROM " . DB_PREFIX . "product WHERE model = 'TEST_MODEL_DEBUG'");

// Prepare test data
$test_data = array(
    'model' => 'TEST_MODEL_DEBUG',
    'sku' => 'TEST_SKU_DEBUG',
    'quantity' => 1,
    'minimum' => 1,
    'status' => 0, // Set to 0 so it doesn't show in frontend
    'price' => 0,
    'product_description' => array(
        1 => array( // Assuming language_id 1
            'name' => 'Test Product Debug',
            'description' => 'This is a test product for debugging',
            'meta_title' => 'Test Product Debug',
            'meta_description' => 'Test',
            'meta_keyword' => 'test'
        )
    ),
    'product_store' => array(0),
    'product_category' => array(),
    'product_image' => array()
);

try {
    echo "<p>Attempting to insert test product...</p>";
    $product_id = $model->addProduct($test_data);
    
    if ($product_id && $product_id > 0) {
        echo "<p class='success'>✓ Product inserted successfully! Product ID: <strong>$product_id</strong></p>";
        
        // Verify it exists
        $verify = $db->query("SELECT product_id, model FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
        if ($verify && $verify->num_rows) {
            echo "<p class='success'>✓ Product verified in database</p>";
            echo "<pre>Product ID: " . $verify->row['product_id'] . "\nModel: " . $verify->row['model'] . "</pre>";
        } else {
            echo "<p class='error'>✗ Product ID returned but product not found in database!</p>";
        }
        
        // Clean up test product
        echo "<h3>Cleanup</h3>";
        $db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
        echo "<p class='success'>✓ Test product cleaned up</p>";
    } else {
        echo "<p class='error'>✗ Product insertion failed! Product ID returned: " . ($product_id ? $product_id : 'NULL') . "</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Exception occurred: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

// Test 4: Check log files
echo "<h3>Test 4: Check Log Files</h3>";
$log_file = DIR_LOGS . 'product_insert_debug.log';
if (file_exists($log_file)) {
    $log_content = file_get_contents($log_file);
    $log_lines = explode("\n", $log_content);
    $recent_lines = array_slice($log_lines, -20); // Last 20 lines
    echo "<p>Recent log entries (last 20 lines):</p>";
    echo "<pre>" . htmlspecialchars(implode("\n", $recent_lines)) . "</pre>";
} else {
    echo "<p class='info'>Log file not found: $log_file</p>";
}

$error_log_file = DIR_LOGS . 'product_insert_error.log';
if (file_exists($error_log_file)) {
    $error_content = file_get_contents($error_log_file);
    $error_lines = explode("\n", $error_content);
    $recent_errors = array_slice($error_lines, -20); // Last 20 lines
    echo "<p>Recent error log entries (last 20 lines):</p>";
    echo "<pre>" . htmlspecialchars(implode("\n", $recent_errors)) . "</pre>";
} else {
    echo "<p class='info'>Error log file not found: $error_log_file</p>";
}

echo "</div></body></html>";
?>

