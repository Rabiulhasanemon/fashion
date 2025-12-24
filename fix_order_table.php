<?php
// Fix script for order table - similar to fix_customer_table.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Fix Order Table Issues</h1>";

// Load OpenCart
$root = dirname(__FILE__);
if (!defined('DIR_APPLICATION')) {
    define('VERSION', '2.4.0');
    require_once($root . '/config.php');
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
    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    $registry->set('db', $db);
    
    // Settings
    $query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' ORDER BY store_id ASC");
    foreach ($query->rows as $result) {
        if (!$result['serialized']) {
            $config->set($result['key'], $result['value']);
        } else {
            $config->set($result['key'], unserialize($result['value']));
        }
    }
    
    echo "<h2>Step 1: Check for order_id = 0</h2>";
    $zero_check = $db->query("SELECT order_id, firstname, lastname, email FROM " . DB_PREFIX . "order WHERE order_id = 0 LIMIT 1");
    if ($zero_check && $zero_check->num_rows > 0) {
        $zero_order = $zero_check->row;
        echo "⚠ Found record with order_id = 0:<br>";
        echo "Email: " . htmlspecialchars($zero_order['email']) . "<br>";
        echo "Name: " . htmlspecialchars($zero_order['firstname'] . ' ' . $zero_order['lastname']) . "<br>";
        echo "Will update order_id = 0 to a new valid ID...<br>";
        
        // Get max order_id
        $max_id_query = $db->query("SELECT MAX(order_id) as max_id FROM " . DB_PREFIX . "order");
        $new_id = 1;
        if ($max_id_query && $max_id_query->num_rows > 0 && isset($max_id_query->row['max_id'])) {
            $new_id = (int)$max_id_query->row['max_id'] + 1;
        }
        
        echo "Updating order_id = 0 to order_id = " . $new_id . "...<br>";
        
        // Update order_id = 0 to new_id
        $update_result = $db->query("UPDATE " . DB_PREFIX . "order SET order_id = '" . (int)$new_id . "' WHERE order_id = 0 LIMIT 1");
        if ($update_result !== false) {
            echo "✓ Successfully updated order_id = 0 to order_id = " . $new_id . "<br>";
            
            // Also update related tables
            $db->query("UPDATE " . DB_PREFIX . "order_product SET order_id = '" . (int)$new_id . "' WHERE order_id = 0");
            $db->query("UPDATE " . DB_PREFIX . "order_total SET order_id = '" . (int)$new_id . "' WHERE order_id = 0");
            $db->query("UPDATE " . DB_PREFIX . "order_history SET order_id = '" . (int)$new_id . "' WHERE order_id = 0");
            echo "✓ Updated related tables (order_product, order_total, order_history)<br>";
        } else {
            echo "✗ Failed to update order_id = 0<br>";
        }
    } else {
        echo "✓ No records with order_id = 0 found<br>";
        // Still need to get max_id for AUTO_INCREMENT
        $max_id_query = $db->query("SELECT MAX(order_id) as max_id FROM " . DB_PREFIX . "order");
        $new_id = 1;
        if ($max_id_query && $max_id_query->num_rows > 0 && isset($max_id_query->row['max_id'])) {
            $new_id = (int)$max_id_query->row['max_id'] + 1;
        }
    }
    
    echo "<h2>Step 2: Check AUTO_INCREMENT on order_id</h2>";
    $status_check = $db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "order'");
    if ($status_check && $status_check->num_rows > 0) {
        $auto_inc = isset($status_check->row['Auto_increment']) ? $status_check->row['Auto_increment'] : null;
        echo "Current AUTO_INCREMENT value: " . ($auto_inc ? $auto_inc : 'NOT SET') . "<br>";
        
        if (!$auto_inc || $auto_inc <= 0) {
            echo "⚠ order_id column does NOT have AUTO_INCREMENT<br>";
            echo "Attempting to add AUTO_INCREMENT...<br>";
            
            // First, check the current structure
            $column_check = $db->query("SHOW COLUMNS FROM " . DB_PREFIX . "order WHERE Field = 'order_id'");
            if ($column_check && $column_check->num_rows > 0) {
                $column_info = $column_check->row;
                echo "Current order_id column type: " . $column_info['Type'] . "<br>";
                
                // Alter table to add AUTO_INCREMENT
                $alter_sql = "ALTER TABLE " . DB_PREFIX . "order MODIFY order_id INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = " . ($new_id + 1);
                echo "SQL: " . htmlspecialchars($alter_sql) . "<br>";
                
                $alter_result = $db->query($alter_sql);
                if ($alter_result !== false) {
                    echo "✓ Successfully added AUTO_INCREMENT to order_id column<br>";
                    echo "Next AUTO_INCREMENT value set to: " . ($new_id + 1) . "<br>";
                } else {
                    echo "✗ Failed to add AUTO_INCREMENT<br>";
                    if (method_exists($db->link, 'error')) {
                        echo "MySQL Error: " . htmlspecialchars($db->link->error) . "<br>";
                    }
                }
            }
        } else {
            echo "✓ AUTO_INCREMENT is already set<br>";
        }
    }
    
    echo "<h2>Step 3: Verify AUTO_INCREMENT Status</h2>";
    $status_check = $db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "order'");
    if ($status_check && $status_check->num_rows > 0) {
        $auto_inc = isset($status_check->row['Auto_increment']) ? $status_check->row['Auto_increment'] : null;
        echo "AUTO_INCREMENT value: " . ($auto_inc ? $auto_inc : 'NOT SET') . "<br>";
        
        if ($auto_inc && $auto_inc > 0) {
            echo "✓ AUTO_INCREMENT is properly configured<br>";
        } else {
            echo "✗ AUTO_INCREMENT is still not set correctly<br>";
        }
    }
    
    echo "<h2>Step 4: Test Insert</h2>";
    // Try a test insert to verify it works
    $test_email = 'test_fix_' . time() . '@example.com';
    $test_insert = "INSERT INTO " . DB_PREFIX . "order SET 
        invoice_prefix = 'INV-',
        store_id = '0',
        store_name = 'Test Store',
        store_url = '" . HTTP_SERVER . "',
        customer_id = '0',
        customer_group_id = '1',
        firstname = 'Test',
        lastname = 'Fix',
        email = '" . $db->escape($test_email) . "',
        telephone = '01712345678',
        fax = '',
        custom_field = '',
        payment_firstname = 'Test',
        payment_lastname = 'Fix',
        payment_company = '',
        payment_address_1 = 'Test Address',
        payment_address_2 = '',
        payment_city = 'Dhaka',
        payment_postcode = '1200',
        payment_country = 'Bangladesh',
        payment_country_id = '19',
        payment_zone = 'Dhaka',
        payment_zone_id = '271',
        payment_region = '',
        payment_region_id = '0',
        payment_address_format = '',
        payment_custom_field = '',
        payment_method = 'Cash On Delivery',
        payment_code = 'cod',
        shipping_firstname = 'Test',
        shipping_lastname = 'Fix',
        shipping_company = '',
        shipping_address_1 = 'Test Address',
        shipping_address_2 = '',
        shipping_city = 'Dhaka',
        shipping_postcode = '1200',
        shipping_country = 'Bangladesh',
        shipping_country_id = '19',
        shipping_zone = 'Dhaka',
        shipping_zone_id = '271',
        shipping_region = '',
        shipping_region_id = '0',
        shipping_address_format = '',
        shipping_custom_field = '',
        shipping_method = 'Flat Rate',
        shipping_code = 'flat.flat',
        comment = 'Test order for fix verification',
        total = '100.00',
        order_status_id = '1',
        affiliate_id = '0',
        commission = '0',
        marketing_id = '0',
        tracking = '',
        language_id = '1',
        currency_id = '1',
        currency_code = 'BDT',
        currency_value = '1.0000',
        ip = '127.0.0.1',
        forwarded_ip = '',
        user_agent = 'Test',
        accept_language = 'en',
        date_added = NOW(),
        date_modified = NOW()";
    
    $test_result = $db->query($test_insert);
    if ($test_result !== false) {
        $test_order_id = $db->getLastId();
        echo "✓ Test insert successful! Order ID: " . $test_order_id . "<br>";
        
        // Verify order exists
        $verify = $db->query("SELECT * FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$test_order_id . "'");
        if ($verify && $verify->num_rows > 0) {
            echo "✓ Verification: Order found in database<br>";
            
            // Delete test order
            $db->query("DELETE FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$test_order_id . "'");
            echo "✓ Test order deleted<br>";
        } else {
            echo "✗ Verification failed: Order not found in database<br>";
        }
    } else {
        echo "✗ Test insert failed!<br>";
        if (method_exists($db->link, 'error')) {
            echo "MySQL Error: " . htmlspecialchars($db->link->error) . "<br>";
        }
    }
    
    echo "<h2>Summary</h2>";
    echo "If all steps completed successfully, order creation should now work!<br>";
    echo "<a href='debug_order_creation.php'>Run Debug Test Again</a>";
    
} else {
    echo "OpenCart already loaded. Please access this script directly.";
}



