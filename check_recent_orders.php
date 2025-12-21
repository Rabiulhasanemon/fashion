<?php
// Quick script to check if orders are being created
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Check Recent Orders & Error Logs</h1>";

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
}

echo "<h2>1. Recent Orders (Last 10)</h2>";
$recent_orders = $db->query("SELECT order_id, firstname, lastname, email, total, date_added, order_status_id FROM " . DB_PREFIX . "order ORDER BY order_id DESC LIMIT 10");
if ($recent_orders->num_rows > 0) {
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr style='background: #f0f0f0;'><th>Order ID</th><th>Name</th><th>Email</th><th>Total</th><th>Date</th><th>Status</th></tr>";
    foreach ($recent_orders->rows as $order) {
        $date = new DateTime($order['date_added']);
        $is_recent = (time() - $date->getTimestamp()) < 3600; // Last hour
        $row_style = $is_recent ? "style='background: #e8f5e9;'" : "";
        echo "<tr $row_style>";
        echo "<td>" . $order['order_id'] . "</td>";
        echo "<td>" . htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) . "</td>";
        echo "<td>" . htmlspecialchars($order['email']) . "</td>";
        echo "<td>" . $order['total'] . "</td>";
        echo "<td>" . $order['date_added'] . ($is_recent ? " <strong>(RECENT!)</strong>" : "") . "</td>";
        echo "<td>" . $order['order_status_id'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'><strong>No orders found in database!</strong></p>";
}

echo "<h2>2. Recent Error Logs (Last 50 lines - Checkout Related)</h2>";
$log_file = DIR_LOGS . 'error.log';
if (file_exists($log_file)) {
    $log_lines = file($log_file);
    $recent_logs = array_slice($log_lines, -100); // Last 100 lines
    $checkout_logs = array();
    $order_logs = array();
    
    foreach ($recent_logs as $line) {
        // Look for checkout/order related logs
        if (stripos($line, 'onepagecheckout') !== false || 
            stripos($line, 'addOrder') !== false ||
            stripos($line, 'ORDER CREATION') !== false ||
            stripos($line, 'Validation result') !== false ||
            stripos($line, 'Required field') !== false ||
            stripos($line, 'INSERT') !== false) {
            $checkout_logs[] = $line;
        }
        // Also get any logs from today
        if (stripos($line, date('Y-m-d')) !== false) {
            $order_logs[] = $line;
        }
    }
    
    if (!empty($checkout_logs)) {
        echo "<h3>Checkout/Order Related Logs:</h3>";
        echo "<pre style='max-height: 400px; overflow-y: scroll; background: #f5f5f5; padding: 10px; border: 1px solid #ddd; font-size: 11px;'>";
        foreach (array_slice($checkout_logs, -30) as $line) {
            echo htmlspecialchars($line);
        }
        echo "</pre>";
    } else {
        echo "<p style='color: orange;'><strong>No checkout-related logs found in recent entries.</strong></p>";
        echo "<p>This might mean:</p>";
        echo "<ul>";
        echo "<li>The form is not being submitted (no POST request)</li>";
        echo "<li>Form validation is failing before reaching order creation</li>";
        echo "<li>JavaScript is preventing form submission</li>";
        echo "</ul>";
    }
    
    if (!empty($order_logs) && count($order_logs) > count($checkout_logs)) {
        echo "<h3>Today's Error Logs (Last 20):</h3>";
        echo "<pre style='max-height: 300px; overflow-y: scroll; background: #fff3cd; padding: 10px; border: 1px solid #ffc107; font-size: 11px;'>";
        foreach (array_slice($order_logs, -20) as $line) {
            echo htmlspecialchars($line);
        }
        echo "</pre>";
    }
} else {
    echo "<p style='color: red;'>Error log file not found: " . $log_file . "</p>";
}

echo "<h2>3. Database Check</h2>";
$table_check = $db->query("SHOW TABLES LIKE '" . DB_PREFIX . "order'");
if ($table_check->num_rows > 0) {
    echo "✓ Order table exists<br>";
    
    $status_check = $db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "order'");
    if ($status_check && $status_check->num_rows > 0) {
        $auto_inc = isset($status_check->row['Auto_increment']) ? $status_check->row['Auto_increment'] : null;
        echo "AUTO_INCREMENT value: " . ($auto_inc ? $auto_inc : 'NOT SET') . "<br>";
        
        $zero_check = $db->query("SELECT order_id FROM " . DB_PREFIX . "order WHERE order_id = 0 LIMIT 1");
        if ($zero_check && $zero_check->num_rows > 0) {
            echo "<span style='color: red;'>⚠ WARNING: Record with order_id = 0 exists!</span><br>";
        } else {
            echo "✓ No order_id = 0 records<br>";
        }
    }
} else {
    echo "<span style='color: red;'>✗ Order table does not exist!</span><br>";
}

echo "<h2>4. Instructions</h2>";
echo "<ol>";
echo "<li><strong>Try placing an order NOW</strong> (while this page is open)</li>";
echo "<li>Fill in all required fields on the checkout page</li>";
echo "<li>Click 'Confirm Order'</li>";
echo "<li><strong>Refresh this page</strong> to see if a new order appears</li>";
echo "<li>Check the error logs above for any new messages</li>";
echo "<li>Look for messages starting with '=== ONEPAGECHECKOUT DEBUG ===' or '=== addOrder() called ==='</li>";
echo "</ol>";

echo "<p style='background: #fff3cd; padding: 15px; border: 1px solid #ffc107; border-radius: 5px;'>";
echo "<strong>Important:</strong> If you don't see any checkout-related logs after trying to place an order, ";
echo "it means the form is not being submitted or validation is failing silently. ";
echo "Check the browser console (F12) for JavaScript errors.";
echo "</p>";

