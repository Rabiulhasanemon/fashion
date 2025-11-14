<?php
// Diagnostic script to check category_module table
// Access via: http://yourdomain.com/admin/check_category_module_table.php

// Suppress deprecation warnings from vendor libraries (Google API client)
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Start output buffering to catch any errors
ob_start();

try {
    // Check if config.php exists
    if (!file_exists('config.php')) {
        die('<h1>Error: config.php not found</h1><p>Please ensure this file is in the admin directory.</p>');
    }

    // Include OpenCart configuration
    require_once('config.php');
    
    // Check if DIR_SYSTEM is defined
    if (!defined('DIR_SYSTEM')) {
        die('<h1>Error: DIR_SYSTEM not defined</h1><p>config.php may be missing required definitions.</p>');
    }
    
    // Check if startup.php exists
    if (!file_exists(DIR_SYSTEM . 'startup.php')) {
        die('<h1>Error: startup.php not found</h1><p>Path: ' . DIR_SYSTEM . 'startup.php</p>');
    }
    
    require_once(DIR_SYSTEM . 'startup.php');

    // Registry
    $registry = new Registry();

    // Config - Don't load config files, just create the object
    // Config values will be loaded from database if needed
    $config = new Config();
    $registry->set('config', $config);

    // Check database constants
    if (!defined('DB_DRIVER') || !defined('DB_HOSTNAME') || !defined('DB_USERNAME') || !defined('DB_PASSWORD') || !defined('DB_DATABASE')) {
        die('<h1>Error: Database constants not defined</h1><p>Please check your config.php file.</p>');
    }

    // Database
    try {
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
        $registry->set('db', $db);
    } catch (Exception $e) {
        die('<h1>Database Connection Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>');
    }

    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Category Module Diagnostic</title>";
    echo "<style>body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;} .container{max-width:1200px;margin:0 auto;background:white;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);} h2{color:#333;border-bottom:2px solid #007bff;padding-bottom:10px;} h3{color:#555;margin-top:20px;} table{border-collapse:collapse;margin:10px 0;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background:#f0f0f0;font-weight:bold;} .success{color:green;font-weight:bold;} .error{color:red;font-weight:bold;} pre{background:#f8f8f8;padding:10px;border-radius:4px;overflow-x:auto;} .info{background:#e7f3ff;padding:10px;border-left:4px solid #2196F3;margin:10px 0;}</style>";
    echo "</head><body><div class='container'>";

    echo "<h2>Category Module Table Diagnostic</h2>";

    // Check if table exists
    echo "<h3>1. Table Existence Check</h3>";
    $table_name = DB_PREFIX . "category_module";
    try {
        $table_check = $db->query("SHOW TABLES LIKE '" . $table_name . "'");
        if ($table_check && $table_check->num_rows) {
            echo "<p class='success'>✓ Table exists: " . htmlspecialchars($table_name) . "</p>";
        } else {
            echo "<p class='error'>✗ Table does NOT exist: " . htmlspecialchars($table_name) . "</p>";
            echo "<div class='info'>";
            echo "<h4>Create Table SQL:</h4>";
            echo "<pre>CREATE TABLE IF NOT EXISTS `" . htmlspecialchars($table_name) . "` (
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
            echo "<p><strong>Run this SQL in phpMyAdmin or your MySQL client.</strong></p>";
            echo "</div>";
            echo "</div></body></html>";
            exit;
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error checking table: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "</div></body></html>";
        exit;
    }

    // Check table structure
    echo "<h3>2. Table Structure</h3>";
    try {
        $structure = $db->query("DESCRIBE " . $table_name);
        if ($structure && $structure->rows) {
            echo "<table>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            foreach ($structure->rows as $field) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($field['Field']) . "</td>";
                echo "<td>" . htmlspecialchars($field['Type']) . "</td>";
                echo "<td>" . htmlspecialchars($field['Null']) . "</td>";
                echo "<td>" . htmlspecialchars($field['Key']) . "</td>";
                echo "<td>" . htmlspecialchars($field['Default'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='error'>✗ Could not retrieve table structure</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    // Check if description column exists
    echo "<h3>3. Description Column Check</h3>";
    $has_description = false;
    if (isset($structure) && $structure->rows) {
        foreach ($structure->rows as $field) {
            if ($field['Field'] == 'description') {
                $has_description = true;
                break;
            }
        }
    }
    if ($has_description) {
        echo "<p class='success'>✓ Description column exists</p>";
    } else {
        echo "<p class='error'>✗ Description column does NOT exist</p>";
        echo "<div class='info'>";
        echo "<h4>Add Description Column SQL:</h4>";
        echo "<pre>ALTER TABLE `" . htmlspecialchars($table_name) . "` ADD COLUMN `description` TEXT NULL AFTER `setting`;</pre>";
        echo "<p><strong>Run this SQL in phpMyAdmin or your MySQL client.</strong></p>";
        echo "</div>";
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
            echo "<p><small>SQL: " . htmlspecialchars($sql) . "</small></p>";
            
            // Verify the insert
            $verify = $db->query("SELECT * FROM " . $table_name . " WHERE category_id = '" . (int)$test_category_id . "' AND code = 'test_module'");
            if ($verify && $verify->num_rows > 0) {
                echo "<p class='success'>✓ Record verified in database</p>";
                // Clean up test record
                $db->query("DELETE FROM " . $table_name . " WHERE category_id = '" . (int)$test_category_id . "' AND code = 'test_module'");
                echo "<p>Test record cleaned up.</p>";
            } else {
                echo "<p class='error'>✗ Record not found after insert</p>";
            }
        } else {
            echo "<p class='error'>✗ Test insert failed</p>";
            echo "<p><small>SQL: " . htmlspecialchars($sql) . "</small></p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    // Show existing records
    echo "<h3>5. Existing Records</h3>";
    try {
        $existing = $db->query("SELECT * FROM " . $table_name . " ORDER BY category_id, sort_order LIMIT 10");
        if ($existing && $existing->num_rows > 0) {
            echo "<p>Found " . $existing->num_rows . " record(s) (showing first 10):</p>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Category ID</th><th>Module ID</th><th>Code</th><th>Sort Order</th><th>Status</th></tr>";
            foreach ($existing->rows as $row) {
                echo "<tr>";
                echo "<td>" . (int)$row['category_module_id'] . "</td>";
                echo "<td>" . (int)$row['category_id'] . "</td>";
                echo "<td>" . (int)$row['module_id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['code']) . "</td>";
                echo "<td>" . (int)$row['sort_order'] . "</td>";
                echo "<td>" . ((int)$row['status'] ? 'Enabled' : 'Disabled') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No records found in table.</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    echo "<hr>";
    echo "<p><strong>Diagnostic Complete!</strong></p>";
    echo "<p><small>If you see any errors above, please fix them before trying to save category modules.</small></p>";
    echo "</div></body></html>";
    
} catch (Exception $e) {
    ob_clean();
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Error</title>";
    echo "<style>body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;} .error-box{max-width:800px;margin:0 auto;background:white;padding:20px;border-radius:8px;border:2px solid #dc3545;} h1{color:#dc3545;} pre{background:#f8f8f8;padding:10px;border-radius:4px;overflow-x:auto;}</style>";
    echo "</head><body><div class='error-box'>";
    echo "<h1>Error Running Diagnostic Script</h1>";
    echo "<p><strong>Error Message:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<p><strong>Stack Trace:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div></body></html>";
} catch (Error $e) {
    ob_clean();
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Error</title>";
    echo "<style>body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;} .error-box{max-width:800px;margin:0 auto;background:white;padding:20px;border-radius:8px;border:2px solid #dc3545;} h1{color:#dc3545;} pre{background:#f8f8f8;padding:10px;border-radius:4px;overflow-x:auto;}</style>";
    echo "</head><body><div class='error-box'>";
    echo "<h1>Fatal Error Running Diagnostic Script</h1>";
    echo "<p><strong>Error Message:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "</div></body></html>";
}

ob_end_flush();
?>

