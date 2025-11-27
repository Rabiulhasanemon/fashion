<?php
// Debug script for manufacturer deletion issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// OpenCart bootstrap
require_once('../config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Log file
$log_file = DIR_LOGS . 'manufacturer_delete_debug.log';
file_put_contents($log_file, date('Y-m-d H:i:s') . " ========== MANUFACTURER DELETE DEBUG ==========\n", FILE_APPEND);

echo "<h1>Manufacturer Delete Debug</h1>";
echo "<pre>";

// Get all manufacturers
$manufacturers = $db->query("SELECT manufacturer_id, name FROM " . DB_PREFIX . "manufacturer ORDER BY manufacturer_id");
echo "Total manufacturers: " . $manufacturers->num_rows . "\n\n";

// Check for products linked to manufacturers
$products_check = $db->query("SELECT manufacturer_id, COUNT(*) as product_count FROM " . DB_PREFIX . "product WHERE manufacturer_id > 0 GROUP BY manufacturer_id");
echo "Manufacturers with products:\n";
if ($products_check->num_rows > 0) {
    foreach ($products_check->rows as $row) {
        echo "  - Manufacturer ID {$row['manufacturer_id']}: {$row['product_count']} products\n";
    }
} else {
    echo "  - No manufacturers have products\n";
}
echo "\n";

// Check for foreign key constraints
echo "Checking for foreign key constraints...\n";
$fk_check = $db->query("SELECT 
    CONSTRAINT_NAME, 
    TABLE_NAME, 
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE 
WHERE REFERENCED_TABLE_SCHEMA = '" . DB_DATABASE . "' 
AND REFERENCED_TABLE_NAME = '" . DB_PREFIX . "manufacturer'
AND REFERENCED_COLUMN_NAME = 'manufacturer_id'");

if ($fk_check->num_rows > 0) {
    echo "Foreign key constraints found:\n";
    foreach ($fk_check->rows as $row) {
        echo "  - Table: {$row['TABLE_NAME']}, Constraint: {$row['CONSTRAINT_NAME']}\n";
    }
} else {
    echo "  - No foreign key constraints found\n";
}
echo "\n";

// Check for orphaned records
echo "Checking for orphaned records...\n";
$orphaned_desc = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "manufacturer_description md LEFT JOIN " . DB_PREFIX . "manufacturer m ON md.manufacturer_id = m.manufacturer_id WHERE m.manufacturer_id IS NULL");
echo "  - Orphaned manufacturer_description records: " . ($orphaned_desc->row['count'] ?? 0) . "\n";

$orphaned_store = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "manufacturer_to_store mts LEFT JOIN " . DB_PREFIX . "manufacturer m ON mts.manufacturer_id = m.manufacturer_id WHERE m.manufacturer_id IS NULL");
echo "  - Orphaned manufacturer_to_store records: " . ($orphaned_store->row['count'] ?? 0) . "\n";

$orphaned_layout = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "manufacturer_to_layout mtl LEFT JOIN " . DB_PREFIX . "manufacturer m ON mtl.manufacturer_id = m.manufacturer_id WHERE m.manufacturer_id IS NULL");
echo "  - Orphaned manufacturer_to_layout records: " . ($orphaned_layout->row['count'] ?? 0) . "\n";

$orphaned_url = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'manufacturer_id=%' AND query NOT IN (SELECT CONCAT('manufacturer_id=', manufacturer_id) FROM " . DB_PREFIX . "manufacturer)");
echo "  - Orphaned url_alias records: " . ($orphaned_url->row['count'] ?? 0) . "\n";
echo "\n";

// Test deletion on a specific manufacturer (if provided)
if (isset($_GET['test_id'])) {
    $test_id = (int)$_GET['test_id'];
    $do_delete = isset($_GET['delete']) && $_GET['delete'] == '1';
    
    echo "Testing deletion of manufacturer ID: {$test_id}\n";
    echo str_repeat("=", 50) . "\n";
    
    // Check if manufacturer exists
    $check = $db->query("SELECT manufacturer_id, name FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = {$test_id}");
    if (!$check->num_rows) {
        echo "ERROR: Manufacturer ID {$test_id} does not exist!\n";
    } else {
        echo "Manufacturer found: {$check->row['name']}\n\n";
        
        // Check related records
        $desc_count = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = {$test_id}");
        echo "  - manufacturer_description records: " . ($desc_count->row['count'] ?? 0) . "\n";
        
        $store_count = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = {$test_id}");
        echo "  - manufacturer_to_store records: " . ($store_count->row['count'] ?? 0) . "\n";
        
        $layout_count = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = {$test_id}");
        echo "  - manufacturer_to_layout records: " . ($layout_count->row['count'] ?? 0) . "\n";
        
        $url_count = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id={$test_id}'");
        echo "  - url_alias records: " . ($url_count->row['count'] ?? 0) . "\n";
        
        $product_count = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product WHERE manufacturer_id = {$test_id}");
        echo "  - product records: " . ($product_count->row['count'] ?? 0) . "\n";
        
        if ($product_count->row['count'] > 0) {
            echo "\nWARNING: This manufacturer has {$product_count->row['count']} products linked to it!\n";
            echo "Products will be unlinked (manufacturer_id set to 0) before deletion.\n";
        }
        
        if ($do_delete) {
            echo "\nAttempting deletion...\n";
            
            // Try deletion using the same logic as the model
            try {
                // CRITICAL: Unlink products first
                if ($product_count->row['count'] > 0) {
                    $unlink_result = $db->query("UPDATE " . DB_PREFIX . "product SET manufacturer_id = 0 WHERE manufacturer_id = {$test_id}");
                    echo "  ✓ Unlinked {$product_count->row['count']} products (set manufacturer_id to 0)\n";
                }
                
                // Delete related records first
                $db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id={$test_id}'");
                echo "  ✓ Deleted url_alias\n";
                
                $db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = {$test_id}");
                echo "  ✓ Deleted manufacturer_to_layout\n";
                
                $db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = {$test_id}");
                echo "  ✓ Deleted manufacturer_to_store\n";
                
                $db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = {$test_id}");
                echo "  ✓ Deleted manufacturer_description\n";
                
                // Finally delete main record
                $result = $db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = {$test_id}");
                if ($result) {
                    echo "  ✓ Deleted main manufacturer record\n";
                    echo "\nSUCCESS: Manufacturer deleted successfully!\n";
                    
                    // Verify deletion
                    $verify = $db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = {$test_id}");
                    if (!$verify->num_rows) {
                        echo "  ✓ Verified: Manufacturer no longer exists in database\n";
                    }
                } else {
                    // Get error
                    $error = '';
                    if (method_exists($db, 'link') && is_object($db->link)) {
                        $error = $db->link->error;
                    }
                    echo "\nERROR: Failed to delete main record. Error: {$error}\n";
                }
            } catch (Exception $e) {
                echo "\nEXCEPTION: " . $e->getMessage() . "\n";
            }
        } else {
            echo "\nTo actually delete this manufacturer, add &delete=1 to the URL\n";
            echo "Example: ?test_id={$test_id}&delete=1\n";
        }
    }
    echo "\n";
}

// List all manufacturers with details
echo "\nAll Manufacturers:\n";
echo str_repeat("=", 80) . "\n";
printf("%-5s %-30s %-10s %-10s\n", "ID", "Name", "Products", "Can Delete");
echo str_repeat("-", 80) . "\n";

foreach ($manufacturers->rows as $mfg) {
    $mfg_id = $mfg['manufacturer_id'];
    $name = $mfg['name'];
    
    $prod_count = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product WHERE manufacturer_id = {$mfg_id}");
    $has_products = ($prod_count->row['count'] ?? 0) > 0;
    
    $can_delete = !$has_products ? "YES" : "NO (has products)";
    
    printf("%-5s %-30s %-10s %-10s\n", $mfg_id, substr($name, 0, 30), $prod_count->row['count'] ?? 0, $can_delete);
}

echo "\n";
echo "To test deletion of a specific manufacturer:\n";
echo "  - View details: ?test_id=X\n";
echo "  - Actually delete: ?test_id=X&delete=1\n";
echo "  - Example: ?test_id=2&delete=1\n";
echo "\n";
echo "Note: Manufacturers with products will have their products unlinked (manufacturer_id set to 0)\n";
echo "      before the manufacturer is deleted.\n";
echo "</pre>";

file_put_contents($log_file, date('Y-m-d H:i:s') . " Debug completed\n", FILE_APPEND);

