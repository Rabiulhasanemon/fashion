<?php
// Test login functionality
// Access: https://ruplexa1.master.com.bd/admin/test_login.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Login Test</h2>";
echo "<pre>";

require_once(__DIR__ . '/config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], unserialize($setting['value']));
	}
}

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Session
$session = new Session();
$registry->set('session', $session);

// User
$user = new User($registry);
$registry->set('user', $user);

echo "=== Testing User Login ===\n\n";

// Test username and password (replace with your actual credentials)
$test_username = isset($_GET['username']) ? $_GET['username'] : '';
$test_password = isset($_GET['password']) ? $_GET['password'] : '';

if ($test_username && $test_password) {
    echo "Attempting login with username: " . htmlspecialchars($test_username) . "\n";
    
    // Check if user exists in database
    $user_query = $db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $db->escape($test_username) . "'");
    
    if ($user_query->num_rows) {
        echo "✓ User found in database\n";
        $user_data = $user_query->row;
        echo "  - User ID: " . $user_data['user_id'] . "\n";
        echo "  - Status: " . $user_data['status'] . "\n";
        
        // Try to login
        $login_result = $user->login($test_username, $test_password);
        
        if ($login_result) {
            echo "✓ Login successful!\n";
            echo "  - User ID: " . $user->getId() . "\n";
            echo "  - Username: " . $user->getUserName() . "\n";
            
            // Generate token
            $token = md5(mt_rand());
            $session->data['token'] = $token;
            echo "\n✓ Token generated: " . $token . "\n";
            echo "\n=== Dashboard URL ===\n";
            echo "https://ruplexa1.master.com.bd/admin/index.php?route=common/dashboard&token=" . $token . "\n";
        } else {
            echo "✗ Login failed - Invalid password\n";
            echo "  (Password hash in DB: " . substr($user_data['password'], 0, 20) . "...)\n";
        }
    } else {
        echo "✗ User not found in database\n";
    }
} else {
    echo "Usage: Add ?username=YOUR_USERNAME&password=YOUR_PASSWORD to URL\n";
    echo "Example: test_login.php?username=admin&password=yourpass\n\n";
    
    // List all users (without passwords)
    echo "=== Users in Database ===\n";
    $users_query = $db->query("SELECT user_id, username, status FROM " . DB_PREFIX . "user");
    if ($users_query->num_rows) {
        foreach ($users_query->rows as $u) {
            echo "  - ID: " . $u['user_id'] . ", Username: " . $u['username'] . ", Status: " . $u['status'] . "\n";
        }
    } else {
        echo "  No users found!\n";
    }
}

echo "</pre>";
?>

