<?php
// Diagnostic script to check category_module table
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

echo "<h2>Category Module Table Diagnostic</h2>";
echo "<style>body{font-family:Arial;margin:20px;} table{border-collapse:collapse;margin:10px 0;} th,td{border:1px solid #ddd;padding:8px;} th{background:#f0f0f0;} .success{color:green;} .error{color:red;}</style>";

// Check if table exists
echo "<h3>1. Table Existence Check</h3>";
$table_name = DB_PREFIX . "category_module";
$table_check = $db->query("SHOW TABLES LIKE '" . $table_name . "'");
if ($table_check->num_rows) {
    echo "<p class='success'>✓ Table exists: " . $table_name . "</p>";
} else {
    echo "<p class='error'>✗ Table does NOT exist: " . $table_name . "</p>";
    echo "<h4>Create Table SQL:</h4>";
    echo "<pre>CREATE TABLE IF NOT EXISTS `" . $table_name . "` (
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

// Check table structure
echo "<h3>2. Table Structure</h3>";
$structure = $db->query("DESCRIBE " . $table_name);
echo "<table>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
foreach ($structure->rows as $field) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($field['Field']) . "</td>";
    echo "<td>" . htmlspecialchars($field['Type']) . "</td>";
    echo "<td>" . htmlspecialchars($field['Null']) . "</td>";
    echo "<td>" . htmlspecialchars($field['Key']) . "</td>";
    echo "<td>" . htmlspecialchars($field['Default']) . "</td>";
    echo "</tr>";
}
echo "</table>";

// Check if description column exists
echo "<h3>3. Description Column Check</h3>";
$has_description = false;
foreach ($structure->rows as $field) {
    if ($field['Field'] == 'description') {
        $has_description = true;
        break;
    }
}
if ($has_description) {
    echo "<p class='success'>✓ Description column exists</p>";
} else {
    echo "<p class='error'>✗ Description column does NOT exist</p>";
    echo "<h4>Add Description Column SQL:</h4>";
    echo "<pre>ALTER TABLE `" . $table_name . "` ADD COLUMN `description` TEXT NULL AFTER `setting`;</pre>";
}

// Test insert
echo "<h3>4. Test Insert</h3>";
try {
    $test_category_id = 1;
    $test_code = 'test_module';
    $test_setting = json_encode(array('test' => 'value'));
    
    // Delete any existing test record
    $db->query("DELETE FROM " . $table_name . " WHERE category_id = '" . (int)$test_category_id . "' AND code = 'test_module'");
    
    if ($has_description) {
        $sql = "INSERT INTO " . $table_name . " SET category_id = '" . (int)$test_category_id . "', module_id = '0', code = '" . $db->escape($test_code) . "', setting = '" . $db->escape($test_setting) . "', description = '', sort_order = '0', status = '1'";
    } else {
        $sql = "INSERT INTO " . $table_name . " SET category_id = '" . (int)$test_category_id . "', module_id = '0', code = '" . $db->escape($test_code) . "', setting = '" . $db->escape($test_setting) . "', sort_order = '0', status = '1'";
    }
    
    $result = $db->query($sql);
    if ($result) {
        echo "<p class='success'>✓ Test insert successful</p>";
        echo "<p>SQL: " . htmlspecialchars($sql) . "</p>";
        
        // Verify the insert
        $verify = $db->query("SELECT * FROM " . $table_name . " WHERE category_id = '" . (int)$test_category_id . "' AND code = 'test_module'");
        if ($verify->num_rows > 0) {
            echo "<p class='success'>✓ Record verified in database</p>";
            // Clean up test record
            $db->query("DELETE FROM " . $table_name . " WHERE category_id = '" . (int)$test_category_id . "' AND code = 'test_module'");
        } else {
            echo "<p class='error'>✗ Record not found after insert</p>";
        }
    } else {
        echo "<p class='error'>✗ Test insert failed</p>";
        echo "<p>SQL: " . htmlspecialchars($sql) . "</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Show existing records
echo "<h3>5. Existing Records</h3>";
$existing = $db->query("SELECT * FROM " . $table_name . " ORDER BY category_id, sort_order LIMIT 10");
if ($existing->num_rows > 0) {
    echo "<p>Found " . $existing->num_rows . " record(s) (showing first 10):</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Category ID</th><th>Module ID</th><th>Code</th><th>Sort Order</th><th>Status</th></tr>";
    foreach ($existing->rows as $row) {
        echo "<tr>";
        echo "<td>" . $row['category_module_id'] . "</td>";
        echo "<td>" . $row['category_id'] . "</td>";
        echo "<td>" . $row['module_id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['code']) . "</td>";
        echo "<td>" . $row['sort_order'] . "</td>";
        echo "<td>" . ($row['status'] ? 'Enabled' : 'Disabled') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No records found in table.</p>";
}

echo "<hr>";
echo "<p><strong>Diagnostic Complete!</strong></p>";
?>

