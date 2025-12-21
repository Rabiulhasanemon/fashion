<?php
/**
 * Fix Customer ID Zero Issue
 * Access: https://ruplexa1.master.com.bd/fix_customer_id_zero.php
 * This script fixes the customer_id = 0 issue that prevents new registrations
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Fix Customer ID Zero</title>";
echo "<style>body { font-family: Arial; margin: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow: auto; }</style>";
echo "</head><body>";
echo "<h1>Fix Customer ID Zero Issue</h1>";

try {
    // Load OpenCart
    require_once(__DIR__ . '/config.php');
    echo "<p class='info'>✓ config.php loaded</p>";
    
    require_once(DIR_SYSTEM . 'startup.php');
    echo "<p class='info'>✓ startup.php loaded</p>";
    
    // Database connection
    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    echo "<p class='info'>✓ Database connection created</p>";
    
    $prefix = DB_PREFIX;
    
    // Check for customer_id = 0
    echo "<h2>Checking for Customer ID Zero</h2>";
    $zero_check = $db->query("SELECT customer_id, firstname, lastname, email FROM " . $prefix . "customer WHERE customer_id = '0' LIMIT 1");
    
    if ($zero_check && $zero_check->num_rows > 0) {
        echo "<p class='error'>❌ Found customer with ID 0:</p>";
        echo "<pre>";
        print_r($zero_check->row);
        echo "</pre>";
        
        // Get max customer_id
        $max_check = $db->query("SELECT MAX(customer_id) as max_id FROM " . $prefix . "customer");
        $max_id = $max_check && $max_check->num_rows > 0 ? (int)$max_check->row['max_id'] : 0;
        $new_id = $max_id + 1;
        
        echo "<p class='info'>Current max customer_id: " . $max_id . "</p>";
        echo "<p class='info'>Will assign new ID: " . $new_id . "</p>";
        
        if (isset($_GET['fix']) && $_GET['fix'] == '1') {
            echo "<h3>Fixing Customer ID Zero...</h3>";
            
            // Update customer_id from 0 to new_id
            $update_sql = "UPDATE " . $prefix . "customer SET customer_id = '" . (int)$new_id . "' WHERE customer_id = '0' LIMIT 1";
            $update_result = $db->query($update_sql);
            
            if ($update_result) {
                echo "<p class='success'>✓ Customer ID updated from 0 to " . $new_id . "</p>";
                
                // Fix AUTO_INCREMENT
                $auto_increment = $new_id + 1;
                $fix_auto_sql = "ALTER TABLE " . $prefix . "customer AUTO_INCREMENT = " . $auto_increment;
                $fix_auto_result = $db->query($fix_auto_sql);
                
                if ($fix_auto_result) {
                    echo "<p class='success'>✓✓✓ AUTO_INCREMENT set to " . $auto_increment . "</p>";
                    echo "<p class='success' style='font-size: 18px; margin-top: 20px;'>🎉 Customer ID Zero issue fixed! Registration should work now.</p>";
                } else {
                    echo "<p class='error'>❌ Failed to fix AUTO_INCREMENT</p>";
                }
                
                // Verify
                $verify = $db->query("SELECT customer_id FROM " . $prefix . "customer WHERE customer_id = '0' LIMIT 1");
                if ($verify && $verify->num_rows == 0) {
                    echo "<p class='success'>✓ Verified: No customer with ID 0 exists</p>";
                } else {
                    echo "<p class='error'>❌ Warning: Customer with ID 0 still exists</p>";
                }
            } else {
                echo "<p class='error'>❌ Failed to update customer ID</p>";
            }
        } else {
            echo "<p><a href='?fix=1' style='background: #4CAF50; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 16px; font-weight: bold;'>🔧 Fix Customer ID Zero Now</a></p>";
        }
    } else {
        echo "<p class='success'>✓ No customer with ID 0 found</p>";
        
        // Check AUTO_INCREMENT anyway
        $auto_check = $db->query("SHOW TABLE STATUS LIKE '" . $prefix . "customer'");
        if ($auto_check && $auto_check->num_rows > 0) {
            $auto_increment = isset($auto_check->row['Auto_increment']) ? $auto_check->row['Auto_increment'] : 'N/A';
            echo "<p class='info'>Current AUTO_INCREMENT value: " . $auto_increment . "</p>";
            
            // Get max customer_id
            $max_check = $db->query("SELECT MAX(customer_id) as max_id FROM " . $prefix . "customer");
            $max_id = $max_check && $max_check->num_rows > 0 ? (int)$max_check->row['max_id'] : 0;
            
            if ($auto_increment != 'N/A' && (int)$auto_increment <= $max_id) {
                echo "<p class='error'>⚠ AUTO_INCREMENT (" . $auto_increment . ") is less than or equal to max customer_id (" . $max_id . ")</p>";
                if (isset($_GET['fix_auto']) && $_GET['fix_auto'] == '1') {
                    $new_auto = $max_id + 1;
                    $fix_auto_sql = "ALTER TABLE " . $prefix . "customer AUTO_INCREMENT = " . $new_auto;
                    $fix_auto_result = $db->query($fix_auto_sql);
                    if ($fix_auto_result) {
                        echo "<p class='success'>✓✓✓ AUTO_INCREMENT fixed to " . $new_auto . "</p>";
                    } else {
                        echo "<p class='error'>❌ Failed to fix AUTO_INCREMENT</p>";
                    }
                } else {
                    echo "<p><a href='?fix_auto=1' style='background: #FF6A00; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Fix AUTO_INCREMENT</a></p>";
                }
            }
        }
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
?>

