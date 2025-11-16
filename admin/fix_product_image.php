<?php
/**
 * Quick Fix Script for Product Image Table
 * 
 * This script fixes common issues with the product_image table
 * Run from: admin/fix_product_image.php
 * 
 * SECURITY: Delete this file after use!
 */

// Load OpenCart
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Database
$db = new \DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "<h2>Product Image Table Fix Script</h2>";
echo "<hr>";

// Check 1: product_image_id = 0
echo "<h3>1. Checking for product_image_id = 0:</h3>";
$check_zero = $db->query("SELECT product_image_id FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0 LIMIT 1");
if ($check_zero && $check_zero->num_rows) {
    echo "<p style='color:red;'><strong>FOUND:</strong> product_image with product_image_id = 0!</p>";
    
    if (isset($_POST['fix_image_id_zero']) && $_POST['fix_image_id_zero'] == '1') {
        // First, try to delete
        $delete_result = $db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
        if ($delete_result) {
            echo "<p style='color:green;'><strong>✓ FIXED:</strong> Deleted product_image records with product_image_id = 0</p>";
            
            // Now fix AUTO_INCREMENT
            $max_pi_after = $db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image");
            $next_id_after = 1;
            if ($max_pi_after && $max_pi_after->num_rows && isset($max_pi_after->row['max_id']) && $max_pi_after->row['max_id'] !== null) {
                $next_id_after = (int)$max_pi_after->row['max_id'] + 1;
            }
            
            $fix_ai = $db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_id_after);
            if ($fix_ai) {
                echo "<p style='color:green;'><strong>✓ FIXED:</strong> AUTO_INCREMENT set to " . $next_id_after . "</p>";
            } else {
                echo "<p style='color:orange;'><strong>⚠ WARNING:</strong> Could not auto-fix AUTO_INCREMENT. Run manually: <code>ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_id_after . ";</code></p>";
            }
        } else {
            echo "<p style='color:red;'><strong>✗ ERROR:</strong> Could not delete. Try manually in phpMyAdmin:</p>";
            echo "<code>DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0;</code><br>";
            echo "<code>ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = 1;</code>";
        }
    } else {
        echo "<form method='post'><input type='hidden' name='fix_image_id_zero' value='1'>";
        echo "<button type='submit' style='background:#dc3545;color:white;padding:10px 20px;border:none;cursor:pointer;border-radius:4px;font-size:16px;'>🔧 Fix All Issues (Delete product_image_id = 0 & Fix AUTO_INCREMENT)</button></form>";
    }
} else {
    echo "<p style='color:green;'>✓ No product_image with product_image_id = 0</p>";
}

// Check 2: product_id = 0 in product_image
echo "<h3>2. Checking for product_id = 0 in product_image:</h3>";
$check_pid_zero = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_image WHERE product_id = 0");
if ($check_pid_zero && $check_pid_zero->num_rows) {
    $count = isset($check_pid_zero->row['count']) ? $check_pid_zero->row['count'] : 0;
    if ($count > 0) {
        echo "<p style='color:orange;'><strong>FOUND:</strong> " . $count . " product_image records with product_id = 0</p>";
        
        if (isset($_POST['fix_product_id_zero']) && $_POST['fix_product_id_zero'] == '1') {
            $delete_result = $db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = 0");
            if ($delete_result) {
                echo "<p style='color:green;'><strong>✓ FIXED:</strong> Deleted " . $count . " orphaned records</p>";
            } else {
                echo "<p style='color:red;'><strong>✗ ERROR:</strong> Could not delete. Try manually: <code>DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = 0;</code></p>";
            }
        } else {
            echo "<form method='post'><input type='hidden' name='fix_product_id_zero' value='1'>";
            echo "<button type='submit' style='background:#dc3545;color:white;padding:10px 20px;border:none;cursor:pointer;border-radius:4px;'>Delete product_id = 0</button></form>";
        }
    } else {
        echo "<p style='color:green;'>✓ No product_image with product_id = 0</p>";
    }
}

// Check 3: AUTO_INCREMENT
echo "<h3>3. Checking product_image AUTO_INCREMENT:</h3>";
$max_pi = $db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image");
if ($max_pi && $max_pi->num_rows) {
    $max_id = isset($max_pi->row['max_id']) ? $max_pi->row['max_id'] : 0;
    $next_id = $max_id + 1;
    echo "<p>Max product_image_id: <strong>" . $max_id . "</strong></p>";
    echo "<p>Next expected: <strong>" . $next_id . "</strong></p>";
    
    // Check AUTO_INCREMENT
    $pi_structure = $db->query("SHOW CREATE TABLE " . DB_PREFIX . "product_image");
    if ($pi_structure && $pi_structure->num_rows) {
        $create_table = isset($pi_structure->row['Create Table']) ? $pi_structure->row['Create Table'] : (isset($pi_structure->row[1]) ? $pi_structure->row[1] : '');
        if ($create_table && preg_match('/AUTO_INCREMENT=(\d+)/i', $create_table, $matches)) {
            $current_ai = $matches[1];
            echo "<p>Current AUTO_INCREMENT: <strong>" . $current_ai . "</strong></p>";
            
            if ($current_ai < $next_id) {
                echo "<p style='color:red;'><strong>⚠️ PROBLEM:</strong> AUTO_INCREMENT (" . $current_ai . ") is less than next expected (" . $next_id . ")!</p>";
                
                if (isset($_POST['fix_pi_auto_inc']) && $_POST['fix_pi_auto_inc'] == '1') {
                    $fix_result = $db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_id);
                    if ($fix_result) {
                        echo "<p style='color:green;'><strong>✓ FIXED:</strong> AUTO_INCREMENT set to " . $next_id . "</p>";
                    } else {
                        echo "<p style='color:red;'><strong>✗ ERROR:</strong> Could not fix. Try manually: <code>ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_id . ";</code></p>";
                    }
                } else {
                    echo "<form method='post'><input type='hidden' name='fix_pi_auto_inc' value='1'>";
                    echo "<button type='submit' style='background:#28a745;color:white;padding:10px 20px;border:none;cursor:pointer;border-radius:4px;'>Fix AUTO_INCREMENT</button></form>";
                }
            } else {
                echo "<p style='color:green;'>✓ AUTO_INCREMENT is correct</p>";
            }
        }
    }
}

// Check 4: Test insert
echo "<h3>4. Testing INSERT (will not actually insert):</h3>";
$test_product_id = 62; // Use a valid product_id
$test_sql = "INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$test_product_id . "', image = 'test/image.jpg', sort_order = '0'";
echo "<p>Test SQL:</p>";
echo "<pre>" . htmlspecialchars($test_sql) . "</pre>";

// Summary
echo "<hr>";
echo "<h3>Summary:</h3>";
echo "<p>After fixing any issues above, try adding a product again.</p>";
echo "<p><strong>⚠️ SECURITY:</strong> Delete this file after use!</p>";
?>

