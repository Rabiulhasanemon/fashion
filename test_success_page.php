<?php
// Test script to verify success page loads correctly
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Success Page</h1>";

// Load OpenCart
$root = dirname(__FILE__);
if (!defined('DIR_APPLICATION')) {
    define('VERSION', '2.4.0');
    require_once($root . '/config.php');
    require_once(DIR_SYSTEM . 'startup.php');
    
    // Registry
    $registry = new Registry();
    
    // Loader
    $loader = new Loader($registry);
    $registry->set('load', $loader);
    
    // Config
    $config = new Config();
    $registry->set('config', $config);
    
    // Database
    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    $registry->set('db', $db);
    
    // Settings
    $query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' ORDER BY store_id ASC");
    foreach ($query->rows as $result) {
        if (!$result['serialized']) {
            $config->set($result['key'], $result['value']);
        } else {
            $config->set($result['key'], unserialize($result['value']));
        }
    }
    
    $config->set('config_url', HTTP_SERVER);
    $config->set('config_ssl', HTTPS_SERVER);
    
    // Request
    $request = new Request();
    $registry->set('request', $request);
    
    // Response
    $response = new Response();
    $response->addHeader('Content-Type: text/html; charset=utf-8');
    $registry->set('response', $response);
    
    // Session
    $session = new Session();
    $registry->set('session', $session);
    
    // Set a test order_id in session
    $session->data['order_id'] = 8; // Use existing order ID 8
    
    // Language
    $language = new Language($config->get('config_language'));
    $registry->set('language', $language);
    
    // Document
    $registry->set('document', new Document());
    
    // Currency
    $registry->set('currency', new Currency($registry));
    
    // Tax
    $registry->set('tax', new Tax($registry));
    
    // Cart
    $registry->set('cart', new Cart($registry));
    
    // Customer
    $registry->set('customer', new Customer($registry));
    
    // Event
    $event = new Event($registry);
    $registry->set('event', $event);
    
    // URL
    $url = new SiteUrl($config->get('config_url'), $config->get('config_ssl'));
    $registry->set('url', $url);
    
    echo "<h2>Test 1: Check Success Page URL</h2>";
    $success_url = $url->link("checkout/success", '', 'SSL');
    echo "Success URL: " . htmlspecialchars($success_url) . "<br>";
    
    // Fix URL if needed
    $success_url = str_replace('://ruplexa1.master.com.bdindex.php', '://ruplexa1.master.com.bd/index.php', $success_url);
    echo "Fixed Success URL: " . htmlspecialchars($success_url) . "<br>";
    
    echo "<h2>Test 2: Try to Load Success Controller</h2>";
    try {
        $loader->controller('checkout/success');
        $success_controller = $registry->get('controller_checkout_success');
        
        if ($success_controller) {
            echo "✓ Success controller loaded<br>";
            
            // Try to call index method
            echo "Calling success controller index() method...<br>";
            ob_start();
            $success_controller->index();
            $output = ob_get_clean();
            
            if (!empty($output)) {
                echo "✓ Success page output generated (" . strlen($output) . " bytes)<br>";
                echo "<h3>Success Page Output (first 500 chars):</h3>";
                echo "<pre>" . htmlspecialchars(substr($output, 0, 500)) . "...</pre>";
            } else {
                echo "✗ Success page output is empty<br>";
            }
        } else {
            echo "✗ Success controller not found<br>";
        }
    } catch (Exception $e) {
        echo "✗ Exception: " . htmlspecialchars($e->getMessage()) . "<br>";
        echo "Stack trace: <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
    
    echo "<h2>Test 3: Direct URL Test</h2>";
    echo "<p>Try accessing the success page directly:</p>";
    echo "<p><a href='" . htmlspecialchars($success_url) . "' target='_blank'>" . htmlspecialchars($success_url) . "</a></p>";
    echo "<p>Or with order_id in session:</p>";
    echo "<p><a href='index.php?route=checkout/success' target='_blank'>index.php?route=checkout/success</a></p>";
    
    echo "<h2>Test 4: Check Recent Error Logs for Success Page</h2>";
    $log_file = DIR_LOGS . 'error.log';
    if (file_exists($log_file)) {
        $log_lines = file($log_file);
        $recent_logs = array_slice($log_lines, -30);
        echo "<pre style='max-height: 300px; overflow-y: scroll; background: #f5f5f5; padding: 10px; border: 1px solid #ddd; font-size: 12px;'>";
        foreach ($recent_logs as $line) {
            if (stripos($line, 'success') !== false || stripos($line, 'CHECKOUT SUCCESS') !== false || stripos($line, 'order_id') !== false) {
                echo htmlspecialchars($line);
            }
        }
        echo "</pre>";
    }
    
} else {
    echo "OpenCart already loaded.";
}

