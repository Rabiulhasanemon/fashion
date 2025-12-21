<?php
// Debug script to test registration and database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

echo "<!DOCTYPE html><html><head><title>Registration Debug Test</title><style>body{font-family:Arial,sans-serif;margin:20px;}table{border-collapse:collapse;width:100%;}th,td{border:1px solid #ddd;padding:8px;text-align:left;}th{background-color:#f2f2f2;}</style></head><body>";
echo "<h1>Registration Debug Test</h1>";
echo "<hr>";

try {
	// Check if config.php exists
	if (!file_exists('config.php')) {
		throw new Exception('config.php not found in current directory');
	}
	
	require_once('config.php');
	
	// Check if DIR_SYSTEM is defined
	if (!defined('DIR_SYSTEM')) {
		throw new Exception('DIR_SYSTEM not defined after loading config.php');
	}
	
	// Check if startup.php exists
	if (!file_exists(DIR_SYSTEM . 'startup.php')) {
		throw new Exception('startup.php not found at: ' . DIR_SYSTEM . 'startup.php');
	}
	
	require_once(DIR_SYSTEM . 'startup.php');
	
	// Registry
	$registry = new Registry();
	
	// Loader
	$loader = new Loader($registry);
	$registry->set('load', $loader);
	
	// Config
	$config = new Config();
	$registry->set('config', $config);
	
	// Check database constants
	if (!defined('DB_DRIVER') || !defined('DB_HOSTNAME') || !defined('DB_USERNAME') || !defined('DB_PASSWORD') || !defined('DB_DATABASE')) {
		throw new Exception('Database constants not defined in config.php');
	}
	
	// Database
	echo "<h2>Test 1: Database Connection</h2>";
	try {
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
		$registry->set('db', $db);
		
		$test_query = $db->query("SELECT 1 as test");
		if ($test_query) {
			echo "✓ Database connection: <strong>SUCCESS</strong><br>";
		} else {
			echo "✗ Database connection: <strong>FAILED</strong><br>";
		}
	} catch (Exception $e) {
		echo "✗ Database connection error: " . htmlspecialchars($e->getMessage()) . "<br>";
		throw $e;
	}
	
	// Store
	$config->set('config_store_id', 0);
	
	// Load settings
	echo "<h2>Loading Settings...</h2>";
	try {
		$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");
		
		if ($query && isset($query->rows)) {
			foreach ($query->rows as $result) {
				if (isset($result['key']) && isset($result['value'])) {
					if (!isset($result['serialized']) || !$result['serialized']) {
						$config->set($result['key'], $result['value']);
					} else {
						$config->set($result['key'], unserialize($result['value']));
					}
				}
			}
			echo "✓ Settings loaded: " . count($query->rows) . " settings<br>";
		} else {
			echo "⚠ No settings found or query failed<br>";
		}
	} catch (Exception $e) {
		echo "⚠ Settings load error: " . htmlspecialchars($e->getMessage()) . "<br>";
	}
	
	echo "<hr>";
	
	// Test 2: Check if customer table exists
	echo "<h2>Test 2: Customer Table Check</h2>";
	try {
		$table_check = $db->query("SHOW TABLES LIKE '" . DB_PREFIX . "customer'");
		if ($table_check && $table_check->num_rows > 0) {
			echo "✓ Customer table exists<br>";
			
			// Check table structure
			$structure = $db->query("DESCRIBE " . DB_PREFIX . "customer");
			if ($structure && isset($structure->rows)) {
				echo "Table columns: ";
				$has_auto_increment = false;
				foreach ($structure->rows as $col) {
					if (isset($col['Field'])) {
						echo $col['Field'] . " (" . (isset($col['Type']) ? $col['Type'] : 'N/A');
						if (isset($col['Extra']) && strpos($col['Extra'], 'auto_increment') !== false) {
							echo " AUTO_INCREMENT";
							$has_auto_increment = true;
						}
						echo "), ";
					}
				}
				echo "<br>";
				
				if (!$has_auto_increment) {
					echo "⚠ <strong>WARNING: customer_id column may not have AUTO_INCREMENT!</strong><br>";
				}
			}
			
			// Check for customer_id = 0
			try {
				$zero_check = $db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE customer_id = 0 LIMIT 1");
				if ($zero_check && $zero_check->num_rows > 0) {
					echo "⚠ <strong>WARNING: Record with customer_id = 0 exists! This will cause insert failures.</strong><br>";
				} else {
					echo "✓ No customer_id = 0 records found<br>";
				}
			} catch (Exception $e) {
				echo "⚠ Could not check for customer_id = 0: " . htmlspecialchars($e->getMessage()) . "<br>";
			}
			
			// Check AUTO_INCREMENT value
			try {
				$ai_check = $db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "customer'");
				if ($ai_check && $ai_check->num_rows > 0 && isset($ai_check->row['Auto_increment'])) {
					echo "AUTO_INCREMENT value: " . $ai_check->row['Auto_increment'] . "<br>";
					if ($ai_check->row['Auto_increment'] <= 0) {
						echo "⚠ <strong>WARNING: AUTO_INCREMENT is 0 or negative!</strong><br>";
					}
				}
			} catch (Exception $e) {
				echo "⚠ Could not check AUTO_INCREMENT: " . htmlspecialchars($e->getMessage()) . "<br>";
			}
		} else {
			echo "✗ Customer table does NOT exist!<br>";
		}
	} catch (Exception $e) {
		echo "✗ Table check error: " . htmlspecialchars($e->getMessage()) . "<br>";
	}
	
	// Test 3: Check customer group
	echo "<h2>Test 3: Customer Group Check</h2>";
	$customer_group_id = $config->get('config_customer_group_id');
	echo "Default customer group ID: " . ($customer_group_id ? $customer_group_id : 'NOT SET') . "<br>";
	
	if ($customer_group_id) {
		try {
			$group_check = $db->query("SELECT * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
			if ($group_check && $group_check->num_rows > 0) {
				$group_name = isset($group_check->row['name']) ? $group_check->row['name'] : 'N/A';
				echo "✓ Customer group exists: " . htmlspecialchars($group_name) . "<br>";
				$approval = isset($group_check->row['approval']) ? $group_check->row['approval'] : 0;
				echo "  Approval required: " . ($approval ? 'YES' : 'NO') . "<br>";
			} else {
				echo "✗ Customer group NOT found!<br>";
			}
		} catch (Exception $e) {
			echo "✗ Customer group check error: " . htmlspecialchars($e->getMessage()) . "<br>";
		}
	} else {
		echo "✗ Customer group ID not configured!<br>";
	}
	
	// Test 4: Test Registration Data
	echo "<h2>Test 4: Test Registration Insert</h2>";
	
	// Create test data
	$test_email = 'test_' . time() . '@example.com';
	$test_data = array(
		'firstname' => 'Test',
		'lastname' => 'User',
		'email' => $test_email,
		'telephone' => '01712345678',
		'password' => 'test123456',
		'address_1' => 'Test Address',
		'city' => 'Dhaka',
		'zone_id' => 0,
		'region_id' => 0,
		'country_id' => $config->get('config_country_id') ? $config->get('config_country_id') : 0
	);
	
	echo "Test data prepared:<br>";
	echo "- Email: " . htmlspecialchars($test_data['email']) . "<br>";
	echo "- Firstname: " . htmlspecialchars($test_data['firstname']) . "<br>";
	echo "- Telephone: " . htmlspecialchars($test_data['telephone']) . "<br>";
	echo "- Customer Group ID: " . $customer_group_id . "<br>";
	echo "<br>";
	
	// Test direct database insert
	try {
		$salt = substr(md5(uniqid(rand(), true)), 0, 9);
		$password_hash = sha1($salt . sha1($salt . sha1($test_data['password'])));
		$ip = '127.0.0.1';
		
		$test_sql = "INSERT INTO " . DB_PREFIX . "customer SET 
			customer_group_id = '" . (int)$customer_group_id . "', 
			store_id = '" . (int)$config->get('config_store_id') . "', 
			firstname = '" . $db->escape($test_data['firstname']) . "', 
			lastname = '" . $db->escape($test_data['lastname']) . "', 
			email = '" . $db->escape($test_data['email']) . "', 
			telephone = '" . $db->escape($test_data['telephone']) . "', 
			salt = '" . $db->escape($salt) . "', 
			password = '" . $db->escape($password_hash) . "', 
			newsletter = '0', 
			ip = '" . $db->escape($ip) . "', 
			status = '1', 
			approved = '1', 
			date_added = NOW()";
		
		echo "SQL Query: <pre>" . htmlspecialchars($test_sql) . "</pre><br>";
		
		$insert_result = $db->query($test_sql);
		
		if ($insert_result !== false) {
			// Query database directly to get the inserted ID (more reliable than getLastId)
			$verify = $db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $db->escape($test_data['email']) . "' ORDER BY customer_id DESC LIMIT 1");
			
			if ($verify && $verify->num_rows > 0) {
				$customer_id = (int)$verify->row['customer_id'];
				$getLastId_value = $db->getLastId();
				echo "✓ Direct INSERT: <strong>SUCCESS</strong><br>";
				echo "  Customer ID (from query): " . $customer_id . "<br>";
				echo "  getLastId() returned: " . ($getLastId_value ? $getLastId_value : '0/FALSE') . "<br>";
				
				// Verify the insert
				$verify_full = $db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
				if ($verify_full && $verify_full->num_rows > 0) {
					echo "✓ Verification: Customer found in database<br>";
					echo "  Email: " . htmlspecialchars($verify_full->row['email']) . "<br>";
					echo "  Firstname: " . htmlspecialchars($verify_full->row['firstname']) . "<br>";
					echo "  Approved: " . $verify_full->row['approved'] . "<br>";
					echo "  Status: " . $verify_full->row['status'] . "<br>";
					
					// Clean up test data
					$db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
					echo "<br>✓ Test customer deleted<br>";
				} else {
					echo "✗ Verification: Customer NOT found in database!<br>";
				}
			} else {
				echo "✗ Direct INSERT: Could not find inserted customer<br>";
			}
		} else {
			echo "✗ Direct INSERT: <strong>FAILED</strong><br>";
			// Check if customer was inserted anyway (race condition or error handling)
			$check_inserted = $db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $db->escape($test_data['email']) . "' LIMIT 1");
			if ($check_inserted && $check_inserted->num_rows > 0) {
				echo "  ⚠ Customer was inserted despite error! ID: " . $check_inserted->row['customer_id'] . "<br>";
				$customer_id = (int)$check_inserted->row['customer_id'];
				// Clean up
				$db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
				echo "  ✓ Test customer deleted<br>";
			} else {
				echo "  Customer was NOT inserted<br>";
			}
		}
	} catch (Exception $e) {
		echo "✗ Insert error: " . htmlspecialchars($e->getMessage()) . "<br>";
		echo "  File: " . htmlspecialchars($e->getFile()) . "<br>";
		echo "  Line: " . $e->getLine() . "<br>";
	}
	
	// Test 5: Test using Model
	echo "<h2>Test 5: Test Using Customer Model</h2>";
	try {
		$loader->model('account/customer');
		$model = $registry->get('model_account_customer');
		
		if (!$model) {
			throw new Exception('Could not load customer model');
		}
		
		$test_email2 = 'test_model_' . time() . '@example.com';
		$test_data2 = array(
			'firstname' => 'Model',
			'lastname' => 'Test',
			'email' => $test_email2,
			'telephone' => '01812345678',
			'password' => 'test123456',
			'address_1' => 'Test Address',
			'city' => 'Dhaka',
			'zone_id' => 0,
			'region_id' => 0,
			'country_id' => $config->get('config_country_id') ? $config->get('config_country_id') : 0
		);
		
		echo "Testing addCustomer() method...<br>";
		$customer_id = $model->addCustomer($test_data2);
		
		if ($customer_id && $customer_id > 0) {
			echo "✓ Model addCustomer(): <strong>SUCCESS</strong><br>";
			echo "  Customer ID: " . $customer_id . "<br>";
			
			// Verify
			$verify = $db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
			if ($verify && $verify->num_rows > 0) {
				echo "✓ Verification: Customer found<br>";
				echo "  Email: " . htmlspecialchars($verify->row['email']) . "<br>";
				
				// Clean up
				$db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
				echo "✓ Test customer deleted<br>";
			} else {
				echo "✗ Verification: Customer NOT found!<br>";
			}
		} else {
			echo "✗ Model addCustomer(): <strong>FAILED</strong><br>";
			echo "  Returned: " . ($customer_id === false ? 'FALSE' : ($customer_id === 0 ? '0' : 'NULL')) . "<br>";
		}
	} catch (Exception $e) {
		echo "✗ Model test error: " . htmlspecialchars($e->getMessage()) . "<br>";
		echo "  File: " . htmlspecialchars($e->getFile()) . "<br>";
		echo "  Line: " . $e->getLine() . "<br>";
	}
	
	// Test 6: Check recent registrations
	echo "<h2>Test 6: Recent Customer Registrations</h2>";
	try {
		$recent = $db->query("SELECT customer_id, firstname, email, telephone, approved, status, date_added FROM " . DB_PREFIX . "customer ORDER BY customer_id DESC LIMIT 5");
		if ($recent && $recent->num_rows > 0) {
			echo "Recent customers in database:<br>";
			echo "<table>";
			echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Approved</th><th>Status</th><th>Date</th></tr>";
			foreach ($recent->rows as $row) {
				echo "<tr>";
				echo "<td>" . (isset($row['customer_id']) ? $row['customer_id'] : 'N/A') . "</td>";
				echo "<td>" . htmlspecialchars(isset($row['firstname']) ? $row['firstname'] : 'N/A') . "</td>";
				echo "<td>" . htmlspecialchars(isset($row['email']) ? $row['email'] : 'N/A') . "</td>";
				echo "<td>" . htmlspecialchars(isset($row['telephone']) ? $row['telephone'] : 'N/A') . "</td>";
				echo "<td>" . (isset($row['approved']) ? $row['approved'] : 'N/A') . "</td>";
				echo "<td>" . (isset($row['status']) ? $row['status'] : 'N/A') . "</td>";
				echo "<td>" . (isset($row['date_added']) ? $row['date_added'] : 'N/A') . "</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "No customers found in database<br>";
		}
	} catch (Exception $e) {
		echo "Error checking recent customers: " . htmlspecialchars($e->getMessage()) . "<br>";
	}
	
} catch (Exception $e) {
	echo "<h2 style='color:red;'>Fatal Error</h2>";
	echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
	echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
	echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
	if (function_exists('error_get_last')) {
		$last_error = error_get_last();
		if ($last_error) {
			echo "<p><strong>Last PHP Error:</strong> " . htmlspecialchars($last_error['message']) . " in " . htmlspecialchars($last_error['file']) . " on line " . $last_error['line'] . "</p>";
		}
	}
}

echo "<hr>";
echo "<h2>Debug Complete</h2>";
echo "<p>Check the results above to identify the issue.</p>";
echo "</body></html>";

ob_end_flush();
