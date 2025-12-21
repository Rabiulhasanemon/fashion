<?php
/**
 * Registration Debug Page
 * Access: https://ruplexa1.master.com.bd/debug_registration.php
 * This page helps debug customer registration issues
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Registration Debug</title>";
echo "<style>body { font-family: Arial; margin: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow: auto; } table { border-collapse: collapse; width: 100%; margin: 10px 0; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } th { background-color: #4CAF50; color: white; }</style>";
echo "</head><body>";
echo "<h1>Customer Registration Debug Tool</h1>";

try {
    // Load OpenCart
    require_once(__DIR__ . '/catalog/config.php');
    echo "<p class='info'>✓ config.php loaded</p>";
    
    require_once(DIR_SYSTEM . 'startup.php');
    echo "<p class='info'>✓ startup.php loaded</p>";
    
    // Database connection
    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    echo "<p class='info'>✓ Database connection created</p>";
    
    $prefix = DB_PREFIX;
    
    // Test database connection
    $test_query = $db->query("SELECT 1 as test");
    if ($test_query) {
        echo "<p class='success'>✓ Database query works</p>";
    } else {
        echo "<p class='error'>❌ Database query failed</p>";
        die();
    }
    
    // Check customer table structure
    echo "<h2>Customer Table Structure</h2>";
    $structure = $db->query("DESCRIBE " . $prefix . "customer");
    if ($structure && $structure->num_rows > 0) {
        echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($structure->rows as $row) {
            echo "<tr>";
            echo "<td><strong>" . $row['Field'] . "</strong></td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . ($row['Default'] !== null ? $row['Default'] : 'NULL') . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check recent registrations
    echo "<h2>Recent Customer Registrations (Last 10)</h2>";
    $recent = $db->query("SELECT customer_id, firstname, lastname, email, telephone, date_added, status, approved FROM " . $prefix . "customer ORDER BY customer_id DESC LIMIT 10");
    if ($recent && $recent->num_rows > 0) {
        echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Date Added</th><th>Status</th><th>Approved</th></tr>";
        foreach ($recent->rows as $row) {
            echo "<tr>";
            echo "<td>" . $row['customer_id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telephone']) . "</td>";
            echo "<td>" . $row['date_added'] . "</td>";
            echo "<td>" . ($row['status'] ? 'Active' : 'Inactive') . "</td>";
            echo "<td>" . ($row['approved'] ? 'Yes' : 'No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='info'>No customers found in database.</p>";
    }
    
    // Test Registration Form
    echo "<h2>Test Registration</h2>";
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_register'])) {
        echo "<h3>Registration Attempt</h3>";
        
        $test_data = array(
            'firstname' => isset($_POST['firstname']) ? $_POST['firstname'] : '',
            'lastname' => isset($_POST['lastname']) ? $_POST['lastname'] : '',
            'email' => isset($_POST['email']) ? $_POST['email'] : '',
            'telephone' => isset($_POST['telephone']) ? $_POST['telephone'] : '',
            'password' => isset($_POST['password']) ? $_POST['password'] : '',
            'newsletter' => isset($_POST['newsletter']) ? 1 : 0
        );
        
        echo "<pre>";
        echo "Registration Data:\n";
        print_r($test_data);
        echo "</pre>";
        
        // Check if email already exists
        $email_check = $db->query("SELECT customer_id, email, firstname FROM " . $prefix . "customer WHERE email = '" . $db->escape($test_data['email']) . "' LIMIT 1");
        if ($email_check && $email_check->num_rows > 0) {
            echo "<p class='error'>❌ Email already exists: " . htmlspecialchars($test_data['email']) . " (Customer ID: " . $email_check->row['customer_id'] . ")</p>";
        } else {
            echo "<p class='success'>✓ Email is available</p>";
            
            // Try to insert directly
            $salt = substr(md5(uniqid(rand(), true)), 0, 9);
            $password_hash = sha1($salt . sha1($salt . sha1($test_data['password'])));
            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
            
            $insert_sql = "INSERT INTO " . $prefix . "customer SET 
                customer_group_id = '1',
                store_id = '0',
                firstname = '" . $db->escape($test_data['firstname']) . "',
                lastname = '" . $db->escape($test_data['lastname']) . "',
                email = '" . $db->escape($test_data['email']) . "',
                telephone = '" . $db->escape($test_data['telephone']) . "',
                salt = '" . $db->escape($salt) . "',
                password = '" . $db->escape($password_hash) . "',
                newsletter = '" . (int)$test_data['newsletter'] . "',
                ip = '" . $db->escape($ip) . "',
                status = '1',
                approved = '1',
                date_added = NOW()";
            
            echo "<pre>SQL Query:\n" . htmlspecialchars($insert_sql) . "</pre>";
            
            $insert_result = $db->query($insert_sql);
            
            if ($insert_result) {
                $new_customer_id = $db->getLastId();
                echo "<p class='success'>✓✓✓ Customer inserted successfully! Customer ID: " . $new_customer_id . "</p>";
                
                // Verify
                $verify = $db->query("SELECT * FROM " . $prefix . "customer WHERE customer_id = '" . (int)$new_customer_id . "' LIMIT 1");
                if ($verify && $verify->num_rows > 0) {
                    echo "<p class='success'>✓ Customer verified in database</p>";
                    echo "<pre>";
                    print_r($verify->row);
                    echo "</pre>";
                }
            } else {
                echo "<p class='error'>❌ Insert failed</p>";
                echo "<p>Check error logs for details.</p>";
            }
        }
    }
    
    // Registration Form
    echo "<form method='POST' style='background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 20px;'>";
    echo "<h3>Test Registration Form</h3>";
    echo "<div style='margin-bottom: 10px;'><label>First Name: <input type='text' name='firstname' required style='padding: 5px; width: 200px;'></label></div>";
    echo "<div style='margin-bottom: 10px;'><label>Last Name: <input type='text' name='lastname' style='padding: 5px; width: 200px;'></label></div>";
    echo "<div style='margin-bottom: 10px;'><label>Email: <input type='email' name='email' required style='padding: 5px; width: 200px;'></label></div>";
    echo "<div style='margin-bottom: 10px;'><label>Telephone: <input type='text' name='telephone' required style='padding: 5px; width: 200px;'></label></div>";
    echo "<div style='margin-bottom: 10px;'><label>Password: <input type='password' name='password' required style='padding: 5px; width: 200px;'></label></div>";
    echo "<div style='margin-bottom: 10px;'><label><input type='checkbox' name='newsletter'> Subscribe to newsletter</label></div>";
    echo "<button type='submit' name='test_register' style='padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;'>Test Register</button>";
    echo "</form>";
    
    // Check error logs
    echo "<h2>Recent Error Logs</h2>";
    $log_file = DIR_LOGS . 'error.log';
    if (file_exists($log_file)) {
        $log_content = file_get_contents($log_file);
        $lines = explode("\n", $log_content);
        $registration_lines = array_filter($lines, function($line) {
            return stripos($line, 'customer') !== false || stripos($line, 'register') !== false || stripos($line, 'addCustomer') !== false;
        });
        if (count($registration_lines) > 0) {
            echo "<pre style='max-height: 300px; overflow: auto;'>";
            echo implode("\n", array_slice($registration_lines, -30));
            echo "</pre>";
        } else {
            echo "<p class='info'>No registration-related errors in log.</p>";
        }
    } else {
        echo "<p class='info'>Error log file not found: " . $log_file . "</p>";
    }
    
    // Check PHP error log
    $php_log = ini_get('error_log');
    if ($php_log && file_exists($php_log)) {
        echo "<h2>PHP Error Log (addCustomer entries)</h2>";
        $php_log_content = file_get_contents($php_log);
        $php_lines = explode("\n", $php_log_content);
        $addCustomer_lines = array_filter($php_lines, function($line) {
            return stripos($line, 'addCustomer') !== false;
        });
        if (count($addCustomer_lines) > 0) {
            echo "<pre style='max-height: 300px; overflow: auto;'>";
            echo implode("\n", array_slice($addCustomer_lines, -30));
            echo "</pre>";
        }
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
?>

