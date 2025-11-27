<?php
// Comprehensive debug script for manufacturer add issue
// Access via: https://ruplexa1.master.com.bd/admin/debug_manufacturer_fix.php

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

echo "<h1>Manufacturer Add Debug - Comprehensive Fix</h1>";
echo "<style>body { font-family: Arial; margin: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } .warning { color: orange; } pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; } table { border-collapse: collapse; width: 100%; margin: 10px 0; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } th { background-color: #f2f2f2; }</style>";

$prefix = DB_PREFIX;
$table_name = $prefix . 'manufacturer';

// 1. Check table structure
echo "<h2>1. Table Structure</h2>";
$structure = $db->query("SHOW CREATE TABLE `{$table_name}`");
if ($structure && $structure->num_rows) {
    echo "<pre>" . htmlspecialchars($structure->row['Create Table']) . "</pre>";
} else {
    echo "<p class='error'>❌ Could not get table structure</p>";
}

// 2. Check for manufacturer_id = 0 records
echo "<h2>2. Checking for manufacturer_id = 0 records</h2>";
$zero_check = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = 0");
$zero_count = $zero_check ? $zero_check->num_rows : 0;
if ($zero_count > 0) {
    echo "<p class='error'>❌ FOUND: {$zero_count} record(s) with manufacturer_id = 0</p>";
    echo "<table>";
    echo "<tr><th>manufacturer_id</th><th>name</th><th>image</th><th>sort_order</th></tr>";
    foreach ($zero_check->rows as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['manufacturer_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['image']) . "</td>";
        echo "<td>" . htmlspecialchars($row['sort_order']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Try to delete
    echo "<p class='info'>Attempting to delete records with manufacturer_id = 0...</p>";
    $delete_result = $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id = 0");
    if ($delete_result) {
        echo "<p class='success'>✓ Successfully deleted records with manufacturer_id = 0</p>";
    } else {
        echo "<p class='error'>❌ Failed to delete records with manufacturer_id = 0</p>";
    }
} else {
    echo "<p class='success'>✓ No manufacturer with manufacturer_id = 0 found.</p>";
}

// 3. Check AUTO_INCREMENT
echo "<h2>3. Checking AUTO_INCREMENT value</h2>";
$ai_check = $db->query("SHOW TABLE STATUS LIKE '{$table_name}'");
$ai_value = 'N/A';
$max_id = 0;
if ($ai_check && $ai_check->num_rows) {
    $row = $ai_check->row;
    if (isset($row['Auto_increment'])) {
        $ai_value = $row['Auto_increment'];
    } elseif (isset($row['AUTO_INCREMENT'])) {
        $ai_value = $row['AUTO_INCREMENT'];
    }
    
    echo "<p class='info'>Current AUTO_INCREMENT: <strong>{$ai_value}</strong></p>";
    
    // Get max ID
    $max_query = $db->query("SELECT MAX(manufacturer_id) as max_id FROM `{$table_name}` WHERE manufacturer_id > 0");
    if ($max_query && $max_query->num_rows && isset($max_query->row['max_id']) && $max_query->row['max_id'] !== null) {
        $max_id = (int)$max_query->row['max_id'];
        echo "<p class='info'>Max manufacturer_id in table: <strong>{$max_id}</strong></p>";
    }
    
    $next_id = max($max_id + 1, 1);
    echo "<p class='info'>Calculated next ID should be: <strong>{$next_id}</strong></p>";
    
    if ((int)$ai_value <= 0 || (int)$ai_value <= $max_id) {
        echo "<p class='warning'>⚠️ AUTO_INCREMENT is invalid or too low. Setting to {$next_id}...</p>";
        $alter_result = $db->query("ALTER TABLE `{$table_name}` AUTO_INCREMENT = {$next_id}");
        if ($alter_result) {
            echo "<p class='success'>✓ AUTO_INCREMENT set to {$next_id}</p>";
            
            // Verify
            $ai_check2 = $db->query("SHOW TABLE STATUS LIKE '{$table_name}'");
            if ($ai_check2 && $ai_check2->num_rows) {
                $row2 = $ai_check2->row;
                $new_ai = isset($row2['Auto_increment']) ? $row2['Auto_increment'] : (isset($row2['AUTO_INCREMENT']) ? $row2['AUTO_INCREMENT'] : 'N/A');
                echo "<p class='info'>Verified AUTO_INCREMENT is now: <strong>{$new_ai}</strong></p>";
            }
        } else {
            echo "<p class='error'>❌ Failed to set AUTO_INCREMENT</p>";
        }
    } else {
        echo "<p class='success'>✓ AUTO_INCREMENT is valid</p>";
    }
} else {
    echo "<p class='error'>❌ Could not get AUTO_INCREMENT value</p>";
}

// 4. Check all manufacturer records
echo "<h2>4. Current Manufacturer Records</h2>";
$all_manufacturers = $db->query("SELECT manufacturer_id, name, sort_order FROM `{$table_name}` ORDER BY manufacturer_id ASC LIMIT 20");
if ($all_manufacturers && $all_manufacturers->num_rows > 0) {
    echo "<p class='info'>Found {$all_manufacturers->num_rows} manufacturer(s):</p>";
    echo "<table>";
    echo "<tr><th>manufacturer_id</th><th>name</th><th>sort_order</th></tr>";
    foreach ($all_manufacturers->rows as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['manufacturer_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['sort_order']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='info'>No manufacturers found in table.</p>";
}

// 5. Test insert
echo "<h2>5. Testing Insert</h2>";
$test_name = 'TEST_MANUFACTURER_' . time();

// Clean up any test records first
$db->query("DELETE FROM `{$table_name}` WHERE name LIKE 'TEST_MANUFACTURER_%'");

// Final cleanup of ID 0
$db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id = 0");

// Ensure AUTO_INCREMENT is correct
$max_check = $db->query("SELECT MAX(manufacturer_id) as max_id FROM `{$table_name}` WHERE manufacturer_id > 0");
$max_id = 0;
if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
    $max_id = (int)$max_check->row['max_id'];
}
$next_id = max($max_id + 1, 1);
$db->query("ALTER TABLE `{$table_name}` AUTO_INCREMENT = {$next_id}");

echo "<p class='info'>Attempting to insert test manufacturer: <strong>{$test_name}</strong></p>";
echo "<p class='info'>AUTO_INCREMENT should be: <strong>{$next_id}</strong></p>";

// Try insert WITHOUT specifying manufacturer_id
$insert_sql = "INSERT INTO `{$table_name}` SET name = '" . $db->escape($test_name) . "', image = '', thumb = '', sort_order = '0'";
echo "<p class='info'>SQL: <code>" . htmlspecialchars($insert_sql) . "</code></p>";

$insert_result = $db->query($insert_sql);

if ($insert_result) {
    $inserted_id = $db->getLastId();
    echo "<p class='success'>✓ Insert successful!</p>";
    echo "<p class='info'>getLastId() returned: <strong>{$inserted_id}</strong></p>";
    
    // Verify the record
    $verify = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$inserted_id . "'");
    if ($verify && $verify->num_rows) {
        echo "<p class='success'>✓ Verified record exists with ID: <strong>{$inserted_id}</strong></p>";
        echo "<table>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        foreach ($verify->row as $key => $value) {
            echo "<tr><td>{$key}</td><td>" . htmlspecialchars($value) . "</td></tr>";
        }
        echo "</table>";
        
        // Clean up test record
        $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$inserted_id . "'");
        echo "<p class='info'>Test record cleaned up.</p>";
    } else {
        echo "<p class='error'>❌ Record not found after insert!</p>";
    }
} else {
    echo "<p class='error'>❌ Insert failed!</p>";
    
    // Try to get error details
    $error_info = '';
    if (property_exists($db, 'link') && is_object($db->link)) {
        if (property_exists($db->link, 'error')) {
            $error_info = $db->link->error;
        }
        if (property_exists($db->link, 'errno')) {
            $errno = $db->link->errno;
            echo "<p class='error'>MySQL Error Code: <strong>{$errno}</strong></p>";
        }
    }
    echo "<p class='error'>MySQL Error: <strong>" . htmlspecialchars($error_info) . "</strong></p>";
    
    // Check if ID 0 still exists
    $check_zero_again = $db->query("SELECT COUNT(*) as count FROM `{$table_name}` WHERE manufacturer_id = 0");
    if ($check_zero_again && $check_zero_again->num_rows && $check_zero_again->row['count'] > 0) {
        echo "<p class='error'>❌ Record with manufacturer_id = 0 still exists! This is the problem.</p>";
        echo "<p class='info'>Attempting force delete...</p>";
        $force_delete = $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id = 0 LIMIT 1");
        if ($force_delete) {
            echo "<p class='success'>✓ Force deleted. Try inserting again.</p>";
        }
    }
}

// 6. Final status
echo "<h2>6. Final Status Check</h2>";
$final_ai = $db->query("SHOW TABLE STATUS LIKE '{$table_name}'");
if ($final_ai && $final_ai->num_rows) {
    $row = $final_ai->row;
    $final_ai_value = isset($row['Auto_increment']) ? $row['Auto_increment'] : (isset($row['AUTO_INCREMENT']) ? $row['AUTO_INCREMENT'] : 'N/A');
    echo "<p class='info'>Final AUTO_INCREMENT: <strong>{$final_ai_value}</strong></p>";
}

$final_zero = $db->query("SELECT COUNT(*) as count FROM `{$table_name}` WHERE manufacturer_id = 0");
$final_zero_count = $final_zero && $final_zero->num_rows ? (int)$final_zero->row['count'] : 0;
if ($final_zero_count > 0) {
    echo "<p class='error'>❌ Still have {$final_zero_count} record(s) with manufacturer_id = 0</p>";
} else {
    echo "<p class='success'>✓ No records with manufacturer_id = 0</p>";
}

echo "<hr>";
echo "<p><strong>Debug complete. If the issue persists, check the error logs and database constraints.</strong></p>";

