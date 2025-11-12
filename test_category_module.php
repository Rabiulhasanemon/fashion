<?php
// Test script to verify category_module table and functionality
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

echo "<h2>Category Module Test</h2>";

// Test 1: Check if table exists
echo "<h3>Test 1: Check if table exists</h3>";
$table_check = $db->query("SHOW TABLES LIKE '" . DB_PREFIX . "category_module'");
if ($table_check->num_rows) {
    echo "✓ Table exists: " . DB_PREFIX . "category_module<br>";
} else {
    echo "✗ Table does NOT exist: " . DB_PREFIX . "category_module<br>";
    echo "Please run the SQL: ALTER TABLE `" . DB_PREFIX . "category_module` ADD COLUMN `description` TEXT NULL AFTER `setting`;<br>";
    exit;
}

// Test 2: Check table structure
echo "<h3>Test 2: Table structure</h3>";
$structure = $db->query("DESCRIBE " . DB_PREFIX . "category_module");
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
foreach ($structure->rows as $field) {
    echo "<tr>";
    echo "<td>" . $field['Field'] . "</td>";
    echo "<td>" . $field['Type'] . "</td>";
    echo "<td>" . $field['Null'] . "</td>";
    echo "<td>" . $field['Key'] . "</td>";
    echo "<td>" . $field['Default'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Test 3: Check if description column exists
echo "<h3>Test 3: Check description column</h3>";
$has_description = false;
foreach ($structure->rows as $field) {
    if ($field['Field'] == 'description') {
        $has_description = true;
        break;
    }
}
if ($has_description) {
    echo "✓ Description column exists<br>";
} else {
    echo "✗ Description column does NOT exist<br>";
    echo "Please run: ALTER TABLE `" . DB_PREFIX . "category_module` ADD COLUMN `description` TEXT NULL AFTER `setting`;<br>";
}

// Test 4: Test insert
echo "<h3>Test 4: Test insert (will be deleted immediately)</h3>";
$test_category_id = 1; // Change this to an existing category ID
$test_code = 'test_module';
$test_description = 'Test description';
$test_setting = json_encode(array());

try {
    $db->query("INSERT INTO " . DB_PREFIX . "category_module SET 
        category_id = '" . (int)$test_category_id . "', 
        module_id = '0', 
        code = '" . $db->escape($test_code) . "', 
        setting = '" . $db->escape($test_setting) . "', 
        description = '" . $db->escape($test_description) . "', 
        sort_order = '0', 
        status = '1'");
    
    echo "✓ Insert successful<br>";
    
    // Delete test record
    $db->query("DELETE FROM " . DB_PREFIX . "category_module WHERE category_id = '" . (int)$test_category_id . "' AND code = '" . $db->escape($test_code) . "'");
    echo "✓ Test record deleted<br>";
} catch (Exception $e) {
    echo "✗ Insert failed: " . $e->getMessage() . "<br>";
}

// Test 5: Check existing records
echo "<h3>Test 5: Existing category modules</h3>";
$existing = $db->query("SELECT * FROM " . DB_PREFIX . "category_module LIMIT 5");
if ($existing->num_rows) {
    echo "Found " . $existing->num_rows . " records:<br>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Category ID</th><th>Code</th><th>Description</th><th>Status</th></tr>";
    foreach ($existing->rows as $row) {
        echo "<tr>";
        echo "<td>" . $row['category_module_id'] . "</td>";
        echo "<td>" . $row['category_id'] . "</td>";
        echo "<td>" . $row['code'] . "</td>";
        echo "<td>" . (isset($row['description']) ? htmlspecialchars(substr($row['description'], 0, 50)) : 'N/A') . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No existing records found<br>";
}

echo "<hr>";
echo "<p><strong>If all tests pass, the issue might be in the form submission or data handling.</strong></p>";
echo "<p>Check your PHP error log for debug messages when saving a category.</p>";

