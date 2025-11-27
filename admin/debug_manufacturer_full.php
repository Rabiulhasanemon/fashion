<?php
// Comprehensive debug script for manufacturer add - Full trace
// Access via: https://ruplexa1.master.com.bd/admin/debug_manufacturer_full.php

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

echo "<h1>Manufacturer Add - Full Debug Trace</h1>";
echo "<style>
body { font-family: Arial; margin: 20px; } 
.success { color: green; font-weight: bold; } 
.error { color: red; font-weight: bold; } 
.info { color: blue; } 
.warning { color: orange; } 
pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; } 
table { border-collapse: collapse; width: 100%; margin: 10px 0; } 
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } 
th { background-color: #f2f2f2; }
.step { background: #e8f4f8; padding: 15px; margin: 10px 0; border-left: 4px solid #2196F3; }
</style>";

$prefix = DB_PREFIX;
$table_name = $prefix . 'manufacturer';

// Simulate the exact addManufacturer() function
echo "<div class='step'><h2>Step 1: Initial State Check</h2>";

// Check for records with ID 0
$zero_check = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = 0");
$zero_count = $zero_check ? $zero_check->num_rows : 0;
echo "<p>Records with manufacturer_id = 0: <strong>{$zero_count}</strong></p>";
if ($zero_count > 0) {
    echo "<p class='error'>❌ Found {$zero_count} record(s) with manufacturer_id = 0:</p>";
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
} else {
    echo "<p class='success'>✓ No records with manufacturer_id = 0</p>";
}

// Check AUTO_INCREMENT
$ai_check = $db->query("SHOW TABLE STATUS LIKE '{$table_name}'");
$ai_value = 'N/A';
if ($ai_check && $ai_check->num_rows) {
    $row = $ai_check->row;
    $ai_value = isset($row['Auto_increment']) ? $row['Auto_increment'] : (isset($row['AUTO_INCREMENT']) ? $row['AUTO_INCREMENT'] : 'N/A');
    echo "<p>Current AUTO_INCREMENT: <strong>{$ai_value}</strong></p>";
}

// Check column structure
$column_check = $db->query("SHOW COLUMNS FROM `{$table_name}` WHERE Field = 'manufacturer_id'");
$has_auto_increment = false;
if ($column_check && $column_check->num_rows) {
    $col_info = $column_check->row;
    $extra = isset($col_info['Extra']) ? $col_info['Extra'] : '';
    $has_auto_increment = (stripos($extra, 'auto_increment') !== false);
    echo "<p>Column has AUTO_INCREMENT: <strong>" . ($has_auto_increment ? 'YES' : 'NO') . "</strong></p>";
    echo "<pre>Column Info: " . print_r($col_info, true) . "</pre>";
}

echo "</div>";

// Step 2: Cleanup (simulating the function)
echo "<div class='step'><h2>Step 2: Cleanup Process</h2>";
$cleanup_attempts = 0;
$max_cleanup_attempts = 3;

while ($cleanup_attempts < $max_cleanup_attempts) {
    echo "<p>Cleanup attempt " . ($cleanup_attempts + 1) . ":</p>";
    
    $db->query("DELETE FROM " . $prefix . "manufacturer WHERE manufacturer_id = 0");
    $db->query("DELETE FROM " . $prefix . "manufacturer_description WHERE manufacturer_id = 0");
    $db->query("DELETE FROM " . $prefix . "manufacturer_to_store WHERE manufacturer_id = 0");
    $db->query("DELETE FROM " . $prefix . "url_alias WHERE query = 'manufacturer_id=0'");
    
    $verify_zero = $db->query("SELECT COUNT(*) as count FROM `{$table_name}` WHERE manufacturer_id = 0");
    $zero_count = 0;
    if ($verify_zero && $verify_zero->num_rows) {
        $zero_count = (int)$verify_zero->row['count'];
    }
    
    echo "<p>Records with ID 0 after cleanup: <strong>{$zero_count}</strong></p>";
    
    if ($zero_count == 0) {
        echo "<p class='success'>✓ Cleanup successful</p>";
        break;
    }
    
    $cleanup_attempts++;
}

if ($zero_count > 0) {
    echo "<p class='error'>❌ Still have {$zero_count} record(s) with ID 0 after cleanup</p>";
    $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id <= 0");
    $verify_final = $db->query("SELECT COUNT(*) as count FROM `{$table_name}` WHERE manufacturer_id = 0");
    $final_zero = 0;
    if ($verify_final && $verify_final->num_rows) {
        $final_zero = (int)$verify_final->row['count'];
    }
    if ($final_zero > 0) {
        echo "<p class='error'>❌ CRITICAL: Cannot remove record with manufacturer_id = 0</p>";
    } else {
        echo "<p class='success'>✓ Aggressive cleanup successful</p>";
    }
}

echo "</div>";

// Step 3: Calculate next ID
echo "<div class='step'><h2>Step 3: Calculate Next ID</h2>";
$max_check = $db->query("SELECT MAX(manufacturer_id) as max_id FROM `{$table_name}` WHERE manufacturer_id > 0");
$max_id = 0;
if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
    $max_id = (int)$max_check->row['max_id'];
}
$next_id = max($max_id + 1, 1);
echo "<p>Max manufacturer_id: <strong>{$max_id}</strong></p>";
echo "<p>Next ID should be: <strong>{$next_id}</strong></p>";
echo "</div>";

// Step 4: Final check before insert
echo "<div class='step'><h2>Step 4: Final Check Before Insert</h2>";
$final_check = $db->query("SELECT manufacturer_id FROM `{$table_name}` WHERE manufacturer_id = 0 LIMIT 1");
if ($final_check && $final_check->num_rows > 0) {
    echo "<p class='error'>❌ CRITICAL: Record with manufacturer_id = 0 still exists!</p>";
    echo "<p>Attempting one more cleanup...</p>";
    $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id = 0");
    $final_check2 = $db->query("SELECT manufacturer_id FROM `{$table_name}` WHERE manufacturer_id = 0 LIMIT 1");
    if ($final_check2 && $final_check2->num_rows > 0) {
        echo "<p class='error'>❌ Cannot proceed - ID 0 record still exists</p>";
        die("Cannot proceed with insert - ID 0 record exists");
    } else {
        echo "<p class='success'>✓ Final cleanup successful</p>";
    }
} else {
    echo "<p class='success'>✓ No records with ID 0 found</p>";
}

// Ensure AUTO_INCREMENT is set
if ($has_auto_increment) {
    $ai_status = $db->query("SHOW TABLE STATUS LIKE '{$table_name}'");
    if ($ai_status && $ai_status->num_rows) {
        $current_ai = isset($ai_status->row['Auto_increment']) ? $ai_status->row['Auto_increment'] : (isset($ai_status->row['AUTO_INCREMENT']) ? $ai_status->row['AUTO_INCREMENT'] : null);
        if ($current_ai === null || (int)$current_ai <= $max_id) {
            echo "<p>Setting AUTO_INCREMENT to {$next_id}...</p>";
            $db->query("ALTER TABLE `{$table_name}` AUTO_INCREMENT = {$next_id}");
            echo "<p class='success'>✓ AUTO_INCREMENT set</p>";
        } else {
            echo "<p>AUTO_INCREMENT is already correct: {$current_ai}</p>";
        }
    }
}
echo "</div>";

// Step 5: Test Insert
echo "<div class='step'><h2>Step 5: Test Insert (Simulating addManufacturer)</h2>";
$test_name = 'DEBUG_TEST_' . time();

// Clean up any previous test records
$db->query("DELETE FROM `{$table_name}` WHERE name LIKE 'DEBUG_TEST_%'");

echo "<p>Test manufacturer name: <strong>{$test_name}</strong></p>";
echo "<p>Using explicit ID: <strong>{$next_id}</strong></p>";

$insert_sql = "INSERT INTO `{$table_name}` SET manufacturer_id = '" . (int)$next_id . "', name = '" . $db->escape($test_name) . "', image = '', thumb = '', sort_order = '0'";
echo "<p>SQL Query:</p>";
echo "<pre>" . htmlspecialchars($insert_sql) . "</pre>";

echo "<p>Executing insert...</p>";
$insert_result = $db->query($insert_sql);

if ($insert_result) {
    echo "<p class='success'>✓ Insert successful!</p>";
    
    // Verify the inserted record
    $verify = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$next_id . "'");
    if ($verify && $verify->num_rows) {
        echo "<p class='success'>✓ Record verified with ID: <strong>{$next_id}</strong></p>";
        echo "<table>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        foreach ($verify->row as $key => $value) {
            echo "<tr><td>{$key}</td><td>" . htmlspecialchars($value) . "</td></tr>";
        }
        echo "</table>";
        
        // Clean up test record
        $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$next_id . "'");
        echo "<p class='info'>Test record cleaned up.</p>";
    } else {
        echo "<p class='error'>❌ Record not found after insert!</p>";
    }
} else {
    echo "<p class='error'>❌ Insert FAILED!</p>";
    
    // Get error details
    $error_info = '';
    $errno = 0;
    if (property_exists($db, 'link') && is_object($db->link)) {
        if (property_exists($db->link, 'error')) {
            $error_info = $db->link->error;
        }
        if (property_exists($db->link, 'errno')) {
            $errno = $db->link->errno;
        }
    }
    
    echo "<p class='error'>MySQL Error: <strong>" . htmlspecialchars($error_info) . "</strong></p>";
    echo "<p class='error'>Error Code: <strong>{$errno}</strong></p>";
    
    // Check what happened
    $check_after = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = 0");
    if ($check_after && $check_after->num_rows > 0) {
        echo "<p class='error'>❌ A record with manufacturer_id = 0 was created!</p>";
        echo "<table>";
        echo "<tr><th>manufacturer_id</th><th>name</th></tr>";
        foreach ($check_after->rows as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['manufacturer_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check if the ID we tried to use already exists
    $check_id = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$next_id . "'");
    if ($check_id && $check_id->num_rows > 0) {
        echo "<p class='warning'>⚠️ ID {$next_id} already exists in the table!</p>";
        echo "<table>";
        echo "<tr><th>manufacturer_id</th><th>name</th></tr>";
        foreach ($check_id->rows as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['manufacturer_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

echo "</div>";

// Step 6: Check all current records
echo "<div class='step'><h2>Step 6: Current Manufacturer Records</h2>";
$all_records = $db->query("SELECT manufacturer_id, name, sort_order FROM `{$table_name}` ORDER BY manufacturer_id ASC");
if ($all_records && $all_records->num_rows > 0) {
    echo "<p>Total records: <strong>{$all_records->num_rows}</strong></p>";
    echo "<table>";
    echo "<tr><th>manufacturer_id</th><th>name</th><th>sort_order</th></tr>";
    foreach ($all_records->rows as $row) {
        $row_class = ($row['manufacturer_id'] == 0) ? 'error' : '';
        echo "<tr class='{$row_class}'>";
        echo "<td>" . htmlspecialchars($row['manufacturer_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['sort_order']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='info'>No manufacturers found.</p>";
}
echo "</div>";

// Step 7: Test actual model function
echo "<div class='step'><h2>Step 7: Testing Actual Model Function</h2>";
echo "<p>Loading OpenCart model...</p>";

// Load the model
$loader = new Loader($registry);
$registry->set('load', $loader);
$loader->model('catalog/manufacturer');
$model = $registry->get('model_catalog_manufacturer');

if ($model) {
    echo "<p class='success'>✓ Model loaded successfully</p>";
    
    $test_data = array(
        'name' => 'DEBUG_MODEL_TEST_' . time(),
        'image' => '',
        'thumb' => '',
        'sort_order' => 0
    );
    
    echo "<p>Test data:</p>";
    echo "<pre>" . print_r($test_data, true) . "</pre>";
    
    echo "<p>Calling addManufacturer()...</p>";
    echo "<p class='info'>Setting execution time limit to 30 seconds...</p>";
    set_time_limit(30);
    
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    try {
        ob_start();
        $start_time = microtime(true);
        $manufacturer_id = $model->addManufacturer($test_data);
        $end_time = microtime(true);
        $execution_time = round(($end_time - $start_time) * 1000, 2);
        $output = ob_get_clean();
        
        echo "<p class='success'>✓ addManufacturer() completed in {$execution_time}ms</p>";
        echo "<p class='success'>✓ addManufacturer() returned: <strong>{$manufacturer_id}</strong></p>";
        
        if (!empty($output)) {
            echo "<p class='warning'>Output captured: " . htmlspecialchars($output) . "</p>";
        }
        
        // Verify
        $verify_model = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        if ($verify_model && $verify_model->num_rows) {
            echo "<p class='success'>✓ Record verified in database</p>";
            
            // Clean up
            $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
            $db->query("DELETE FROM " . $prefix . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
            $db->query("DELETE FROM " . $prefix . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
            echo "<p class='info'>Test record cleaned up.</p>";
        } else {
            echo "<p class='error'>❌ Record not found in database after model insert!</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Exception: <strong>" . htmlspecialchars($e->getMessage()) . "</strong></p>";
        echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
        
        // Check what happened
        $check_error = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = 0");
        if ($check_error && $check_error->num_rows > 0) {
            echo "<p class='error'>❌ A record with manufacturer_id = 0 was created by the model!</p>";
        }
    }
} else {
    echo "<p class='error'>❌ Could not load model</p>";
}

echo "</div>";

// Final summary
echo "<div class='step'><h2>Final Summary</h2>";
echo "<p><strong>If the error persists, check:</strong></p>";
echo "<ol>";
echo "<li>Is there a record with manufacturer_id = 0? (Check Step 1)</li>";
echo "<li>Does the column have AUTO_INCREMENT? (Check Step 1)</li>";
echo "<li>Is the calculated next_id correct? (Check Step 3)</li>";
echo "<li>Did the test insert work? (Check Step 5)</li>";
echo "<li>Did the model function work? (Check Step 7)</li>";
echo "</ol>";
echo "<p><strong>Debug complete.</strong></p>";
echo "</div>";


// Access via: https://ruplexa1.master.com.bd/admin/debug_manufacturer_full.php

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

echo "<h1>Manufacturer Add - Full Debug Trace</h1>";
echo "<style>
body { font-family: Arial; margin: 20px; } 
.success { color: green; font-weight: bold; } 
.error { color: red; font-weight: bold; } 
.info { color: blue; } 
.warning { color: orange; } 
pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; } 
table { border-collapse: collapse; width: 100%; margin: 10px 0; } 
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } 
th { background-color: #f2f2f2; }
.step { background: #e8f4f8; padding: 15px; margin: 10px 0; border-left: 4px solid #2196F3; }
</style>";

$prefix = DB_PREFIX;
$table_name = $prefix . 'manufacturer';

// Simulate the exact addManufacturer() function
echo "<div class='step'><h2>Step 1: Initial State Check</h2>";

// Check for records with ID 0
$zero_check = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = 0");
$zero_count = $zero_check ? $zero_check->num_rows : 0;
echo "<p>Records with manufacturer_id = 0: <strong>{$zero_count}</strong></p>";
if ($zero_count > 0) {
    echo "<p class='error'>❌ Found {$zero_count} record(s) with manufacturer_id = 0:</p>";
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
} else {
    echo "<p class='success'>✓ No records with manufacturer_id = 0</p>";
}

// Check AUTO_INCREMENT
$ai_check = $db->query("SHOW TABLE STATUS LIKE '{$table_name}'");
$ai_value = 'N/A';
if ($ai_check && $ai_check->num_rows) {
    $row = $ai_check->row;
    $ai_value = isset($row['Auto_increment']) ? $row['Auto_increment'] : (isset($row['AUTO_INCREMENT']) ? $row['AUTO_INCREMENT'] : 'N/A');
    echo "<p>Current AUTO_INCREMENT: <strong>{$ai_value}</strong></p>";
}

// Check column structure
$column_check = $db->query("SHOW COLUMNS FROM `{$table_name}` WHERE Field = 'manufacturer_id'");
$has_auto_increment = false;
if ($column_check && $column_check->num_rows) {
    $col_info = $column_check->row;
    $extra = isset($col_info['Extra']) ? $col_info['Extra'] : '';
    $has_auto_increment = (stripos($extra, 'auto_increment') !== false);
    echo "<p>Column has AUTO_INCREMENT: <strong>" . ($has_auto_increment ? 'YES' : 'NO') . "</strong></p>";
    echo "<pre>Column Info: " . print_r($col_info, true) . "</pre>";
}

echo "</div>";

// Step 2: Cleanup (simulating the function)
echo "<div class='step'><h2>Step 2: Cleanup Process</h2>";
$cleanup_attempts = 0;
$max_cleanup_attempts = 3;

while ($cleanup_attempts < $max_cleanup_attempts) {
    echo "<p>Cleanup attempt " . ($cleanup_attempts + 1) . ":</p>";
    
    $db->query("DELETE FROM " . $prefix . "manufacturer WHERE manufacturer_id = 0");
    $db->query("DELETE FROM " . $prefix . "manufacturer_description WHERE manufacturer_id = 0");
    $db->query("DELETE FROM " . $prefix . "manufacturer_to_store WHERE manufacturer_id = 0");
    $db->query("DELETE FROM " . $prefix . "url_alias WHERE query = 'manufacturer_id=0'");
    
    $verify_zero = $db->query("SELECT COUNT(*) as count FROM `{$table_name}` WHERE manufacturer_id = 0");
    $zero_count = 0;
    if ($verify_zero && $verify_zero->num_rows) {
        $zero_count = (int)$verify_zero->row['count'];
    }
    
    echo "<p>Records with ID 0 after cleanup: <strong>{$zero_count}</strong></p>";
    
    if ($zero_count == 0) {
        echo "<p class='success'>✓ Cleanup successful</p>";
        break;
    }
    
    $cleanup_attempts++;
}

if ($zero_count > 0) {
    echo "<p class='error'>❌ Still have {$zero_count} record(s) with ID 0 after cleanup</p>";
    $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id <= 0");
    $verify_final = $db->query("SELECT COUNT(*) as count FROM `{$table_name}` WHERE manufacturer_id = 0");
    $final_zero = 0;
    if ($verify_final && $verify_final->num_rows) {
        $final_zero = (int)$verify_final->row['count'];
    }
    if ($final_zero > 0) {
        echo "<p class='error'>❌ CRITICAL: Cannot remove record with manufacturer_id = 0</p>";
    } else {
        echo "<p class='success'>✓ Aggressive cleanup successful</p>";
    }
}

echo "</div>";

// Step 3: Calculate next ID
echo "<div class='step'><h2>Step 3: Calculate Next ID</h2>";
$max_check = $db->query("SELECT MAX(manufacturer_id) as max_id FROM `{$table_name}` WHERE manufacturer_id > 0");
$max_id = 0;
if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
    $max_id = (int)$max_check->row['max_id'];
}
$next_id = max($max_id + 1, 1);
echo "<p>Max manufacturer_id: <strong>{$max_id}</strong></p>";
echo "<p>Next ID should be: <strong>{$next_id}</strong></p>";
echo "</div>";

// Step 4: Final check before insert
echo "<div class='step'><h2>Step 4: Final Check Before Insert</h2>";
$final_check = $db->query("SELECT manufacturer_id FROM `{$table_name}` WHERE manufacturer_id = 0 LIMIT 1");
if ($final_check && $final_check->num_rows > 0) {
    echo "<p class='error'>❌ CRITICAL: Record with manufacturer_id = 0 still exists!</p>";
    echo "<p>Attempting one more cleanup...</p>";
    $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id = 0");
    $final_check2 = $db->query("SELECT manufacturer_id FROM `{$table_name}` WHERE manufacturer_id = 0 LIMIT 1");
    if ($final_check2 && $final_check2->num_rows > 0) {
        echo "<p class='error'>❌ Cannot proceed - ID 0 record still exists</p>";
        die("Cannot proceed with insert - ID 0 record exists");
    } else {
        echo "<p class='success'>✓ Final cleanup successful</p>";
    }
} else {
    echo "<p class='success'>✓ No records with ID 0 found</p>";
}

// Ensure AUTO_INCREMENT is set
if ($has_auto_increment) {
    $ai_status = $db->query("SHOW TABLE STATUS LIKE '{$table_name}'");
    if ($ai_status && $ai_status->num_rows) {
        $current_ai = isset($ai_status->row['Auto_increment']) ? $ai_status->row['Auto_increment'] : (isset($ai_status->row['AUTO_INCREMENT']) ? $ai_status->row['AUTO_INCREMENT'] : null);
        if ($current_ai === null || (int)$current_ai <= $max_id) {
            echo "<p>Setting AUTO_INCREMENT to {$next_id}...</p>";
            $db->query("ALTER TABLE `{$table_name}` AUTO_INCREMENT = {$next_id}");
            echo "<p class='success'>✓ AUTO_INCREMENT set</p>";
        } else {
            echo "<p>AUTO_INCREMENT is already correct: {$current_ai}</p>";
        }
    }
}
echo "</div>";

// Step 5: Test Insert
echo "<div class='step'><h2>Step 5: Test Insert (Simulating addManufacturer)</h2>";
$test_name = 'DEBUG_TEST_' . time();

// Clean up any previous test records
$db->query("DELETE FROM `{$table_name}` WHERE name LIKE 'DEBUG_TEST_%'");

echo "<p>Test manufacturer name: <strong>{$test_name}</strong></p>";
echo "<p>Using explicit ID: <strong>{$next_id}</strong></p>";

$insert_sql = "INSERT INTO `{$table_name}` SET manufacturer_id = '" . (int)$next_id . "', name = '" . $db->escape($test_name) . "', image = '', thumb = '', sort_order = '0'";
echo "<p>SQL Query:</p>";
echo "<pre>" . htmlspecialchars($insert_sql) . "</pre>";

echo "<p>Executing insert...</p>";
$insert_result = $db->query($insert_sql);

if ($insert_result) {
    echo "<p class='success'>✓ Insert successful!</p>";
    
    // Verify the inserted record
    $verify = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$next_id . "'");
    if ($verify && $verify->num_rows) {
        echo "<p class='success'>✓ Record verified with ID: <strong>{$next_id}</strong></p>";
        echo "<table>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        foreach ($verify->row as $key => $value) {
            echo "<tr><td>{$key}</td><td>" . htmlspecialchars($value) . "</td></tr>";
        }
        echo "</table>";
        
        // Clean up test record
        $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$next_id . "'");
        echo "<p class='info'>Test record cleaned up.</p>";
    } else {
        echo "<p class='error'>❌ Record not found after insert!</p>";
    }
} else {
    echo "<p class='error'>❌ Insert FAILED!</p>";
    
    // Get error details
    $error_info = '';
    $errno = 0;
    if (property_exists($db, 'link') && is_object($db->link)) {
        if (property_exists($db->link, 'error')) {
            $error_info = $db->link->error;
        }
        if (property_exists($db->link, 'errno')) {
            $errno = $db->link->errno;
        }
    }
    
    echo "<p class='error'>MySQL Error: <strong>" . htmlspecialchars($error_info) . "</strong></p>";
    echo "<p class='error'>Error Code: <strong>{$errno}</strong></p>";
    
    // Check what happened
    $check_after = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = 0");
    if ($check_after && $check_after->num_rows > 0) {
        echo "<p class='error'>❌ A record with manufacturer_id = 0 was created!</p>";
        echo "<table>";
        echo "<tr><th>manufacturer_id</th><th>name</th></tr>";
        foreach ($check_after->rows as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['manufacturer_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check if the ID we tried to use already exists
    $check_id = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$next_id . "'");
    if ($check_id && $check_id->num_rows > 0) {
        echo "<p class='warning'>⚠️ ID {$next_id} already exists in the table!</p>";
        echo "<table>";
        echo "<tr><th>manufacturer_id</th><th>name</th></tr>";
        foreach ($check_id->rows as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['manufacturer_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

echo "</div>";

// Step 6: Check all current records
echo "<div class='step'><h2>Step 6: Current Manufacturer Records</h2>";
$all_records = $db->query("SELECT manufacturer_id, name, sort_order FROM `{$table_name}` ORDER BY manufacturer_id ASC");
if ($all_records && $all_records->num_rows > 0) {
    echo "<p>Total records: <strong>{$all_records->num_rows}</strong></p>";
    echo "<table>";
    echo "<tr><th>manufacturer_id</th><th>name</th><th>sort_order</th></tr>";
    foreach ($all_records->rows as $row) {
        $row_class = ($row['manufacturer_id'] == 0) ? 'error' : '';
        echo "<tr class='{$row_class}'>";
        echo "<td>" . htmlspecialchars($row['manufacturer_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['sort_order']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='info'>No manufacturers found.</p>";
}
echo "</div>";

// Step 7: Test actual model function
echo "<div class='step'><h2>Step 7: Testing Actual Model Function</h2>";
echo "<p>Loading OpenCart model...</p>";

// Load the model
$loader = new Loader($registry);
$registry->set('load', $loader);
$loader->model('catalog/manufacturer');
$model = $registry->get('model_catalog_manufacturer');

if ($model) {
    echo "<p class='success'>✓ Model loaded successfully</p>";
    
    $test_data = array(
        'name' => 'DEBUG_MODEL_TEST_' . time(),
        'image' => '',
        'thumb' => '',
        'sort_order' => 0
    );
    
    echo "<p>Test data:</p>";
    echo "<pre>" . print_r($test_data, true) . "</pre>";
    
    echo "<p>Calling addManufacturer()...</p>";
    echo "<p class='info'>Setting execution time limit to 30 seconds...</p>";
    set_time_limit(30);
    
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    try {
        ob_start();
        $start_time = microtime(true);
        $manufacturer_id = $model->addManufacturer($test_data);
        $end_time = microtime(true);
        $execution_time = round(($end_time - $start_time) * 1000, 2);
        $output = ob_get_clean();
        
        echo "<p class='success'>✓ addManufacturer() completed in {$execution_time}ms</p>";
        echo "<p class='success'>✓ addManufacturer() returned: <strong>{$manufacturer_id}</strong></p>";
        
        if (!empty($output)) {
            echo "<p class='warning'>Output captured: " . htmlspecialchars($output) . "</p>";
        }
        
        // Verify
        $verify_model = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        if ($verify_model && $verify_model->num_rows) {
            echo "<p class='success'>✓ Record verified in database</p>";
            
            // Clean up
            $db->query("DELETE FROM `{$table_name}` WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
            $db->query("DELETE FROM " . $prefix . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
            $db->query("DELETE FROM " . $prefix . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
            echo "<p class='info'>Test record cleaned up.</p>";
        } else {
            echo "<p class='error'>❌ Record not found in database after model insert!</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Exception: <strong>" . htmlspecialchars($e->getMessage()) . "</strong></p>";
        echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
        
        // Check what happened
        $check_error = $db->query("SELECT * FROM `{$table_name}` WHERE manufacturer_id = 0");
        if ($check_error && $check_error->num_rows > 0) {
            echo "<p class='error'>❌ A record with manufacturer_id = 0 was created by the model!</p>";
        }
    }
} else {
    echo "<p class='error'>❌ Could not load model</p>";
}

echo "</div>";

// Final summary
echo "<div class='step'><h2>Final Summary</h2>";
echo "<p><strong>If the error persists, check:</strong></p>";
echo "<ol>";
echo "<li>Is there a record with manufacturer_id = 0? (Check Step 1)</li>";
echo "<li>Does the column have AUTO_INCREMENT? (Check Step 1)</li>";
echo "<li>Is the calculated next_id correct? (Check Step 3)</li>";
echo "<li>Did the test insert work? (Check Step 5)</li>";
echo "<li>Did the model function work? (Check Step 7)</li>";
echo "</ol>";
echo "<p><strong>Debug complete.</strong></p>";
echo "</div>";

