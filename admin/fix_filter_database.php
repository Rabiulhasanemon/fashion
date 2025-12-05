<?php
/**
 * Fix Filter Database Issues
 * This script fixes the AUTO_INCREMENT and removes problematic rows
 */

require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Start Registry
$registry = new Registry();
$loader = new Loader($registry);
$registry->set('load', $loader);
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

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Fix Filter Database</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #6c5ce7; padding-bottom: 10px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 4px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; border: 1px solid #dee2e6; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; }
        th { background: #6c5ce7; color: white; }
    </style>
</head>
<body>
<div class=\"container\">
    <h1>🔧 Fix Filter Database Issues</h1>";

// Step 1: Check for row with ID 0
echo "<h2>Step 1: Check for problematic rows</h2>";
try {
    $zero_check = $db->query("SELECT * FROM `" . DB_PREFIX . "filter_group` WHERE filter_group_id = 0");
    if ($zero_check->num_rows > 0) {
        echo "<div class=\"error\">❌ Found " . $zero_check->num_rows . " row(s) with filter_group_id = 0</div>";
        echo "<table><tr><th>filter_group_id</th><th>label</th><th>sort_order</th></tr>";
        foreach ($zero_check->rows as $row) {
            echo "<tr><td>" . $row['filter_group_id'] . "</td><td>" . htmlspecialchars($row['label']) . "</td><td>" . $row['sort_order'] . "</td></tr>";
        }
        echo "</table>";
        
        echo "<div class=\"warning\">⚠️ Deleting row(s) with ID 0...</div>";
        $db->query("DELETE FROM `" . DB_PREFIX . "filter_group_description` WHERE filter_group_id = 0");
        $db->query("DELETE FROM `" . DB_PREFIX . "filter` WHERE filter_group_id = 0");
        $db->query("DELETE FROM `" . DB_PREFIX . "filter_description` WHERE filter_group_id = 0");
        $db->query("DELETE FROM `" . DB_PREFIX . "filter_group_to_profile` WHERE filter_group_id = 0");
        $db->query("DELETE FROM `" . DB_PREFIX . "filter_group` WHERE filter_group_id = 0");
        echo "<div class=\"success\">✅ Deleted all rows with filter_group_id = 0</div>";
    } else {
        echo "<div class=\"success\">✅ No rows with filter_group_id = 0 found</div>";
    }
} catch (Exception $e) {
    echo "<div class=\"error\">❌ Error: " . $e->getMessage() . "</div>";
}

// Step 2: Check and fix AUTO_INCREMENT
echo "<h2>Step 2: Fix AUTO_INCREMENT</h2>";
try {
    // Get current AUTO_INCREMENT
    $ai_check = $db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "filter_group'");
    $current_ai = $ai_check->row['Auto_increment'];
    echo "<div class=\"info\">Current AUTO_INCREMENT: <strong>" . ($current_ai ? $current_ai : 'NULL') . "</strong></div>";
    
    // Get max ID
    $max_id_query = $db->query("SELECT MAX(filter_group_id) as max_id FROM `" . DB_PREFIX . "filter_group`");
    $max_id = $max_id_query->row['max_id'] ? (int)$max_id_query->row['max_id'] : 0;
    echo "<div class=\"info\">Maximum filter_group_id in table: <strong>$max_id</strong></div>";
    
    // Calculate new AUTO_INCREMENT (should be max_id + 1, minimum 1)
    $new_ai = max(1, $max_id + 1);
    
    if (!$current_ai || $current_ai <= $max_id) {
        echo "<div class=\"warning\">⚠️ AUTO_INCREMENT needs to be fixed. Setting to: <strong>$new_ai</strong></div>";
        $db->query("ALTER TABLE `" . DB_PREFIX . "filter_group` AUTO_INCREMENT = " . (int)$new_ai);
        echo "<div class=\"success\">✅ AUTO_INCREMENT fixed to $new_ai</div>";
    } else {
        echo "<div class=\"success\">✅ AUTO_INCREMENT is properly configured ($current_ai)</div>";
    }
} catch (Exception $e) {
    echo "<div class=\"error\">❌ Error fixing AUTO_INCREMENT: " . $e->getMessage() . "</div>";
}

// Step 3: Test insert
echo "<h2>Step 3: Test Insert</h2>";
try {
    $test_label = 'FIX_TEST_' . time();
    $test_sort_order = 0;
    
    echo "<div class=\"info\">Attempting test insert...</div>";
    echo "<pre>Label: $test_label\nSort Order: $test_sort_order</pre>";
    
    $db->query("INSERT INTO `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$test_sort_order . "', label = '" . $db->escape($test_label) . "'");
    $filter_group_id = $db->getLastId();
    
    if ($filter_group_id && $filter_group_id > 0) {
        echo "<div class=\"success\">✅ Insert successful! Filter Group ID: $filter_group_id</div>";
        
        // Clean up
        $db->query("DELETE FROM `" . DB_PREFIX . "filter_group` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
        echo "<div class=\"info\">🧹 Test data cleaned up</div>";
        echo "<div class=\"success\"><strong>✅ Database is now fixed! You can now add filters in the admin panel.</strong></div>";
    } else {
        echo "<div class=\"error\">❌ Insert still failing. Got ID: " . ($filter_group_id ? $filter_group_id : 'NULL') . "</div>";
    }
} catch (Exception $e) {
    echo "<div class=\"error\">❌ Insert test failed: " . $e->getMessage() . "</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</div>
</body>
</html>";
?>

