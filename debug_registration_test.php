<?php
// Debug script to test registration and database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('config.php');
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
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Store
$config->set('config_store_id', 0);

// Load settings
$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");

foreach ($query->rows as $result) {
	if (!$result['serialized']) {
		$config->set($result['key'], $result['value']);
	} else {
		$config->set($result['key'], unserialize($result['value']));
	}
}

echo "<h1>Registration Debug Test</h1>";
echo "<hr>";

// Test 1: Database Connection
echo "<h2>Test 1: Database Connection</h2>";
try {
	$test_query = $db->query("SELECT 1 as test");
	if ($test_query) {
		echo "✓ Database connection: <strong>SUCCESS</strong><br>";
	} else {
		echo "✗ Database connection: <strong>FAILED</strong><br>";
	}
} catch (Exception $e) {
	echo "✗ Database connection error: " . $e->getMessage() . "<br>";
}

// Test 2: Check if customer table exists
echo "<h2>Test 2: Customer Table Check</h2>";
try {
	$table_check = $db->query("SHOW TABLES LIKE '" . DB_PREFIX . "customer'");
	if ($table_check->num_rows > 0) {
		echo "✓ Customer table exists<br>";
		
		// Check table structure
		$structure = $db->query("DESCRIBE " . DB_PREFIX . "customer");
		echo "Table columns: ";
		foreach ($structure->rows as $col) {
			echo $col['Field'] . " (" . $col['Type'] . "), ";
		}
		echo "<br>";
	} else {
		echo "✗ Customer table does NOT exist!<br>";
	}
} catch (Exception $e) {
	echo "✗ Table check error: " . $e->getMessage() . "<br>";
}

// Test 3: Check customer group
echo "<h2>Test 3: Customer Group Check</h2>";
$customer_group_id = $config->get('config_customer_group_id');
echo "Default customer group ID: " . ($customer_group_id ? $customer_group_id : 'NOT SET') . "<br>";

if ($customer_group_id) {
	$group_check = $db->query("SELECT * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
	if ($group_check->num_rows > 0) {
		echo "✓ Customer group exists: " . $group_check->row['name'] . "<br>";
		echo "  Approval required: " . ($group_check->row['approval'] ? 'YES' : 'NO') . "<br>";
	} else {
		echo "✗ Customer group NOT found!<br>";
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
echo "- Email: " . $test_data['email'] . "<br>";
echo "- Firstname: " . $test_data['firstname'] . "<br>";
echo "- Telephone: " . $test_data['telephone'] . "<br>";
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
		$customer_id = $db->getLastId();
		echo "✓ Direct INSERT: <strong>SUCCESS</strong><br>";
		echo "  Customer ID: " . ($customer_id ? $customer_id : 'NO ID RETURNED') . "<br>";
		
		// Verify the insert
		$verify = $db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		if ($verify->num_rows > 0) {
			echo "✓ Verification: Customer found in database<br>";
			echo "  Email: " . $verify->row['email'] . "<br>";
			echo "  Firstname: " . $verify->row['firstname'] . "<br>";
			echo "  Approved: " . $verify->row['approved'] . "<br>";
			echo "  Status: " . $verify->row['status'] . "<br>";
			
			// Clean up test data
			$db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
			echo "<br>✓ Test customer deleted<br>";
		} else {
			echo "✗ Verification: Customer NOT found in database!<br>";
		}
	} else {
		echo "✗ Direct INSERT: <strong>FAILED</strong><br>";
		echo "  Error: " . (method_exists($db, 'getError') ? $db->getError() : 'Unknown error') . "<br>";
	}
} catch (Exception $e) {
	echo "✗ Insert error: " . $e->getMessage() . "<br>";
	echo "  File: " . $e->getFile() . "<br>";
	echo "  Line: " . $e->getLine() . "<br>";
}

// Test 5: Test using Model
echo "<h2>Test 5: Test Using Customer Model</h2>";
try {
	$loader->model('account/customer');
	$model = $registry->get('model_account_customer');
	
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
		if ($verify->num_rows > 0) {
			echo "✓ Verification: Customer found<br>";
			echo "  Email: " . $verify->row['email'] . "<br>";
			
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
	echo "✗ Model test error: " . $e->getMessage() . "<br>";
	echo "  File: " . $e->getFile() . "<br>";
	echo "  Line: " . $e->getLine() . "<br>";
}

// Test 6: Check recent registrations
echo "<h2>Test 6: Recent Customer Registrations</h2>";
try {
	$recent = $db->query("SELECT customer_id, firstname, email, telephone, approved, status, date_added FROM " . DB_PREFIX . "customer ORDER BY customer_id DESC LIMIT 5");
	if ($recent->num_rows > 0) {
		echo "Recent customers in database:<br>";
		echo "<table border='1' cellpadding='5'>";
		echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Approved</th><th>Status</th><th>Date</th></tr>";
		foreach ($recent->rows as $row) {
			echo "<tr>";
			echo "<td>" . $row['customer_id'] . "</td>";
			echo "<td>" . $row['firstname'] . "</td>";
			echo "<td>" . $row['email'] . "</td>";
			echo "<td>" . $row['telephone'] . "</td>";
			echo "<td>" . $row['approved'] . "</td>";
			echo "<td>" . $row['status'] . "</td>";
			echo "<td>" . $row['date_added'] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "No customers found in database<br>";
	}
} catch (Exception $e) {
	echo "Error checking recent customers: " . $e->getMessage() . "<br>";
}

// Test 7: Check database permissions
echo "<h2>Test 7: Database Permissions Check</h2>";
try {
	$perm_test = $db->query("SHOW GRANTS FOR CURRENT_USER()");
	if ($perm_test) {
		echo "Current user permissions:<br>";
		foreach ($perm_test->rows as $row) {
			echo htmlspecialchars($row[array_key_first($row)]) . "<br>";
		}
	}
} catch (Exception $e) {
	echo "Permission check error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h2>Debug Complete</h2>";
echo "<p>Check the results above to identify the issue.</p>";

