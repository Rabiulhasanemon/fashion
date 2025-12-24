<?php
// Debug script to check checkout flow
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Checkout Flow Debug</h1>";

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

echo "<h2>Test 1: Check Recent Orders</h2>";
$recent_orders = $db->query("SELECT order_id, firstname, lastname, email, total, date_added, order_status_id FROM " . DB_PREFIX . "order ORDER BY order_id DESC LIMIT 5");
if ($recent_orders->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Order ID</th><th>Name</th><th>Email</th><th>Total</th><th>Date</th><th>Status</th></tr>";
    foreach ($recent_orders->rows as $order) {
        echo "<tr>";
        echo "<td>" . $order['order_id'] . "</td>";
        echo "<td>" . htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) . "</td>";
        echo "<td>" . htmlspecialchars($order['email']) . "</td>";
        echo "<td>" . $order['total'] . "</td>";
        echo "<td>" . $order['date_added'] . "</td>";
        echo "<td>" . $order['order_status_id'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No recent orders found.</p>";
}

echo "<h2>Test 2: Check Error Logs for Checkout</h2>";
$log_file = DIR_LOGS . 'error.log';
if (file_exists($log_file)) {
    $log_lines = file($log_file);
    $recent_logs = array_slice($log_lines, -100);
    $checkout_logs = array();
    foreach ($recent_logs as $line) {
        if (stripos($line, 'onepagecheckout') !== false || 
            stripos($line, 'checkout') !== false || 
            stripos($line, 'success') !== false ||
            stripos($line, 'order') !== false ||
            stripos($line, 'redirect') !== false) {
            $checkout_logs[] = $line;
        }
    }
    if (!empty($checkout_logs)) {
        echo "<pre style='max-height: 400px; overflow-y: scroll; background: #f5f5f5; padding: 10px; border: 1px solid #ddd; font-size: 11px;'>";
        foreach (array_slice($checkout_logs, -50) as $line) {
            echo htmlspecialchars($line);
        }
        echo "</pre>";
    } else {
        echo "<p>No checkout-related logs found in recent entries.</p>";
    }
} else {
    echo "<p>Error log file not found: " . $log_file . "</p>";
}

echo "<h2>Test 3: Test Success Page URL</h2>";
$success_url = $url->link("checkout/success", '', 'SSL');
$success_url = str_replace('://ruplexa1.master.com.bdindex.php', '://ruplexa1.master.com.bd/index.php', $success_url);
echo "<p>Success URL: <a href='" . htmlspecialchars($success_url) . "' target='_blank'>" . htmlspecialchars($success_url) . "</a></p>";

echo "<h2>Test 4: Check Form Action URL</h2>";
$checkout_url = $url->link('checkout/onepagecheckout', '', 'SSL');
echo "<p>Checkout URL: <a href='" . htmlspecialchars($checkout_url) . "' target='_blank'>" . htmlspecialchars($checkout_url) . "</a></p>";

echo "<h2>Test 5: Check if Success Controller Exists</h2>";
$success_controller_file = DIR_APPLICATION . 'controller/checkout/success.php';
if (file_exists($success_controller_file)) {
    echo "✓ Success controller file exists<br>";
    $success_template_file = DIR_TEMPLATE . $config->get('config_template') . '/template/checkout/success.tpl';
    if (file_exists($success_template_file)) {
        echo "✓ Success template exists: " . $success_template_file . "<br>";
    } else {
        echo "✗ Success template not found: " . $success_template_file . "<br>";
        $default_template = DIR_TEMPLATE . 'default/template/checkout/success.tpl';
        if (file_exists($default_template)) {
            echo "✓ Default template exists: " . $default_template . "<br>";
        } else {
            echo "✗ Default template also not found<br>";
        }
    }
} else {
    echo "✗ Success controller file not found: " . $success_controller_file . "<br>";
}

echo "<h2>Test 6: Check Output Buffer Status</h2>";
echo "Output buffer level: " . ob_get_level() . "<br>";
if (ob_get_level() > 0) {
    echo "⚠ Output buffering is active. This might interfere with redirects.<br>";
} else {
    echo "✓ No output buffering active.<br>";
}

echo "<h2>Test 7: Check Headers Status</h2>";
if (headers_sent($file, $line)) {
    echo "⚠ Headers already sent in: " . $file . " on line " . $line . "<br>";
} else {
    echo "✓ Headers not sent yet.<br>";
}

echo "<h2>Instructions</h2>";
echo "<ol>";
echo "<li>Try placing an order through the checkout</li>";
echo "<li>Check the browser's Network tab (F12) to see if the redirect is happening</li>";
echo "<li>Check the browser console for any JavaScript errors</li>";
echo "<li>Check the error logs above for any PHP errors</li>";
echo "<li>If you see a redirect happening but a white page, check the success page URL directly</li>";
echo "</ol>";



