<?php
// Helper script to create category_module table
// Access via: http://yourdomain.com/admin/create_category_module_table.php

// Suppress deprecation warnings from vendor libraries
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Custom error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (($errno === E_DEPRECATED || $errno === E_STRICT) && 
        strpos($errfile, 'vendor/google/apiclient') !== false) {
        return true;
    }
    return false;
}, E_ALL);

// Output buffering with filter
ob_start(function($buffer) {
    $patterns = [
        '/Deprecated:.*?Google\\\\Model::.*?\n/',
        '/Deprecated:.*?Google\\\\Collection::.*?\n/',
        '/Deprecated:.*?vendor\/google\/apiclient.*?\n/',
    ];
    foreach ($patterns as $pattern) {
        $buffer = preg_replace($pattern, '', $buffer);
    }
    return $buffer;
});

try {
    // Check if config.php exists
    if (!file_exists('config.php')) {
        ob_end_clean();
        die('<h1>Error: config.php not found</h1><p>Please ensure this file is in the admin directory.</p>');
    }

    // Include OpenCart configuration
    @require_once('config.php');
    
    // Check if DIR_SYSTEM is defined
    if (!defined('DIR_SYSTEM')) {
        ob_end_clean();
        die('<h1>Error: DIR_SYSTEM not defined</h1>');
    }
    
    // Check if startup.php exists
    if (!file_exists(DIR_SYSTEM . 'startup.php')) {
        ob_end_clean();
        die('<h1>Error: startup.php not found</h1>');
    }
    
    @require_once(DIR_SYSTEM . 'startup.php');
    
    // Check database constants
    if (!defined('DB_DRIVER') || !defined('DB_HOSTNAME') || !defined('DB_USERNAME') || !defined('DB_PASSWORD') || !defined('DB_DATABASE')) {
        ob_end_clean();
        die('<h1>Error: Database constants not defined</h1>');
    }

    // Database connection
    try {
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    } catch (Exception $e) {
        ob_end_clean();
        die('<h1>Database Connection Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>');
    }

    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Create Category Module Table</title>";
    echo "<style>body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;} .container{max-width:800px;margin:0 auto;background:white;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);} h2{color:#333;border-bottom:2px solid #007bff;padding-bottom:10px;} .success{color:green;font-weight:bold;background:#d4edda;padding:15px;border-radius:4px;margin:10px 0;} .error{color:red;font-weight:bold;background:#f8d7da;padding:15px;border-radius:4px;margin:10px 0;} .info{background:#e7f3ff;padding:15px;border-left:4px solid #2196F3;margin:10px 0;} pre{background:#f8f8f8;padding:10px;border-radius:4px;overflow-x:auto;}</style>";
    echo "</head><body><div class='container'>";

    echo "<h2>Create Category Module Table</h2>";

    $table_name = DB_PREFIX . "category_module";

    // Check if table already exists
    $table_check = $db->query("SHOW TABLES LIKE '" . $table_name . "'");
    if ($table_check && $table_check->num_rows > 0) {
        echo "<div class='info'>";
        echo "<p><strong>Table already exists!</strong></p>";
        echo "<p>The table <code>" . htmlspecialchars($table_name) . "</code> already exists in your database.</p>";
        echo "<p><a href='check_category_module_table.php'>Go back to diagnostic</a></p>";
        echo "</div>";
        echo "</div></body></html>";
        ob_end_flush();
        exit;
    }

    // Create table SQL
    $sql = "CREATE TABLE IF NOT EXISTS `" . $table_name . "` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

    echo "<div class='info'>";
    echo "<p><strong>Creating table:</strong> <code>" . htmlspecialchars($table_name) . "</code></p>";
    echo "<p><strong>SQL Query:</strong></p>";
    echo "<pre>" . htmlspecialchars($sql) . "</pre>";
    echo "</div>";

    // Execute SQL
    try {
        $result = $db->query($sql);
        
        if ($result) {
            // Verify table was created
            $verify = $db->query("SHOW TABLES LIKE '" . $table_name . "'");
            if ($verify && $verify->num_rows > 0) {
                echo "<div class='success'>";
                echo "<p>✓ <strong>Table created successfully!</strong></p>";
                echo "<p>The table <code>" . htmlspecialchars($table_name) . "</code> has been created in your database.</p>";
                echo "<p><a href='check_category_module_table.php'>View diagnostic results</a></p>";
                echo "</div>";
                
                // Show table structure
                $structure = $db->query("DESCRIBE " . $table_name);
                if ($structure && $structure->rows) {
                    echo "<h3>Table Structure</h3>";
                    echo "<table style='width:100%;border-collapse:collapse;'>";
                    echo "<tr style='background:#f0f0f0;'><th style='padding:8px;border:1px solid #ddd;'>Field</th><th style='padding:8px;border:1px solid #ddd;'>Type</th><th style='padding:8px;border:1px solid #ddd;'>Null</th><th style='padding:8px;border:1px solid #ddd;'>Key</th></tr>";
                    foreach ($structure->rows as $field) {
                        echo "<tr>";
                        echo "<td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($field['Field']) . "</td>";
                        echo "<td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($field['Type']) . "</td>";
                        echo "<td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($field['Null']) . "</td>";
                        echo "<td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($field['Key']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            } else {
                echo "<div class='error'>";
                echo "<p>✗ Table creation reported success, but table was not found.</p>";
                echo "<p>Please check your database manually.</p>";
                echo "</div>";
            }
        } else {
            echo "<div class='error'>";
            echo "<p>✗ <strong>Failed to create table</strong></p>";
            echo "<p>There was an error executing the SQL query.</p>";
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<div class='error'>";
        echo "<p>✗ <strong>Error creating table</strong></p>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "</div>";
    }

    echo "</div></body></html>";
    
} catch (Exception $e) {
    ob_clean();
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Error</title>";
    echo "<style>body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;} .error-box{max-width:800px;margin:0 auto;background:white;padding:20px;border-radius:8px;border:2px solid #dc3545;} h1{color:#dc3545;} pre{background:#f8f8f8;padding:10px;border-radius:4px;overflow-x:auto;}</style>";
    echo "</head><body><div class='error-box'>";
    echo "<h1>Error Running Script</h1>";
    echo "<p><strong>Error Message:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "</div></body></html>";
} catch (Error $e) {
    ob_clean();
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Error</title>";
    echo "<style>body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;} .error-box{max-width:800px;margin:0 auto;background:white;padding:20px;border-radius:8px;border:2px solid #dc3545;} h1{color:#dc3545;} pre{background:#f8f8f8;padding:10px;border-radius:4px;overflow-x:auto;}</style>";
    echo "</head><body><div class='error-box'>";
    echo "<h1>Fatal Error</h1>";
    echo "<p><strong>Error Message:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "</div></body></html>";
}

ob_end_flush();
?>

