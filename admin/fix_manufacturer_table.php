<?php
// Fix manufacturer table structure - Add AUTO_INCREMENT to manufacturer_id
// Access via: https://ruplexa1.master.com.bd/admin/fix_manufacturer_table.php

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

if (!$db) {
    die('Database connection failed');
}

echo "<h1>Fix Manufacturer Table Structure</h1>";
echo "<style>body { font-family: Arial; margin: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } .warning { color: orange; } pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }</style>";

$prefix = DB_PREFIX;
$table_name = $prefix . 'manufacturer';

// Step 1: Check current structure
echo "<h2>Step 1: Current Table Structure</h2>";
$structure = $db->query("SHOW CREATE TABLE `{$table_name}`");
if ($structure && $structure->num_rows) {
    echo "<pre>" . htmlspecialchars($structure->row['Create Table']) . "</pre>";
    
    // Check if AUTO_INCREMENT is missing
    if (stripos($structure->row['Create Table'], 'AUTO_INCREMENT') === false) {
        echo "<p class='error'>❌ AUTO_INCREMENT is MISSING from manufacturer_id column!</p>";
    } else {
        echo "<p class='success'>✓ AUTO_INCREMENT exists in table definition</p>";
    }
} else {
    die("<p class='error'>❌ Could not get table structure</p>");
}

// Step 2: Delete any records with manufacturer_id = 0
echo "<h2>Step 2: Cleanup Records with manufacturer_id = 0</h2>";
$zero_check = $db->query("SELECT COUNT(*) as count FROM `{$table_name}` WHERE manufacturer_id = 0");
$zero_count = $zero_check && $zero_check->num_rows ? (int)$zero_check->row['count'] : 0;

if ($zero_count > 0) {
    echo "<p class='warning'>⚠️ Found {$zero_count} record(s) with manufacturer_id = 0. Deleting...</p>";
    
    // Delete from related tables first
    $db->query("DELETE FROM " . $prefix . "manufacturer_description WHERE manufacturer_id = 0");
    $db->query("DELETE FROM " . $prefix . "manufacturer_to_store WHERE manufacturer_id = 0");
    $db->query("DELETE FROM " . $prefix . "manufacturer_to_layout WHERE manufacturer_id = 0");
    $db->query("DELETE FROM " . $prefix . "url_alias WHERE query = 'manufacturer_id=0'");
    
    // Delete from main table
    $delete_result = $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id = 0");
    if ($delete_result) {
        echo "<p class='success'>✓ Successfully deleted {$zero_count} record(s) with manufacturer_id = 0</p>";
    } else {
        echo "<p class='error'>❌ Failed to delete records</p>";
    }
} else {
    echo "<p class='success'>✓ No records with manufacturer_id = 0 found</p>";
}

// Step 3: Get max ID
echo "<h2>Step 3: Calculate Next ID</h2>";
$max_check = $db->query("SELECT MAX(manufacturer_id) as max_id FROM `{$table_name}` WHERE manufacturer_id > 0");
$max_id = 0;
if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
    $max_id = (int)$max_check->row['max_id'];
}
$next_id = max($max_id + 1, 1);
echo "<p class='info'>Max manufacturer_id: <strong>{$max_id}</strong></p>";
echo "<p class='info'>Next ID should be: <strong>{$next_id}</strong></p>";

// Step 4: Fix the table structure - Add AUTO_INCREMENT
echo "<h2>Step 4: Fixing Table Structure</h2>";
echo "<p class='info'>Modifying manufacturer_id column to add AUTO_INCREMENT...</p>";

// First, check if we need to modify the column
$columns = $db->query("SHOW COLUMNS FROM `{$table_name}` LIKE 'manufacturer_id'");
$needs_fix = false;

if ($columns && $columns->num_rows) {
    $col_info = $columns->row;
    $extra = isset($col_info['Extra']) ? $col_info['Extra'] : '';
    
    if (stripos($extra, 'auto_increment') === false) {
        $needs_fix = true;
        echo "<p class='warning'>⚠️ Column does not have AUTO_INCREMENT. Fixing...</p>";
        
        // Modify the column to add AUTO_INCREMENT
        // We need to preserve the NOT NULL constraint
        $alter_sql = "ALTER TABLE `{$table_name}` MODIFY `manufacturer_id` int(11) NOT NULL AUTO_INCREMENT";
        echo "<p class='info'>SQL: <code>" . htmlspecialchars($alter_sql) . "</code></p>";
        
        $alter_result = $db->query($alter_sql);
        
        if ($alter_result) {
            echo "<p class='success'>✓ Successfully added AUTO_INCREMENT to manufacturer_id column</p>";
        } else {
            echo "<p class='error'>❌ Failed to modify column. Trying alternative method...</p>";
            
            // Alternative: Drop and recreate (more risky but sometimes needed)
            // First, let's try setting AUTO_INCREMENT on the table
            $alter_table = $db->query("ALTER TABLE `{$table_name}` AUTO_INCREMENT = {$next_id}");
            if ($alter_table) {
                echo "<p class='success'>✓ Set AUTO_INCREMENT value to {$next_id}</p>";
            }
        }
    } else {
        echo "<p class='success'>✓ Column already has AUTO_INCREMENT</p>";
    }
} else {
    echo "<p class='error'>❌ Could not check column structure</p>";
}

// Step 5: Set AUTO_INCREMENT value
echo "<h2>Step 5: Setting AUTO_INCREMENT Value</h2>";
$set_ai = $db->query("ALTER TABLE `{$table_name}` AUTO_INCREMENT = {$next_id}");
if ($set_ai) {
    echo "<p class='success'>✓ AUTO_INCREMENT set to {$next_id}</p>";
} else {
    echo "<p class='error'>❌ Failed to set AUTO_INCREMENT</p>";
}

// Step 6: Verify the fix
echo "<h2>Step 6: Verification</h2>";
$verify_structure = $db->query("SHOW CREATE TABLE `{$table_name}`");
if ($verify_structure && $verify_structure->num_rows) {
    $create_table = $verify_structure->row['Create Table'];
    echo "<pre>" . htmlspecialchars($create_table) . "</pre>";
    
    if (stripos($create_table, 'AUTO_INCREMENT') !== false) {
        echo "<p class='success'>✓ AUTO_INCREMENT is now present in table definition</p>";
    } else {
        echo "<p class='error'>❌ AUTO_INCREMENT still missing!</p>";
    }
}

// Check AUTO_INCREMENT value
$ai_check = $db->query("SHOW TABLE STATUS LIKE '{$table_name}'");
if ($ai_check && $ai_check->num_rows) {
    $row = $ai_check->row;
    $ai_value = isset($row['Auto_increment']) ? $row['Auto_increment'] : (isset($row['AUTO_INCREMENT']) ? $row['AUTO_INCREMENT'] : 'N/A');
    echo "<p class='info'>Current AUTO_INCREMENT value: <strong>{$ai_value}</strong></p>";
    
    if ($ai_value !== 'N/A' && (int)$ai_value > 0) {
        echo "<p class='success'>✓ AUTO_INCREMENT is working correctly</p>";
    } else {
        echo "<p class='warning'>⚠️ AUTO_INCREMENT value is still invalid</p>";
    }
}

// Step 7: Test insert
echo "<h2>Step 7: Test Insert</h2>";
$test_name = 'TEST_FIX_' . time();

// Clean up any previous test records
$db->query("DELETE FROM `{$table_name}` WHERE name LIKE 'TEST_FIX_%'");

echo "<p class='info'>Attempting test insert: <strong>{$test_name}</strong></p>";
$test_sql = "INSERT INTO `{$table_name}` SET name = '" . $db->escape($test_name) . "', image = '', thumb = '', sort_order = '0'";
echo "<p class='info'>SQL: <code>" . htmlspecialchars($test_sql) . "</code></p>";

$test_result = $db->query($test_sql);

if ($test_result) {
    $inserted_id = $db->getLastId();
    echo "<p class='success'>✓ Insert successful!</p>";
    echo "<p class='info'>getLastId() returned: <strong>{$inserted_id}</strong></p>";
    
    if ($inserted_id > 0) {
        echo "<p class='success'>✓ Inserted ID is valid: {$inserted_id}</p>";
        
        // Verify the record
        $verify = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$inserted_id . "'");
        if ($verify && $verify->num_rows) {
            echo "<p class='success'>✓ Record verified with correct ID</p>";
            
            // Clean up test record
            $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$inserted_id . "'");
            echo "<p class='info'>Test record cleaned up.</p>";
        }
    } else {
        echo "<p class='error'>❌ Inserted ID is 0 - AUTO_INCREMENT is still not working!</p>";
        
        // Check what was actually inserted
        $check_zero = $db->query("SELECT * FROM `{$table_name}` WHERE name = '" . $db->escape($test_name) . "'");
        if ($check_zero && $check_zero->num_rows) {
            echo "<p class='error'>Record was inserted with manufacturer_id = " . $check_zero->row['manufacturer_id'] . "</p>";
            $db->query("DELETE FROM `{$table_name}` WHERE name = '" . $db->escape($test_name) . "'");
        }
    }
} else {
    echo "<p class='error'>❌ Insert failed!</p>";
}

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<p><strong>If AUTO_INCREMENT is still not working, you may need to:</strong></p>";
echo "<ol>";
echo "<li>Manually run: <code>ALTER TABLE `{$table_name}` MODIFY `manufacturer_id` int(11) NOT NULL AUTO_INCREMENT;</code></li>";
echo "<li>Then set: <code>ALTER TABLE `{$table_name}` AUTO_INCREMENT = {$next_id};</code></li>";
echo "<li>Verify with: <code>SHOW CREATE TABLE `{$table_name}`;</code></li>";
echo "</ol>";
echo "<p><strong>Fix complete. Try adding a manufacturer now.</strong></p>";

