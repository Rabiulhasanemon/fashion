<?php
// Debug script to check checkout process and view error logs
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
echo "<!DOCTYPE html><html><head><title>Checkout Debug</title><style>body{font-family:Arial,sans-serif;margin:20px;}pre{background:#f5f5f5;padding:10px;border:1px solid #ddd;overflow:auto;}table{border-collapse:collapse;width:100%;}th,td{border:1px solid #ddd;padding:8px;text-align:left;}th{background:#f0f0f0;}</style></head><body>";
echo "<h1>Checkout Process Debug</h1>";
echo "<hr>";

try {
	require_once('config.php');
	require_once(DIR_SYSTEM . 'startup.php');
	
	$registry = new Registry();
	$loader = new Loader($registry);
	$registry->set('load', $loader);
	
	$config = new Config();
	$registry->set('config', $config);
	
	$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
	$registry->set('db', $db);
	
	// Load settings
	$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0'");
	foreach ($query->rows as $result) {
		if (!isset($result['serialized']) || !$result['serialized']) {
			$config->set($result['key'], $result['value']);
		} else {
			$config->set($result['key'], unserialize($result['value']));
		}
	}
	
	echo "<h2>1. Check Error Logs</h2>";
	$log_file = DIR_LOGS . 'error.log';
	if (file_exists($log_file)) {
		$log_content = file_get_contents($log_file);
		$log_lines = explode("\n", $log_content);
		$recent_logs = array_slice($log_lines, -50); // Last 50 lines
		echo "<h3>Recent Error Logs (Last 50 lines):</h3>";
		echo "<pre>" . htmlspecialchars(implode("\n", $recent_logs)) . "</pre>";
	} else {
		echo "<p>Error log file not found at: " . $log_file . "</p>";
	}
	
	echo "<hr>";
	
	echo "<h2>2. Check Recent Orders</h2>";
	try {
		$orders = $db->query("SELECT order_id, firstname, email, telephone, total, order_status_id, date_added FROM " . DB_PREFIX . "order ORDER BY order_id DESC LIMIT 5");
		if ($orders && $orders->num_rows > 0) {
			echo "<table>";
			echo "<tr><th>Order ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Total</th><th>Status</th><th>Date</th></tr>";
			foreach ($orders->rows as $order) {
				echo "<tr>";
				echo "<td>" . (isset($order['order_id']) ? $order['order_id'] : 'N/A') . "</td>";
				echo "<td>" . htmlspecialchars(isset($order['firstname']) ? $order['firstname'] : 'N/A') . "</td>";
				echo "<td>" . htmlspecialchars(isset($order['email']) ? $order['email'] : 'N/A') . "</td>";
				echo "<td>" . htmlspecialchars(isset($order['telephone']) ? $order['telephone'] : 'N/A') . "</td>";
				echo "<td>" . (isset($order['total']) ? $order['total'] : 'N/A') . "</td>";
				echo "<td>" . (isset($order['order_status_id']) ? $order['order_status_id'] : 'N/A') . "</td>";
				echo "<td>" . (isset($order['date_added']) ? $order['date_added'] : 'N/A') . "</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "<p>No recent orders found</p>";
		}
	} catch (Exception $e) {
		echo "<p>Error checking orders: " . htmlspecialchars($e->getMessage()) . "</p>";
	}
	
	echo "<hr>";
	
	echo "<h2>3. Check Payment Methods</h2>";
	try {
		$payment_methods = $db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE type = 'payment' AND code != ''");
		if ($payment_methods && $payment_methods->num_rows > 0) {
			echo "<p>Available payment methods:</p>";
			echo "<ul>";
			foreach ($payment_methods->rows as $method) {
				$code = isset($method['code']) ? $method['code'] : 'N/A';
				$status = isset($method['status']) ? ($method['status'] ? 'Enabled' : 'Disabled') : 'Unknown';
				echo "<li>" . htmlspecialchars($code) . " - " . $status . "</li>";
			}
			echo "</ul>";
		} else {
			echo "<p>No payment methods found</p>";
		}
	} catch (Exception $e) {
		echo "<p>Error checking payment methods: " . htmlspecialchars($e->getMessage()) . "</p>";
	}
	
	echo "<hr>";
	
	echo "<h2>4. Test URL Generation</h2>";
	try {
		$loader->model('checkout/order');
		$url = new Url(HTTP_SERVER, HTTPS_SERVER);
		$registry->set('url', $url);
		
		$test_urls = array(
			'checkout/success' => $url->link('checkout/success', '', 'SSL'),
			'checkout/confirm' => $url->link('checkout/confirm', '', 'SSL'),
			'payment/cod/confirm' => $url->link('payment/cod/confirm', '', 'SSL'),
		);
		
		echo "<table>";
		echo "<tr><th>Route</th><th>Generated URL</th></tr>";
		foreach ($test_urls as $route => $generated_url) {
			echo "<tr>";
			echo "<td>" . htmlspecialchars($route) . "</td>";
			echo "<td>" . htmlspecialchars($generated_url ? $generated_url : 'FAILED') . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	} catch (Exception $e) {
		echo "<p>Error testing URLs: " . htmlspecialchars($e->getMessage()) . "</p>";
	}
	
	echo "<hr>";
	echo "<h2>5. Instructions</h2>";
	echo "<p>1. Try to place an order and click 'Confirm Order'</p>";
	echo "<p>2. Check the error logs above for any errors</p>";
	echo "<p>3. Check if a new order was created in the recent orders table</p>";
	echo "<p>4. Share the error log output if you see any errors</p>";
	
} catch (Exception $e) {
	echo "<h2 style='color:red;'>Error</h2>";
	echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
	echo "<p>File: " . htmlspecialchars($e->getFile()) . "</p>";
	echo "<p>Line: " . $e->getLine() . "</p>";
}

echo "</body></html>";
ob_end_flush();



