<?php
/**
 * Site Error Checker
 * Access: https://ruplexa1.master.com.bd/check_site_error.php
 * This page helps identify what's causing the HTTP 500 error
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Site Error Check</title>";
echo "<style>body { font-family: Arial; margin: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow: auto; }</style>";
echo "</head><body>";
echo "<h1>Site Error Diagnostic Tool</h1>";

try {
    echo "<h2>Step 1: Check config.php</h2>";
    if (file_exists(__DIR__ . '/config.php')) {
        require_once(__DIR__ . '/config.php');
        echo "<p class='success'>✓ config.php loaded</p>";
        
        // Check if constants are defined
        if (defined('DB_HOSTNAME')) {
            echo "<p class='success'>✓ Database constants defined</p>";
        } else {
            echo "<p class='error'>❌ Database constants not defined</p>";
        }
    } else {
        echo "<p class='error'>❌ config.php not found</p>";
        die();
    }
    
    echo "<h2>Step 2: Check startup.php</h2>";
    if (file_exists(DIR_SYSTEM . 'startup.php')) {
        require_once(DIR_SYSTEM . 'startup.php');
        echo "<p class='success'>✓ startup.php loaded</p>";
    } else {
        echo "<p class='error'>❌ startup.php not found at: " . DIR_SYSTEM . 'startup.php' . "</p>";
        die();
    }
    
    echo "<h2>Step 3: Check Database Connection</h2>";
    try {
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
        $test_query = $db->query("SELECT 1 as test");
        if ($test_query) {
            echo "<p class='success'>✓ Database connection works</p>";
        } else {
            echo "<p class='error'>❌ Database query failed</p>";
        }
    } catch (Exception $db_error) {
        echo "<p class='error'>❌ Database error: " . htmlspecialchars($db_error->getMessage()) . "</p>";
    }
    
    echo "<h2>Step 4: Check Registry and Loader</h2>";
    try {
        $registry = new Registry();
        $loader = new Loader($registry);
        $registry->set('load', $loader);
        echo "<p class='success'>✓ Registry and Loader created</p>";
    } catch (Exception $reg_error) {
        echo "<p class='error'>❌ Registry error: " . htmlspecialchars($reg_error->getMessage()) . "</p>";
    }
    
    echo "<h2>Step 5: Check URL Class</h2>";
    try {
        $config = new Config();
        $registry->set('config', $config);
        $registry->set('db', $db);
        
        // Load settings
        $query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
        foreach ($query->rows as $setting) {
            if (!$setting['serialized']) {
                $config->set($setting['key'], $setting['value']);
            } else {
                $config->set($setting['key'], unserialize($setting['value']));
            }
        }
        
        $url = new Url($config->get('config_url'), $config->get('config_ssl'));
        $registry->set('url', $url);
        echo "<p class='success'>✓ URL class created</p>";
        
        // Test URL generation
        $test_url = $url->link('account/account', '', 'SSL');
        if ($test_url) {
            echo "<p class='success'>✓ URL generation works: " . htmlspecialchars($test_url) . "</p>";
        } else {
            echo "<p class='error'>❌ URL generation failed</p>";
        }
    } catch (Exception $url_error) {
        echo "<p class='error'>❌ URL class error: " . htmlspecialchars($url_error->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($url_error->getTraceAsString()) . "</pre>";
    }
    
    echo "<h2>Step 6: Check Recent PHP Errors</h2>";
    $error_log = ini_get('error_log');
    if ($error_log && file_exists($error_log)) {
        $log_content = file_get_contents($error_log);
        $lines = explode("\n", $log_content);
        $recent_errors = array_slice($lines, -50);
        if (count($recent_errors) > 0) {
            echo "<pre style='max-height: 400px; overflow: auto;'>";
            echo htmlspecialchars(implode("\n", $recent_errors));
            echo "</pre>";
        } else {
            echo "<p class='info'>No recent errors in PHP error log</p>";
        }
    } else {
        echo "<p class='info'>PHP error log not found or not configured</p>";
    }
    
    // Check OpenCart error log
    $oc_error_log = DIR_LOGS . 'error.log';
    if (file_exists($oc_error_log)) {
        echo "<h2>Step 7: Check OpenCart Error Log</h2>";
        $oc_log_content = file_get_contents($oc_error_log);
        $oc_lines = explode("\n", $oc_log_content);
        $recent_oc_errors = array_slice($oc_lines, -50);
        if (count($recent_oc_errors) > 0) {
            echo "<pre style='max-height: 400px; overflow: auto;'>";
            echo htmlspecialchars(implode("\n", $recent_oc_errors));
            echo "</pre>";
        } else {
            echo "<p class='info'>No recent errors in OpenCart error log</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Fatal Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
} catch (Error $e) {
    echo "<p class='error'>❌ Fatal PHP Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
?>

