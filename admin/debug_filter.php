<?php
/**
 * Filter Debug Script
 * Use this to test filter operations and see detailed errors
 */

// Start session and load OpenCart
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Start Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Load settings
$config->set('config_store_id', 0);
$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");

foreach ($query->rows as $result) {
    if (!$result['serialized']) {
        $config->set($result['key'], $result['value']);
    } else {
        $config->set($result['key'], unserialize($result['value']));
    }
}

// Enable error display
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Filter Debug Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #6c5ce7; padding-bottom: 10px; }
        h2 { color: #666; margin-top: 30px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 4px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; border: 1px solid #dee2e6; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; }
        th { background: #6c5ce7; color: white; }
        tr:hover { background: #f8f9fa; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #dee2e6; border-radius: 4px; }
    </style>
</head>
<body>
<div class=\"container\">
    <h1>🔍 Filter System Debug Tool</h1>";

// Test 1: Database Connection
echo "<div class=\"test-section\">";
echo "<h2>1. Database Connection Test</h2>";
try {
    $test_query = $db->query("SELECT 1");
    echo "<div class=\"success\">✅ Database connection successful</div>";
} catch (Exception $e) {
    echo "<div class=\"error\">❌ Database connection failed: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Test 2: Check Required Tables
echo "<div class=\"test-section\">";
echo "<h2>2. Database Tables Check</h2>";
$required_tables = [
    DB_PREFIX . 'filter_group',
    DB_PREFIX . 'filter_group_description',
    DB_PREFIX . 'filter',
    DB_PREFIX . 'filter_description',
    DB_PREFIX . 'filter_group_to_profile'
];

foreach ($required_tables as $table) {
    try {
        $result = $db->query("SHOW TABLES LIKE '" . $table . "'");
        if ($result->num_rows > 0) {
            // Check table structure
            $structure = $db->query("DESCRIBE `" . $table . "`");
            echo "<div class=\"success\">✅ Table <strong>$table</strong> exists (" . $structure->num_rows . " columns)</div>";
        } else {
            echo "<div class=\"error\">❌ Table <strong>$table</strong> does NOT exist</div>";
        }
    } catch (Exception $e) {
        echo "<div class=\"error\">❌ Error checking table <strong>$table</strong>: " . $e->getMessage() . "</div>";
    }
}
echo "</div>";

// Test 3: Check Table Structures
echo "<div class=\"test-section\">";
echo "<h2>3. Table Structure Check</h2>";
try {
    $structure = $db->query("DESCRIBE `" . DB_PREFIX . "filter_group`");
    echo "<div class=\"info\"><strong>filter_group table structure:</strong></div>";
    echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($structure->rows as $row) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "<div class=\"error\">❌ Error checking table structure: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Test 4: Check AUTO_INCREMENT and fix if needed
echo "<div class=\"test-section\">";
echo "<h2>4. Database AUTO_INCREMENT Check & Fix</h2>";
try {
    // Check current AUTO_INCREMENT value
    $ai_check = $db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "filter_group'");
    $auto_increment = $ai_check->row['Auto_increment'];
    echo "<div class=\"info\">Current AUTO_INCREMENT value: <strong>" . ($auto_increment ? $auto_increment : 'NULL') . "</strong></div>";
    
    // Check for row with ID 0
    $zero_check = $db->query("SELECT * FROM `" . DB_PREFIX . "filter_group` WHERE filter_group_id = 0");
    if ($zero_check->num_rows > 0) {
        echo "<div class=\"error\">❌ Found row with filter_group_id = 0 (this causes conflicts!)</div>";
        echo "<div class=\"warning\">⚠️ Attempting to fix by deleting row with ID 0...</div>";
        try {
            $db->query("DELETE FROM `" . DB_PREFIX . "filter_group` WHERE filter_group_id = 0");
            echo "<div class=\"success\">✅ Deleted row with ID 0</div>";
        } catch (Exception $e) {
            echo "<div class=\"error\">❌ Failed to delete: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class=\"success\">✅ No row with ID 0 found</div>";
    }
    
    // Get max ID
    $max_id = $db->query("SELECT MAX(filter_group_id) as max_id FROM `" . DB_PREFIX . "filter_group`");
    $max_id_value = $max_id->row['max_id'] ? $max_id->row['max_id'] : 0;
    echo "<div class=\"info\">Maximum filter_group_id in table: <strong>$max_id_value</strong></div>";
    
    // Fix AUTO_INCREMENT if needed
    $new_ai = max(1, $max_id_value + 1);
    if (!$auto_increment || $auto_increment <= $max_id_value) {
        echo "<div class=\"warning\">⚠️ AUTO_INCREMENT needs to be fixed. Setting to: $new_ai</div>";
        try {
            $db->query("ALTER TABLE `" . DB_PREFIX . "filter_group` AUTO_INCREMENT = " . (int)$new_ai);
            echo "<div class=\"success\">✅ AUTO_INCREMENT fixed to $new_ai</div>";
        } catch (Exception $e) {
            echo "<div class=\"error\">❌ Failed to fix AUTO_INCREMENT: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class=\"success\">✅ AUTO_INCREMENT is properly configured</div>";
    }
    
    // Now test insert
    echo "<div class=\"info\"><strong>Testing INSERT operation...</strong></div>";
    $test_label = 'DEBUG_TEST_' . time();
    $test_sort_order = 0;
    
    echo "<pre>Label: $test_label\nSort Order: $test_sort_order</pre>";
    
    $insert_query = "INSERT INTO `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$test_sort_order . "', label = '" . $db->escape($test_label) . "'";
    echo "<div class=\"info\">SQL: <pre>" . htmlspecialchars($insert_query) . "</pre></div>";
    
    $db->query($insert_query);
    $filter_group_id = $db->getLastId();
    
    if ($filter_group_id && $filter_group_id > 0) {
        echo "<div class=\"success\">✅ Insert successful! Filter Group ID: $filter_group_id</div>";
        
        // Clean up test data
        $db->query("DELETE FROM `" . DB_PREFIX . "filter_group` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
        echo "<div class=\"info\">🧹 Test data cleaned up</div>";
    } else {
        echo "<div class=\"error\">❌ Insert failed - No valid ID returned (got: " . ($filter_group_id ? $filter_group_id : 'NULL') . ")</div>";
    }
} catch (Exception $e) {
    echo "<div class=\"error\">❌ Insert test failed: " . $e->getMessage() . "</div>";
    echo "<div class=\"info\">Error details: <pre>" . print_r($e, true) . "</pre></div>";
}
echo "</div>";

// Test 5: Check Languages
echo "<div class=\"test-section\">";
echo "<h2>5. Available Languages</h2>";
try {
    $languages = $db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'");
    if ($languages->num_rows > 0) {
        echo "<table><tr><th>Language ID</th><th>Name</th><th>Code</th><th>Status</th></tr>";
        foreach ($languages->rows as $lang) {
            echo "<tr>";
            echo "<td>" . $lang['language_id'] . "</td>";
            echo "<td>" . $lang['name'] . "</td>";
            echo "<td>" . $lang['code'] . "</td>";
            echo "<td>" . $lang['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<div class=\"success\">✅ Found " . $languages->num_rows . " active language(s)</div>";
    } else {
        echo "<div class=\"error\">❌ No active languages found!</div>";
    }
} catch (Exception $e) {
    echo "<div class=\"error\">❌ Error checking languages: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Test 6: Check PHP Error Log
echo "<div class=\"test-section\">";
echo "<h2>6. PHP Error Log Location</h2>";
$error_log_path = ini_get('error_log');
if ($error_log_path) {
    echo "<div class=\"info\">PHP Error Log: <strong>$error_log_path</strong></div>";
    if (file_exists($error_log_path)) {
        $log_size = filesize($error_log_path);
        echo "<div class=\"info\">Log file exists, size: " . number_format($log_size) . " bytes</div>";
        if ($log_size > 0 && $log_size < 1048576) { // Less than 1MB
            $recent_logs = file_get_contents($error_log_path);
            $lines = explode("\n", $recent_logs);
            $recent_lines = array_slice($lines, -20); // Last 20 lines
            echo "<div class=\"info\">Recent error log entries (last 20 lines):</div>";
            echo "<pre>" . htmlspecialchars(implode("\n", $recent_lines)) . "</pre>";
        }
    } else {
        echo "<div class=\"warning\">⚠️ Log file does not exist</div>";
    }
} else {
    echo "<div class=\"warning\">⚠️ PHP error_log not configured</div>";
}
echo "</div>";

// Test 7: Check OpenCart Logs
echo "<div class=\"test-section\">";
echo "<h2>7. OpenCart Error Logs</h2>";
if (defined('DIR_LOGS')) {
    $oc_log_file = DIR_LOGS . $config->get('config_error_filename');
    echo "<div class=\"info\">OpenCart Log File: <strong>$oc_log_file</strong></div>";
    
    if (file_exists($oc_log_file)) {
        $log_size = filesize($oc_log_file);
        echo "<div class=\"info\">Log file exists, size: " . number_format($log_size) . " bytes</div>";
        if ($log_size > 0 && $log_size < 1048576) { // Less than 1MB
            $recent_logs = file_get_contents($oc_log_file);
            $lines = explode("\n", $recent_logs);
            $recent_lines = array_slice($lines, -30); // Last 30 lines
            echo "<div class=\"info\">Recent OpenCart error log entries (last 30 lines):</div>";
            echo "<pre>" . htmlspecialchars(implode("\n", $recent_lines)) . "</pre>";
        } else {
            echo "<div class=\"warning\">⚠️ Log file is too large to display</div>";
        }
    } else {
        echo "<div class=\"warning\">⚠️ OpenCart log file does not exist</div>";
    }
} else {
    echo "<div class=\"warning\">⚠️ DIR_LOGS constant not defined</div>";
}
echo "</div>";

// Test 8: PHP Configuration
echo "<div class=\"test-section\">";
echo "<h2>8. PHP Configuration</h2>";
echo "<table>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>PHP Version</td><td>" . phpversion() . "</td></tr>";
echo "<tr><td>Error Reporting</td><td>" . error_reporting() . "</td></tr>";
echo "<tr><td>Display Errors</td><td>" . (ini_get('display_errors') ? 'On' : 'Off') . "</td></tr>";
echo "<tr><td>Display Startup Errors</td><td>" . (ini_get('display_startup_errors') ? 'On' : 'Off') . "</td></tr>";
echo "<tr><td>Log Errors</td><td>" . (ini_get('log_errors') ? 'On' : 'Off') . "</td></tr>";
echo "<tr><td>Max Execution Time</td><td>" . ini_get('max_execution_time') . " seconds</td></tr>";
echo "<tr><td>Memory Limit</td><td>" . ini_get('memory_limit') . "</td></tr>";
echo "</table>";
echo "</div>";

// Test 9: Model Load Test
echo "<div class=\"test-section\">";
echo "<h2>9. Model Load Test</h2>";
try {
    $loader->model('catalog/filter');
    $model = $registry->get('model_catalog_filter');
    if ($model) {
        echo "<div class=\"success\">✅ Filter model loaded successfully</div>";
        
        // Test getFilterGroups method
        try {
            $groups = $model->getFilterGroups();
            echo "<div class=\"success\">✅ getFilterGroups() method works. Found " . count($groups) . " filter group(s)</div>";
        } catch (Exception $e) {
            echo "<div class=\"error\">❌ getFilterGroups() failed: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class=\"error\">❌ Failed to load filter model</div>";
    }
} catch (Exception $e) {
    echo "<div class=\"error\">❌ Error loading model: " . $e->getMessage() . "</div>";
    echo "<div class=\"info\">Stack trace: <pre>" . $e->getTraceAsString() . "</pre></div>";
}
echo "</div>";

echo "</div>
</body>
</html>";
?>

