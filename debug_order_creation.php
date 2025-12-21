<?php
// Debug script to test order creation
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

echo "<h1>Order Creation Debug</h1>";

// Load OpenCart
$root = dirname(__FILE__);
if (!defined('DIR_APPLICATION')) {
    require_once($root . '/config.php');
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
    
    // Store
    $store_id = 0;
    $query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'http://', '') = '" . $db->escape($_SERVER['HTTP_HOST']) . "' OR REPLACE(`url`, 'https://', '') = '" . $db->escape($_SERVER['HTTP_HOST']) . "'");
    
    if ($query->num_rows) {
        $config->set('config_store_id', $query->row['store_id']);
    } else {
        $config->set('config_store_id', 0);
    }
    
    // Settings
    $query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");
    
    foreach ($query->rows as $result) {
        if (!$result['serialized']) {
            $config->set($result['key'], $result['value']);
        } else {
            $config->set($result['key'], unserialize($result['value']));
        }
    }
    
    // Loader
    $loader = new Loader($registry);
    $registry->set('load', $loader);
    
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
    
    // Language
    $language = new Language($config->get('config_admin_language'));
    $language->load('default');
    $registry->set('language', $language);
    
    // Document
    $registry->set('document', new Document());
    
    // Currency
    $registry->set('currency', new Currency($registry));
    
    // User
    $registry->set('user', new Cart\User($registry));
    
    // OpenBay Pro
    $registry->set('openbay', new OpenBay($registry));
    
    // Event
    $event = new Event($registry);
    $registry->set('event', $event);
    
    // Event Register
    if ($config->has('action_event')) {
        foreach ($config->get('action_event') as $key => $value) {
            $event->register($key, new Action($value));
        }
    }
    
    // Front Controller
    $controller = new Front($registry);
    
    // Router
    if (isset($request->get['route'])) {
        $action = new Action($request->get['route']);
    }
    
    // Load order model
    $loader->model('checkout/order');
    $model_order = $registry->get('model_checkout_order');
    
    echo "<h2>Test 1: Database Connection</h2>";
    if ($db) {
        echo "✓ Database connection: SUCCESS<br>";
    } else {
        echo "✗ Database connection: FAILED<br>";
        exit;
    }
    
    echo "<h2>Test 2: Check Order Table Structure</h2>";
    $table_check = $db->query("SHOW TABLES LIKE '" . DB_PREFIX . "order'");
    if ($table_check && $table_check->num_rows > 0) {
        echo "✓ Order table exists<br>";
        
        // Check for AUTO_INCREMENT
        $status_check = $db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "order'");
        if ($status_check && $status_check->num_rows > 0) {
            $auto_inc = isset($status_check->row['Auto_increment']) ? $status_check->row['Auto_increment'] : null;
            echo "AUTO_INCREMENT value: " . ($auto_inc ? $auto_inc : 'NOT SET') . "<br>";
            
            if (!$auto_inc || $auto_inc <= 0) {
                echo "⚠ WARNING: AUTO_INCREMENT is not set correctly!<br>";
            }
        }
        
        // Check for order_id = 0
        $zero_check = $db->query("SELECT order_id FROM " . DB_PREFIX . "order WHERE order_id = 0 LIMIT 1");
        if ($zero_check && $zero_check->num_rows > 0) {
            echo "⚠ WARNING: Record with order_id = 0 exists! This will cause insert failures.<br>";
        }
    } else {
        echo "✗ Order table does not exist!<br>";
        exit;
    }
    
    echo "<h2>Test 3: Check Recent Orders</h2>";
    $recent_orders = $db->query("SELECT order_id, firstname, lastname, email, total, order_status_id, date_added FROM " . DB_PREFIX . "order ORDER BY order_id DESC LIMIT 5");
    if ($recent_orders && $recent_orders->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Order ID</th><th>Name</th><th>Email</th><th>Total</th><th>Status</th><th>Date</th></tr>";
        foreach ($recent_orders->rows as $order) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($order['order_id']) . "</td>";
            echo "<td>" . htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) . "</td>";
            echo "<td>" . htmlspecialchars($order['email']) . "</td>";
            echo "<td>" . htmlspecialchars($order['total']) . "</td>";
            echo "<td>" . htmlspecialchars($order['order_status_id']) . "</td>";
            echo "<td>" . htmlspecialchars($order['date_added']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No orders found in database.<br>";
    }
    
    echo "<h2>Test 4: Test Order Data Structure</h2>";
    $test_order_data = array(
        'invoice_prefix' => $config->get('config_invoice_prefix'),
        'store_id' => $config->get('config_store_id'),
        'store_name' => $config->get('config_name'),
        'store_url' => HTTP_SERVER,
        'customer_id' => 0,
        'customer_group_id' => $config->get('config_customer_group_id'),
        'firstname' => 'Test',
        'lastname' => 'User',
        'email' => 'test@example.com',
        'telephone' => '01712345678',
        'fax' => '',
        'custom_field' => array(),
        'payment_firstname' => 'Test',
        'payment_lastname' => 'User',
        'payment_company' => '',
        'payment_address_1' => 'Test Address',
        'payment_address_2' => '',
        'payment_city' => 'Dhaka',
        'payment_postcode' => '1200',
        'payment_country' => 'Bangladesh',
        'payment_country_id' => 19,
        'payment_zone' => 'Dhaka',
        'payment_zone_id' => 271,
        'payment_region' => '',
        'payment_region_id' => 0,
        'payment_address_format' => '',
        'payment_custom_field' => array(),
        'payment_method' => 'Cash On Delivery',
        'payment_code' => 'cod',
        'shipping_firstname' => 'Test',
        'shipping_lastname' => 'User',
        'shipping_company' => '',
        'shipping_address_1' => 'Test Address',
        'shipping_address_2' => '',
        'shipping_city' => 'Dhaka',
        'shipping_postcode' => '1200',
        'shipping_country' => 'Bangladesh',
        'shipping_country_id' => 19,
        'shipping_zone' => 'Dhaka',
        'shipping_zone_id' => 271,
        'shipping_region' => '',
        'shipping_region_id' => 0,
        'shipping_address_format' => '',
        'shipping_custom_field' => array(),
        'shipping_method' => 'Flat Shipping Rate',
        'shipping_code' => 'flat.flat',
        'comment' => 'Test order',
        'total' => 100.00,
        'order_status_id' => $config->get('config_order_status_id'),
        'affiliate_id' => 0,
        'commission' => 0,
        'marketing_id' => 0,
        'tracking' => '',
        'language_id' => $config->get('config_language_id'),
        'currency_id' => 1,
        'currency_code' => 'BDT',
        'currency_value' => 1.0000,
        'ip' => '127.0.0.1',
        'forwarded_ip' => '',
        'user_agent' => 'Test',
        'accept_language' => 'en',
        'products' => array(
            array(
                'product_id' => 1,
                'name' => 'Test Product',
                'model' => 'TEST001',
                'quantity' => 1,
                'price' => 100.00,
                'total' => 100.00,
                'tax' => 0,
                'reward' => 0,
                'option' => array()
            )
        ),
        'vouchers' => array(),
        'totals' => array(
            array(
                'code' => 'sub_total',
                'title' => 'Sub-Total',
                'value' => 100.00,
                'sort_order' => 1
            ),
            array(
                'code' => 'total',
                'title' => 'Total',
                'value' => 100.00,
                'sort_order' => 2
            )
        ),
        'emi' => 0,
        'emi_tenure' => 0
    );
    
    echo "Test order data prepared with " . count($test_order_data) . " fields.<br>";
    echo "Required fields check:<br>";
    $required_fields = array('invoice_prefix', 'store_id', 'firstname', 'lastname', 'email', 'telephone', 'payment_firstname', 'payment_lastname', 'payment_address_1', 'payment_city', 'payment_country_id', 'payment_zone_id', 'payment_method', 'payment_code', 'total', 'order_status_id');
    foreach ($required_fields as $field) {
        if (isset($test_order_data[$field])) {
            echo "✓ $field: " . (is_array($test_order_data[$field]) ? 'ARRAY' : htmlspecialchars($test_order_data[$field])) . "<br>";
        } else {
            echo "✗ $field: MISSING<br>";
        }
    }
    
    echo "<h2>Test 5: Try to Create Test Order</h2>";
    try {
        $test_order_id = $model_order->addOrder($test_order_data);
        if ($test_order_id && $test_order_id > 0) {
            echo "✓ Test order created successfully! Order ID: " . $test_order_id . "<br>";
            
            // Verify order exists
            $verify = $db->query("SELECT * FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$test_order_id . "'");
            if ($verify && $verify->num_rows > 0) {
                echo "✓ Order verified in database<br>";
                
                // Delete test order
                $db->query("DELETE FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$test_order_id . "'");
                $db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$test_order_id . "'");
                $db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$test_order_id . "'");
                echo "✓ Test order deleted<br>";
            } else {
                echo "✗ Order not found in database after creation!<br>";
            }
        } else {
            echo "✗ Order creation failed! Returned: " . ($test_order_id ? $test_order_id : 'FALSE/0') . "<br>";
            echo "Check error logs for details.<br>";
        }
    } catch (Exception $e) {
        echo "✗ Exception during order creation: " . htmlspecialchars($e->getMessage()) . "<br>";
        echo "Stack trace: <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
    
    echo "<h2>Test 6: Check Error Logs</h2>";
    $log_file = DIR_LOGS . 'error.log';
    if (file_exists($log_file)) {
        $log_lines = file($log_file);
        $recent_logs = array_slice($log_lines, -20);
        echo "<pre>";
        foreach ($recent_logs as $line) {
            if (stripos($line, 'order') !== false || stripos($line, 'addOrder') !== false || stripos($line, 'onepagecheckout') !== false) {
                echo htmlspecialchars($line);
            }
        }
        echo "</pre>";
    } else {
        echo "Error log file not found: " . $log_file . "<br>";
    }
    
    echo "<h2>Debug Complete</h2>";
    echo "Check the results above to identify the issue.";
    
    ob_end_flush();
} else {
    echo "OpenCart already loaded. Please access this script directly.";
}

