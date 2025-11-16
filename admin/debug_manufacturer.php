<?php
// Debug script for manufacturer add issue
// Access via: https://ruplexa1.master.com.bd/admin/debug_manufacturer.php

// Load OpenCart framework
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

// Check database connection
if (!$db) {
    die('Database connection failed');
}

echo "<h1>Manufacturer Add Debug</h1>";
echo "<style>body { font-family: Arial; margin: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; }</style>";

$prefix = DB_PREFIX;

// 1. Check for manufacturer_id = 0 records
echo "<h2>1. Checking for manufacturer_id = 0 records</h2>";
$zero_check = $db->query("SELECT COUNT(*) as count FROM {$prefix}manufacturer WHERE manufacturer_id = 0");
$zero_count = $zero_check && $zero_check->num_rows ? (int)$zero_check->row['count'] : 0;
if ($zero_count > 0) {
    echo "<p class='error'>❌ FOUND: {$zero_count} record(s) with manufacturer_id = 0</p>";
    echo "<p>These need to be deleted. Run: <code>DELETE FROM {$prefix}manufacturer WHERE manufacturer_id = 0;</code></p>";
} else {
    echo "<p class='success'>✓ No manufacturer with manufacturer_id = 0 found.</p>";
}

// 2. Check AUTO_INCREMENT
echo "<h2>2. Checking AUTO_INCREMENT value</h2>";
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

// 3. Check MAX manufacturer_id
echo "<h2>3. Checking MAX manufacturer_id</h2>";
$max_check = $db->query("SELECT MAX(manufacturer_id) as max_id FROM {$prefix}manufacturer WHERE manufacturer_id > 0");
$max_id = 0;
if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
    $max_id = (int)$max_check->row['max_id'];
}
$next_id = max($max_id + 1, 1);
echo "<p class='info'>Maximum manufacturer_id: {$max_id}</p>";
echo "<p class='info'>Next expected manufacturer_id: {$next_id}</p>";

if ($ai_value != 'N/A' && (int)$ai_value < $next_id) {
    echo "<p class='error'>⚠️ AUTO_INCREMENT ({$ai_value}) is less than expected next ID ({$next_id})</p>";
    echo "<p>Fix: Run <code>ALTER TABLE {$prefix}manufacturer AUTO_INCREMENT = {$next_id};</code></p>";
} else {
    echo "<p class='success'>✓ AUTO_INCREMENT is correct</p>";
}

// 4. Test the addManufacturer logic
echo "<h2>4. Testing Manufacturer Insert Logic</h2>";
echo "<p>Simulating manufacturer insert...</p>";

try {
    // Clean up
    $db->query("DELETE FROM {$prefix}manufacturer WHERE manufacturer_id = 0");
    $db->query("DELETE FROM {$prefix}manufacturer_description WHERE manufacturer_id = 0");
    $db->query("DELETE FROM {$prefix}manufacturer_to_store WHERE manufacturer_id = 0");
    $db->query("DELETE FROM {$prefix}url_alias WHERE query = 'manufacturer_id=0'");
    
    // Calculate next ID
    $max_check = $db->query("SELECT MAX(manufacturer_id) as max_id FROM {$prefix}manufacturer WHERE manufacturer_id > 0");
    $max_id = 0;
    if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
        $max_id = (int)$max_check->row['max_id'];
    }
    $next_id = max($max_id + 1, 1);
    
    echo "<p class='info'>Calculated next ID: {$next_id}</p>";
    
    // Set AUTO_INCREMENT
    $db->query("ALTER TABLE {$prefix}manufacturer AUTO_INCREMENT = {$next_id}");
    echo "<p class='success'>✓ AUTO_INCREMENT set to {$next_id}</p>";
    
    // Test insert (we'll delete it immediately)
    $test_name = 'TEST_MANUFACTURER_' . time();
    $insert_sql = "INSERT INTO {$prefix}manufacturer SET manufacturer_id = '" . (int)$next_id . "', name = '" . $db->escape($test_name) . "', image = '', thumb = '', sort_order = '0'";
    
    echo "<p class='info'>SQL: <code>" . htmlspecialchars($insert_sql) . "</code></p>";
    
    $result = $db->query($insert_sql);
    
    if ($result) {
        // Verify
        $verify = $db->query("SELECT manufacturer_id FROM {$prefix}manufacturer WHERE manufacturer_id = '" . (int)$next_id . "' LIMIT 1");
        if ($verify && $verify->num_rows) {
            $inserted_id = (int)$verify->row['manufacturer_id'];
            echo "<p class='success'>✓ Test insert successful! manufacturer_id: {$inserted_id}</p>";
            
            // Clean up test record
            $db->query("DELETE FROM {$prefix}manufacturer WHERE manufacturer_id = '" . (int)$inserted_id . "'");
            echo "<p class='info'>✓ Test record deleted</p>";
        } else {
            echo "<p class='error'>❌ Insert reported success but record not found!</p>";
        }
    } else {
        echo "<p class='error'>❌ Insert failed!</p>";
        
        // Get error
        $error = '';
        $errno = 0;
        if (property_exists($db, 'link') && is_object($db->link)) {
            if (property_exists($db->link, 'error')) {
                $error = $db->link->error;
            }
            if (property_exists($db->link, 'errno')) {
                $errno = $db->link->errno;
            }
        }
        echo "<p class='error'>Error: {$error} (Code: {$errno})</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

// 5. Check for recent errors
echo "<h2>5. Recent Error Logs</h2>";
$log_file = DIR_LOGS . 'manufacturer_error.log';
if (file_exists($log_file)) {
    $log_content = file_get_contents($log_file);
    $log_lines = explode("\n", $log_content);
    $recent_lines = array_slice($log_lines, -20); // Last 20 lines
    echo "<pre>" . htmlspecialchars(implode("\n", $recent_lines)) . "</pre>";
} else {
    echo "<p class='info'>No error log file found at: {$log_file}</p>";
}

// 6. Check PHP error log location
echo "<h2>6. PHP Configuration</h2>";
echo "<p class='info'>PHP Error Log: " . ini_get('error_log') . "</p>";
echo "<p class='info'>Display Errors: " . (ini_get('display_errors') ? 'On' : 'Off') . "</p>";
echo "<p class='info'>Error Reporting: " . error_reporting() . "</p>";

// 7. Check table structure
echo "<h2>7. Table Structure</h2>";
$create_table = $db->query("SHOW CREATE TABLE {$prefix}manufacturer");
if ($create_table && $create_table->num_rows) {
    $create_sql = isset($create_table->row['Create Table']) ? $create_table->row['Create Table'] : (isset($create_table->row[1]) ? $create_table->row[1] : 'N/A');
    echo "<pre>" . htmlspecialchars($create_sql) . "</pre>";
}

echo "<hr>";
echo "<p><a href='index.php?route=catalog/manufacturer/add'>← Back to Add Manufacturer</a></p>";
?>

