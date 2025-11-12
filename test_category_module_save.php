<?php
// Test script to verify category_module table and test saving
// Place this in your admin root and access via browser: http://yourdomain.com/admin/test_category_module_save.php

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

echo "<h2>Category Module Save Test</h2>";
echo "<style>body{font-family:Arial;margin:20px;} table{border-collapse:collapse;margin:10px 0;} th,td{border:1px solid #ddd;padding:8px;} th{background:#f0f0f0;}</style>";

// Test 1: Check if table exists
echo "<h3>Test 1: Check if table exists</h3>";
$table_check = $db->query("SHOW TABLES LIKE '" . DB_PREFIX . "category_module'");
if ($table_check->num_rows) {
    echo "✓ Table exists: " . DB_PREFIX . "category_module<br>";
} else {
    echo "✗ Table does NOT exist: " . DB_PREFIX . "category_module<br>";
    echo "<strong>ACTION REQUIRED:</strong> Run this SQL:<br>";
    echo "<pre>CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "category_module` (
  `category_module_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(64) NOT NULL,
  `setting` text NOT NULL,
  `description` TEXT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_module_id`),
  KEY `category_id` (`category_id`),
  KEY `code` (`code`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;</pre>";
    exit;
}

// Test 2: Check table structure
echo "<h3>Test 2: Table structure</h3>";
$structure = $db->query("DESCRIBE " . DB_PREFIX . "category_module");
echo "<table>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
$has_description = false;
foreach ($structure->rows as $field) {
    echo "<tr>";
    echo "<td>" . $field['Field'] . "</td>";
    echo "<td>" . $field['Type'] . "</td>";
    echo "<td>" . $field['Null'] . "</td>";
    echo "<td>" . $field['Key'] . "</td>";
    echo "<td>" . ($field['Default'] !== null ? $field['Default'] : 'NULL') . "</td>";
    echo "</tr>";
    if ($field['Field'] == 'description') {
        $has_description = true;
    }
}
echo "</table>";

// Test 3: Check if description column exists
echo "<h3>Test 3: Check description column</h3>";
if ($has_description) {
    echo "✓ Description column exists<br>";
} else {
    echo "✗ Description column does NOT exist<br>";
    echo "<strong>ACTION REQUIRED:</strong> Run this SQL:<br>";
    echo "<pre>ALTER TABLE `" . DB_PREFIX . "category_module` ADD COLUMN `description` TEXT NULL AFTER `setting`;</pre>";
}

// Test 4: Test inserting a module
echo "<h3>Test 4: Test inserting a module</h3>";
$test_category_id = 226; // Use a test category ID
$test_code = 'banner';
$test_description = 'Test description from script';
$test_setting = json_encode(array());

// Delete any existing test entries
$db->query("DELETE FROM " . DB_PREFIX . "category_module WHERE category_id = '" . (int)$test_category_id . "' AND code = 'banner'");

// Try to insert
try {
    $insert_sql = "INSERT INTO " . DB_PREFIX . "category_module SET 
        category_id = '" . (int)$test_category_id . "', 
        module_id = '0', 
        code = '" . $db->escape($test_code) . "', 
        setting = '" . $db->escape($test_setting) . "', 
        description = '" . $db->escape($test_description) . "', 
        sort_order = '0', 
        status = '1'";
    
    echo "SQL: <pre>" . htmlspecialchars($insert_sql) . "</pre>";
    
    $db->query($insert_sql);
    echo "✓ Insert successful!<br>";
    
    // Verify it was inserted
    $verify = $db->query("SELECT * FROM " . DB_PREFIX . "category_module WHERE category_id = '" . (int)$test_category_id . "' AND code = 'banner'");
    if ($verify->num_rows) {
        echo "✓ Verification: Record found in database<br>";
        echo "Description: " . htmlspecialchars($verify->row['description']) . "<br>";
    } else {
        echo "✗ Verification failed: Record not found<br>";
    }
    
} catch (Exception $e) {
    echo "✗ Insert failed: " . $e->getMessage() . "<br>";
}

// Test 5: Check existing modules for category 226
echo "<h3>Test 5: Check existing modules for category 226</h3>";
$existing = $db->query("SELECT * FROM " . DB_PREFIX . "category_module WHERE category_id = '226'");
if ($existing->num_rows) {
    echo "Found " . $existing->num_rows . " module(s) for category 226:<br>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Module ID</th><th>Code</th><th>Description</th><th>Sort Order</th><th>Status</th></tr>";
    foreach ($existing->rows as $row) {
        echo "<tr>";
        echo "<td>" . $row['category_module_id'] . "</td>";
        echo "<td>" . $row['module_id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['code']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . $row['sort_order'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No modules found for category 226<br>";
}

echo "<hr>";
echo "<h3>Summary</h3>";
echo "<p>If all tests pass, the database is set up correctly. The issue might be:</p>";
echo "<ul>";
echo "<li>Form data not reaching PHP (check browser console and error logs)</li>";
echo "<li>Validation failing silently</li>";
echo "<li>Data format issue in POST</li>";
echo "</ul>";
echo "<p><strong>Next step:</strong> Check the log file at: <code>" . DIR_LOGS . "category_module_debug.log</code></p>";

