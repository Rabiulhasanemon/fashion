<?php
// Test manufacturer add with real form data simulation
// Access via: https://ruplexa1.master.com.bd/admin/test_manufacturer_add_real.php

// Load OpenCart framework
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

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Log
$log = new Log('test_manufacturer.log');
$registry->set('log', $log);

// Cache (required by model)
$cache = new Cache('file', 3600);
$registry->set('cache', $cache);

// Session (minimal)
$session = new Session();
$registry->set('session', $session);

// Load the model
$loader->model('catalog/manufacturer');
$model = $registry->get('model_catalog_manufacturer');

echo "<h1>Test Manufacturer Add - Real Form Data Simulation</h1>";
echo "<style>
body { font-family: Arial; margin: 20px; } 
.success { color: green; font-weight: bold; } 
.error { color: red; font-weight: bold; } 
.info { color: blue; } 
pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
.step { background: #e8f4f8; padding: 15px; margin: 10px 0; border-left: 4px solid #2196F3; }
</style>";

$prefix = DB_PREFIX;

// Simulate the exact POST data that would come from the form
$test_data = array(
    'name' => 'TEST_REAL_' . time(),
    'image' => '',
    'thumb' => '',
    'sort_order' => '0',
    'manufacturer_store' => array(0), // This is what the form sends
    'keyword' => '',
    'manufacturer_layout' => array(),
    'manufacturer_description' => array() // Empty array like the form
);

echo "<div class='step'><h2>Step 1: Simulated Form Data</h2>";
echo "<pre>" . print_r($test_data, true) . "</pre>";
echo "</div>";

// Check current state
echo "<div class='step'><h2>Step 2: Current Database State</h2>";
$max_check = $db->query("SELECT MAX(manufacturer_id) as max_id FROM " . $prefix . "manufacturer WHERE manufacturer_id > 0");
$max_id = 0;
if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
    $max_id = (int)$max_check->row['max_id'];
}
echo "<p>Max manufacturer_id: <strong>{$max_id}</strong></p>";

$zero_check = $db->query("SELECT COUNT(*) as count FROM " . $prefix . "manufacturer WHERE manufacturer_id = 0");
$zero_count = $zero_check && $zero_check->num_rows ? (int)$zero_check->row['count'] : 0;
echo "<p>Records with manufacturer_id = 0: <strong>{$zero_count}</strong></p>";

if ($zero_count > 0) {
    echo "<p class='error'>❌ Found {$zero_count} record(s) with ID 0. Cleaning up...</p>";
    $db->query("DELETE FROM " . $prefix . "manufacturer WHERE manufacturer_id = 0");
    $db->query("DELETE FROM " . $prefix . "manufacturer_description WHERE manufacturer_id = 0");
    $db->query("DELETE FROM " . $prefix . "manufacturer_to_store WHERE manufacturer_id = 0");
    echo "<p class='success'>✓ Cleaned up</p>";
}
echo "</div>";

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

echo "<div class='step'><h2>Step 3: Calling addManufacturer()</h2>";
echo "<p>Calling model->addManufacturer() with test data...</p>";

try {
    // Enable output buffering to catch any output
    ob_start();
    
    $start_time = microtime(true);
    $manufacturer_id = $model->addManufacturer($test_data);
    $end_time = microtime(true);
    $execution_time = round(($end_time - $start_time) * 1000, 2);
    
    $output = ob_get_clean();
    
    echo "<p class='success'>✓ Function completed in {$execution_time}ms</p>";
    echo "<p class='success'>✓ Returned manufacturer_id: <strong>{$manufacturer_id}</strong></p>";
    
    if (!empty($output)) {
        echo "<p class='info'>Output captured:</p>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    }
    
    // Verify the record
    $verify = $db->query("SELECT * FROM " . $prefix . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
    if ($verify && $verify->num_rows) {
        echo "<p class='success'>✓ Record verified in database</p>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        foreach ($verify->row as $key => $value) {
            echo "<tr><td>{$key}</td><td>" . htmlspecialchars($value) . "</td></tr>";
        }
        echo "</table>";
        
        // Clean up
        $db->query("DELETE FROM " . $prefix . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        $db->query("DELETE FROM " . $prefix . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        $db->query("DELETE FROM " . $prefix . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        echo "<p class='info'>Test record cleaned up.</p>";
    } else {
        echo "<p class='error'>❌ Record not found in database!</p>";
    }
    
} catch (Exception $e) {
    $output = ob_get_clean();
    
    echo "<p class='error'>❌ Exception caught!</p>";
    echo "<p class='error'><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p class='error'><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p class='error'><strong>Line:</strong> " . $e->getLine() . "</p>";
    
    if (!empty($output)) {
        echo "<p class='info'>Output before exception:</p>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    }
    
    echo "<p class='info'><strong>Stack Trace:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    
    // Check what happened in the database
    $check_after = $db->query("SELECT * FROM " . $prefix . "manufacturer WHERE manufacturer_id = 0");
    if ($check_after && $check_after->num_rows > 0) {
        echo "<p class='error'>❌ A record with manufacturer_id = 0 was created!</p>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>manufacturer_id</th><th>name</th></tr>";
        foreach ($check_after->rows as $row) {
            echo "<tr><td>" . htmlspecialchars($row['manufacturer_id']) . "</td><td>" . htmlspecialchars($row['name']) . "</td></tr>";
        }
        echo "</table>";
    }
    
    // Check error log
    $log_file = DIR_LOGS . 'manufacturer_error.log';
    if (file_exists($log_file) && filesize($log_file) > 0) {
        echo "<p class='info'><strong>Error Log Contents:</strong></p>";
        echo "<pre>" . htmlspecialchars(file_get_contents($log_file)) . "</pre>";
    } else {
        echo "<p class='info'>Error log is empty (file exists: " . (file_exists($log_file) ? 'yes' : 'no') . ")</p>";
    }
    
} catch (Error $e) {
    $output = ob_get_clean();
    
    echo "<p class='error'>❌ PHP Error caught!</p>";
    echo "<p class='error'><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p class='error'><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p class='error'><strong>Line:</strong> " . $e->getLine() . "</p>";
}

echo "</div>";

// Check final state
echo "<div class='step'><h2>Step 4: Final Database State</h2>";
$final_zero = $db->query("SELECT COUNT(*) as count FROM " . $prefix . "manufacturer WHERE manufacturer_id = 0");
$final_zero_count = $final_zero && $final_zero->num_rows ? (int)$final_zero->row['count'] : 0;
echo "<p>Records with manufacturer_id = 0: <strong>{$final_zero_count}</strong></p>";

if ($final_zero_count > 0) {
    echo "<p class='error'>❌ Records with ID 0 still exist!</p>";
    $db->query("DELETE FROM " . $prefix . "manufacturer WHERE manufacturer_id = 0");
}

$final_max = $db->query("SELECT MAX(manufacturer_id) as max_id FROM " . $prefix . "manufacturer WHERE manufacturer_id > 0");
$final_max_id = 0;
if ($final_max && $final_max->num_rows && isset($final_max->row['max_id']) && $final_max->row['max_id'] !== null) {
    $final_max_id = (int)$final_max->row['max_id'];
}
echo "<p>Max manufacturer_id: <strong>{$final_max_id}</strong></p>";
echo "</div>";

echo "<hr>";
echo "<p><strong>Test complete. Check the results above.</strong></p>";

