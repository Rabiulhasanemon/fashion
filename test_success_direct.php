<?php
// Test script to directly access success page with order_id
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Success Page Direct Access</h1>";

// Load OpenCart
$root = dirname(__FILE__);
if (!defined('DIR_APPLICATION')) {
    define('VERSION', '2.4.0');
    require_once($root . '/config.php');
    require_once(DIR_SYSTEM . 'startup.php');
    
    $registry = new Registry();
    $loader = new Loader($registry);
    $registry->set('load', $loader);
    $config = new Config();
    $registry->set('config', $config);
    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    $registry->set('db', $db);
    
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
    
    $request = new Request();
    $registry->set('request', $request);
    $response = new Response();
    $response->addHeader('Content-Type: text/html; charset=utf-8');
    $registry->set('response', $response);
    $session = new Session();
    $registry->set('session', $session);
    
    // Set a test order_id in session (use the most recent order)
    $recent_order = $db->query("SELECT order_id FROM " . DB_PREFIX . "order ORDER BY order_id DESC LIMIT 1");
    if ($recent_order->num_rows > 0) {
        $test_order_id = $recent_order->row['order_id'];
        $session->data['order_id'] = $test_order_id;
        echo "<p>Set order_id in session: " . $test_order_id . "</p>";
    }
    
    $language = new Language($config->get('config_language'));
    $registry->set('language', $language);
    $registry->set('document', new Document());
    $registry->set('currency', new Currency($registry));
    $registry->set('tax', new Tax($registry));
    $registry->set('cart', new Cart($registry));
    $registry->set('customer', new Customer($registry));
    $event = new Event($registry);
    $registry->set('event', $event);
    $url = new SiteUrl($config->get('config_url'), $config->get('config_ssl'));
    $registry->set('url', $url);
}

echo "<h2>Test: Access Success Page Directly</h2>";
$success_url = $url->link("checkout/success", '', 'SSL');
echo "<p><strong>Success Page URL:</strong> <a href='" . htmlspecialchars($success_url) . "' target='_blank'>" . htmlspecialchars($success_url) . "</a></p>";

echo "<h2>Test: Try to Load Success Controller</h2>";
try {
    $loader->controller('checkout/success');
    $success_controller = $registry->get('controller_checkout_success');
    
    if ($success_controller) {
        echo "✓ Success controller loaded<br>";
        echo "<p>Calling success controller index() method...</p>";
        
        // Capture output
        ob_start();
        try {
            $success_controller->index();
            $output = ob_get_clean();
            
            if (!empty($output)) {
                echo "<p>✓ Success page output generated (" . strlen($output) . " bytes)</p>";
                echo "<h3>Success Page Output Preview (first 1000 chars):</h3>";
                echo "<pre style='max-height: 300px; overflow-y: scroll; background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>";
                echo htmlspecialchars(substr($output, 0, 1000));
                if (strlen($output) > 1000) {
                    echo "\n... (truncated, total length: " . strlen($output) . " bytes)";
                }
                echo "</pre>";
            } else {
                echo "<p>✗ Success page output is empty</p>";
            }
        } catch (Exception $e) {
            ob_end_clean();
            echo "<p>✗ Exception during index() call: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        }
    } else {
        echo "✗ Success controller not found<br>";
    }
} catch (Exception $e) {
    echo "✗ Exception: " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "Stack trace: <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<h2>Instructions</h2>";
echo "<ol>";
echo "<li>Click the success page URL above to test if it loads directly</li>";
echo "<li>If it loads directly, the issue is with the redirect from checkout</li>";
echo "<li>If it doesn't load, check the error logs for the success page</li>";
echo "</ol>";

