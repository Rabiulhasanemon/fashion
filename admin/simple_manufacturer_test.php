<?php
/**
 * Simple Manufacturer Test - Minimal version
 * Access: https://ruplexa1.master.com.bd/admin/simple_manufacturer_test.php
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Manufacturer Test</title>";
echo "<style>body { font-family: Arial; margin: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; }</style>";
echo "</head><body>";
echo "<h1>Simple Manufacturer Test</h1>";

try {
    // Load OpenCart
    require_once('config.php');
    echo "<p class='success'>✓ config.php loaded</p>";
    
    require_once(DIR_SYSTEM . 'startup.php');
    echo "<p class='success'>✓ startup.php loaded</p>";
    
    // Database - use the same pattern as other working scripts
    $db = new \DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    echo "<p class='success'>✓ Database connection created</p>";
    
    $prefix = DB_PREFIX;
    
    // Test database connection
    $test_query = $db->query("SELECT 1 as test");
    if ($test_query) {
        echo "<p class='success'>✓ Database query works</p>";
    } else {
        echo "<p class='error'>❌ Database query failed</p>";
        die();
    }
    
    // Check for manufacturer_id = 0
    echo "<h2>Check for manufacturer_id = 0</h2>";
    $zero_check = $db->query("SELECT COUNT(*) as count FROM {$prefix}manufacturer WHERE manufacturer_id = 0");
    $zero_count = $zero_check && $zero_check->num_rows ? (int)$zero_check->row['count'] : 0;
    if ($zero_count > 0) {
        echo "<p class='error'>❌ Found {$zero_count} record(s) with manufacturer_id = 0</p>";
        if (isset($_GET['cleanup'])) {
            $db->query("DELETE FROM {$prefix}manufacturer WHERE manufacturer_id = 0");
            $db->query("DELETE FROM {$prefix}manufacturer_description WHERE manufacturer_id = 0");
            $db->query("DELETE FROM {$prefix}manufacturer_to_store WHERE manufacturer_id = 0");
            echo "<p class='success'>✓ Cleaned up. <a href='?'>Refresh</a></p>";
        } else {
            echo "<p><a href='?cleanup=1'><button>Clean Up Now</button></a></p>";
        }
    } else {
        echo "<p class='success'>✓ No manufacturer_id = 0 records</p>";
    }
    
    // Check AUTO_INCREMENT
    echo "<h2>Check AUTO_INCREMENT</h2>";
    $ai_check = $db->query("SHOW TABLE STATUS LIKE '{$prefix}manufacturer'");
    $ai_value = 'N/A';
    if ($ai_check && $ai_check->num_rows) {
        $row = $ai_check->row;
        if (isset($row['Auto_increment'])) {
            $ai_value = $row['Auto_increment'];
        } elseif (isset($row['AUTO_INCREMENT'])) {
            $ai_value = $row['AUTO_INCREMENT'];
        }
    }
    echo "<p class='info'>Current AUTO_INCREMENT: {$ai_value}</p>";
    
    $max_check = $db->query("SELECT MAX(manufacturer_id) as max_id FROM {$prefix}manufacturer WHERE manufacturer_id > 0");
    $max_id = 0;
    if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
        $max_id = (int)$max_check->row['max_id'];
    }
    $next_id = max($max_id + 1, 1);
    echo "<p class='info'>Max manufacturer_id: {$max_id}</p>";
    echo "<p class='info'>Next expected ID: {$next_id}</p>";
    
    // Test insert
    echo "<h2>Test Insert</h2>";
    if (isset($_GET['test']) && $_GET['test'] == 'insert') {
        try {
            // Clean up
            $db->query("DELETE FROM {$prefix}manufacturer WHERE manufacturer_id = 0");
            
            // Set AUTO_INCREMENT
            $db->query("ALTER TABLE {$prefix}manufacturer AUTO_INCREMENT = {$next_id}");
            echo "<p class='info'>✓ AUTO_INCREMENT set to {$next_id}</p>";
            
            // Try insert with explicit ID
            $test_name = 'TEST_' . time();
            $insert_sql = "INSERT INTO {$prefix}manufacturer SET manufacturer_id = '" . (int)$next_id . "', name = '" . $db->escape($test_name) . "', image = '', thumb = '', sort_order = '0'";
            echo "<p class='info'>SQL: <code>" . htmlspecialchars($insert_sql) . "</code></p>";
            
            $result = $db->query($insert_sql);
            
            if ($result) {
                // Verify
                $verify = $db->query("SELECT manufacturer_id FROM {$prefix}manufacturer WHERE manufacturer_id = '" . (int)$next_id . "' LIMIT 1");
                if ($verify && $verify->num_rows) {
                    $inserted_id = (int)$verify->row['manufacturer_id'];
                    echo "<p class='success'>✓ SUCCESS! Inserted with ID: {$inserted_id}</p>";
                    
                    // Clean up
                    $db->query("DELETE FROM {$prefix}manufacturer WHERE manufacturer_id = '{$inserted_id}'");
                    echo "<p class='info'>✓ Test record deleted</p>";
                } else {
                    echo "<p class='error'>❌ Insert succeeded but record not found!</p>";
                }
            } else {
                echo "<p class='error'>❌ Insert failed!</p>";
                
                // Get error
                $error = '';
                $errno = 0;
                if (property_exists($db, 'link') && is_object($db->link)) {
                    if (property_exists($db->link, 'error')) {
                        $error = $db->link->error;
                    }
                    if (property_exists($db->link, 'errno')) {
                        $errno = $db->link->errno;
                    }
                }
                echo "<p class='error'>Error: {$error} (Code: {$errno})</p>";
            }
        } catch (Exception $e) {
            echo "<p class='error'>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p><a href='?test=insert'><button>Test Insert</button></a></p>";
    }
    
    // Check error log
    echo "<h2>Error Log</h2>";
    $log_file = DIR_LOGS . 'manufacturer_error.log';
    if (file_exists($log_file)) {
        $log_content = file_get_contents($log_file);
        $log_lines = explode("\n", $log_content);
        $recent_lines = array_slice($log_lines, -20);
        if (!empty($recent_lines)) {
            echo "<pre>" . htmlspecialchars(implode("\n", $recent_lines)) . "</pre>";
        } else {
            echo "<p class='info'>Log file is empty</p>";
        }
    } else {
        echo "<p class='info'>No error log file found</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ FATAL ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p class='error'>File: " . htmlspecialchars($e->getFile()) . " Line: " . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
} catch (Error $e) {
    echo "<p class='error'>❌ PHP ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p class='error'>File: " . htmlspecialchars($e->getFile()) . " Line: " . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<hr>";
echo "<p><a href='index.php?route=catalog/manufacturer/add'>← Back to Add Manufacturer</a></p>";
echo "</body></html>";
?>

