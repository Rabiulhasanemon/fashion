<?php
// Script to fix customer_id = 0 issue and AUTO_INCREMENT
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
echo "<!DOCTYPE html><html><head><title>Fix Customer Table</title><style>body{font-family:Arial,sans-serif;margin:20px;}table{border-collapse:collapse;width:100%;}th,td{border:1px solid #ddd;padding:8px;text-align:left;}th{background-color:#f2f2f2;}.success{color:green;}.error{color:red;}.warning{color:orange;}</style></head><body>";
echo "<h1>Fix Customer Table Issues</h1>";
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
	
	echo "<h2>Step 1: Check for customer_id = 0</h2>";
	$zero_check = $db->query("SELECT customer_id, email, firstname FROM " . DB_PREFIX . "customer WHERE customer_id = 0 LIMIT 1");
	
	if ($zero_check && $zero_check->num_rows > 0) {
		echo "<p class='warning'>⚠ Found record with customer_id = 0:</p>";
		echo "<ul>";
		echo "<li>Email: " . htmlspecialchars($zero_check->row['email']) . "</li>";
		echo "<li>Name: " . htmlspecialchars($zero_check->row['firstname']) . "</li>";
		echo "</ul>";
		
		// Get the highest customer_id
		$max_query = $db->query("SELECT MAX(customer_id) as max_id FROM " . DB_PREFIX . "customer");
		$new_id = 1;
		if ($max_query && $max_query->num_rows > 0 && isset($max_query->row['max_id'])) {
			$new_id = (int)$max_query->row['max_id'] + 1;
		}
		
		echo "<p>Will update customer_id = 0 to customer_id = " . $new_id . "</p>";
		
		// Update the record
		$update_result = $db->query("UPDATE " . DB_PREFIX . "customer SET customer_id = '" . (int)$new_id . "' WHERE customer_id = 0 LIMIT 1");
		
		if ($update_result !== false) {
			echo "<p class='success'>✓ Successfully updated customer_id = 0 to customer_id = " . $new_id . "</p>";
		} else {
			echo "<p class='error'>✗ Failed to update customer_id = 0</p>";
		}
	} else {
		echo "<p class='success'>✓ No records with customer_id = 0 found</p>";
	}
	
	echo "<hr>";
	
	echo "<h2>Step 2: Check AUTO_INCREMENT on customer_id</h2>";
	
	// Check current table structure
	$structure = $db->query("SHOW COLUMNS FROM " . DB_PREFIX . "customer WHERE Field = 'customer_id'");
	if ($structure && $structure->num_rows > 0) {
		$col = $structure->row;
		$has_auto_increment = (isset($col['Extra']) && strpos($col['Extra'], 'auto_increment') !== false);
		
		if ($has_auto_increment) {
			echo "<p class='success'>✓ customer_id column has AUTO_INCREMENT</p>";
		} else {
			echo "<p class='warning'>⚠ customer_id column does NOT have AUTO_INCREMENT</p>";
			echo "<p>Attempting to add AUTO_INCREMENT...</p>";
			
			// Get current max ID to set AUTO_INCREMENT correctly
			$max_query = $db->query("SELECT MAX(customer_id) as max_id FROM " . DB_PREFIX . "customer");
			$next_id = 1;
			if ($max_query && $max_query->num_rows > 0 && isset($max_query->row['max_id'])) {
				$next_id = (int)$max_query->row['max_id'] + 1;
			}
			
			// Add AUTO_INCREMENT
			$alter_sql = "ALTER TABLE " . DB_PREFIX . "customer MODIFY customer_id INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = " . $next_id;
			echo "<p>SQL: <code>" . htmlspecialchars($alter_sql) . "</code></p>";
			
			$alter_result = $db->query($alter_sql);
			
			if ($alter_result !== false) {
				echo "<p class='success'>✓ Successfully added AUTO_INCREMENT to customer_id column</p>";
				echo "<p>Next AUTO_INCREMENT value set to: " . $next_id . "</p>";
			} else {
				echo "<p class='error'>✗ Failed to add AUTO_INCREMENT. You may need to run this SQL manually:</p>";
				echo "<pre>" . htmlspecialchars($alter_sql) . "</pre>";
			}
		}
	}
	
	echo "<hr>";
	
	echo "<h2>Step 3: Verify AUTO_INCREMENT Status</h2>";
	$status = $db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "customer'");
	if ($status && $status->num_rows > 0) {
		$auto_inc = isset($status->row['Auto_increment']) ? $status->row['Auto_increment'] : 'NULL';
		echo "<p>AUTO_INCREMENT value: <strong>" . $auto_inc . "</strong></p>";
		
		if ($auto_inc && $auto_inc > 0) {
			echo "<p class='success'>✓ AUTO_INCREMENT is properly configured</p>";
		} else {
			echo "<p class='error'>✗ AUTO_INCREMENT is not set correctly</p>";
		}
	}
	
	echo "<hr>";
	
	echo "<h2>Step 4: Test Insert</h2>";
	$test_email = 'test_fix_' . time() . '@example.com';
	$test_sql = "INSERT INTO " . DB_PREFIX . "customer SET 
		customer_group_id = '1', 
		store_id = '0', 
		firstname = 'Test Fix', 
		lastname = 'User', 
		email = '" . $db->escape($test_email) . "', 
		telephone = '01712345678', 
		salt = 'test12345', 
		password = 'testpassword', 
		newsletter = '0', 
		ip = '127.0.0.1', 
		status = '1', 
		approved = '1', 
		date_added = NOW()";
	
	$test_result = $db->query($test_sql);
	
	if ($test_result !== false) {
		$test_id = $db->getLastId();
		if ($test_id && $test_id > 0) {
			echo "<p class='success'>✓ Test insert successful! Customer ID: " . $test_id . "</p>";
			
			// Verify
			$verify = $db->query("SELECT customer_id, email FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$test_id . "'");
			if ($verify && $verify->num_rows > 0) {
				echo "<p class='success'>✓ Verification: Customer found in database</p>";
				
				// Clean up
				$db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$test_id . "'");
				echo "<p>✓ Test customer deleted</p>";
			}
		} else {
			// Try to find by email
			$find = $db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $db->escape($test_email) . "' LIMIT 1");
			if ($find && $find->num_rows > 0) {
				$test_id = (int)$find->row['customer_id'];
				echo "<p class='success'>✓ Test insert successful! Customer ID (from query): " . $test_id . "</p>";
				echo "<p class='warning'>⚠ Note: getLastId() returned 0, but customer was inserted successfully</p>";
				
				// Clean up
				$db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$test_id . "'");
				echo "<p>✓ Test customer deleted</p>";
			} else {
				echo "<p class='error'>✗ Test insert failed - customer not found</p>";
			}
		}
	} else {
		echo "<p class='error'>✗ Test insert failed</p>";
	}
	
	echo "<hr>";
	echo "<h2>Summary</h2>";
	echo "<p>If all steps completed successfully, registration should now work!</p>";
	echo "<p><a href='debug_registration_test.php'>Run Debug Test Again</a></p>";
	
} catch (Exception $e) {
	echo "<h2 class='error'>Error</h2>";
	echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
	echo "<p>File: " . htmlspecialchars($e->getFile()) . "</p>";
	echo "<p>Line: " . $e->getLine() . "</p>";
}

echo "</body></html>";
ob_end_flush();



