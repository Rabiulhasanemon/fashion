<?php
/**
 * Simple test script to debug manufacturer add issue
 * Access: https://ruplexa1.master.com.bd/admin/test_manufacturer_add.php
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load OpenCart
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Database - use the same pattern as other working scripts
$db = new \DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Load model
$loader = new Loader($registry);
$registry->set('load', $loader);

$model = $loader->model('catalog/manufacturer');

echo "<!DOCTYPE html><html><head><title>Manufacturer Add Test</title>";
echo "<style>body { font-family: Arial; margin: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; }</style>";
echo "</head><body>";
echo "<h1>Manufacturer Add Test</h1>";

$prefix = DB_PREFIX;

// Test 1: Check if model loaded
echo "<h2>Test 1: Model Loading</h2>";
if ($model) {
    echo "<p class='success'>✓ Model loaded successfully</p>";
} else {
    echo "<p class='error'>❌ Failed to load model</p>";
    die();
}

// Test 2: Check database connection
echo "<h2>Test 2: Database Connection</h2>";
$test_query = $db->query("SELECT 1 as test");
if ($test_query) {
    echo "<p class='success'>✓ Database connection OK</p>";
} else {
    echo "<p class='error'>❌ Database connection failed</p>";
    die();
}

// Test 3: Check for manufacturer_id = 0
echo "<h2>Test 3: Check for manufacturer_id = 0</h2>";
$zero_check = $db->query("SELECT COUNT(*) as count FROM {$prefix}manufacturer WHERE manufacturer_id = 0");
$zero_count = $zero_check && $zero_check->num_rows ? (int)$zero_check->row['count'] : 0;
if ($zero_count > 0) {
    echo "<p class='error'>❌ Found {$zero_count} record(s) with manufacturer_id = 0</p>";
    echo "<p><button onclick='if(confirm(\"Delete manufacturer_id=0 records?\")) { window.location.href=\"?action=cleanup\"; }'>Clean Up Now</button></p>";
} else {
    echo "<p class='success'>✓ No manufacturer_id = 0 records</p>";
}

// Handle cleanup
if (isset($_GET['action']) && $_GET['action'] == 'cleanup') {
    $db->query("DELETE FROM {$prefix}manufacturer WHERE manufacturer_id = 0");
    $db->query("DELETE FROM {$prefix}manufacturer_description WHERE manufacturer_id = 0");
    $db->query("DELETE FROM {$prefix}manufacturer_to_store WHERE manufacturer_id = 0");
    $db->query("DELETE FROM {$prefix}url_alias WHERE query = 'manufacturer_id=0'");
    echo "<p class='success'>✓ Cleanup complete. <a href='?'>Refresh</a></p>";
}

// Test 4: Check AUTO_INCREMENT
echo "<h2>Test 4: AUTO_INCREMENT Check</h2>";
$ai_check = $db->query("SHOW TABLE STATUS LIKE '{$prefix}manufacturer'");
$ai_value = 'N/A';
if ($ai_check && $ai_check->num_rows) {
    $row = $ai_check->row;
    if (isset($row['Auto_increment'])) {
        $ai_value = $row['Auto_increment'];
    } elseif (isset($row['AUTO_INCREMENT'])) {
        $ai_value = $row['AUTO_INCREMENT'];
    }
}
echo "<p class='info'>Current AUTO_INCREMENT: {$ai_value}</p>";

$max_check = $db->query("SELECT MAX(manufacturer_id) as max_id FROM {$prefix}manufacturer WHERE manufacturer_id > 0");
$max_id = 0;
if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
    $max_id = (int)$max_check->row['max_id'];
}
$next_id = max($max_id + 1, 1);
echo "<p class='info'>Max manufacturer_id: {$max_id}</p>";
echo "<p class='info'>Next expected ID: {$next_id}</p>";

// Test 5: Try to add manufacturer
echo "<h2>Test 5: Test Manufacturer Add</h2>";
if (isset($_GET['test']) && $_GET['test'] == 'add') {
    try {
        $test_data = array(
            'name' => 'TEST_MANUFACTURER_' . time(),
            'sort_order' => 0
        );
        
        echo "<p class='info'>Attempting to add manufacturer with data:</p>";
        echo "<pre>" . print_r($test_data, true) . "</pre>";
        
        $manufacturer_id = $model->addManufacturer($test_data);
        
        if ($manufacturer_id > 0) {
            echo "<p class='success'>✓ SUCCESS! Manufacturer added with ID: {$manufacturer_id}</p>";
            
            // Verify it exists
            $verify = $db->query("SELECT * FROM {$prefix}manufacturer WHERE manufacturer_id = '{$manufacturer_id}'");
            if ($verify && $verify->num_rows) {
                echo "<p class='success'>✓ Verified: Record exists in database</p>";
                echo "<pre>" . print_r($verify->row, true) . "</pre>";
                
                // Clean up test record
                echo "<p><button onclick='if(confirm(\"Delete test manufacturer?\")) { window.location.href=\"?test=delete&id={$manufacturer_id}\"; }'>Delete Test Record</button></p>";
            } else {
                echo "<p class='error'>❌ WARNING: addManufacturer returned ID but record not found!</p>";
            }
        } else {
            echo "<p class='error'>❌ FAILED: addManufacturer returned 0 or false</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ EXCEPTION: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p class='error'>File: " . htmlspecialchars($e->getFile()) . " Line: " . $e->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } catch (Error $e) {
        echo "<p class='error'>❌ PHP ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p class='error'>File: " . htmlspecialchars($e->getFile()) . " Line: " . $e->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
} else {
    echo "<p><a href='?test=add'><button>Test Add Manufacturer</button></a></p>";
}

// Handle delete
if (isset($_GET['test']) && $_GET['test'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $db->query("DELETE FROM {$prefix}manufacturer WHERE manufacturer_id = '{$id}'");
    $db->query("DELETE FROM {$prefix}manufacturer_description WHERE manufacturer_id = '{$id}'");
    $db->query("DELETE FROM {$prefix}manufacturer_to_store WHERE manufacturer_id = '{$id}'");
    echo "<p class='success'>✓ Test record deleted. <a href='?'>Refresh</a></p>";
}

// Test 6: Check error logs
echo "<h2>Test 6: Recent Error Logs</h2>";
$log_file = DIR_LOGS . 'manufacturer_error.log';
if (file_exists($log_file)) {
    $log_content = file_get_contents($log_file);
    $log_lines = explode("\n", $log_content);
    $recent_lines = array_slice($log_lines, -30); // Last 30 lines
    if (!empty($recent_lines)) {
        echo "<pre>" . htmlspecialchars(implode("\n", $recent_lines)) . "</pre>";
    } else {
        echo "<p class='info'>Log file is empty</p>";
    }
} else {
    echo "<p class='info'>No error log file found at: {$log_file}</p>";
}

// Test 7: Check PHP errors
echo "<h2>Test 7: PHP Error Log</h2>";
$php_error_log = ini_get('error_log');
echo "<p class='info'>PHP Error Log Location: " . ($php_error_log ? $php_error_log : 'Not set') . "</p>";

echo "<hr>";
echo "<p><a href='index.php?route=catalog/manufacturer/add'>← Back to Add Manufacturer</a></p>";
echo "</body></html>";
?>

