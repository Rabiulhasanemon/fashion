<?php
// Direct test script for manufacturer deletion
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

echo "<h1>Direct Manufacturer Deletion Test</h1>";
echo "<pre>";

$prefix = DB_PREFIX;

// Get manufacturer ID from URL
$test_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$confirm = isset($_GET['confirm']) && $_GET['confirm'] == '1';

if ($test_id <= 0) {
    echo "Usage: ?id=X (where X is the manufacturer_id to delete)\n";
    echo "       ?id=X&confirm=1 (to actually delete)\n";
    echo "\nAvailable manufacturers:\n";
    $manufacturers = $db->query("SELECT manufacturer_id, name FROM {$prefix}manufacturer ORDER BY manufacturer_id LIMIT 20");
    foreach ($manufacturers->rows as $mfg) {
        $prod_check = $db->query("SELECT COUNT(*) as count FROM {$prefix}product WHERE manufacturer_id = {$mfg['manufacturer_id']}");
        $has_products = ($prod_check->row['count'] ?? 0) > 0;
        $prod_text = $has_products ? " (has products)" : "";
        echo "  ID {$mfg['manufacturer_id']}: {$mfg['name']}{$prod_text}\n";
        echo "    <a href='?id={$mfg['manufacturer_id']}'>[View Details]</a> ";
        if (!$has_products) {
            echo "<a href='?id={$mfg['manufacturer_id']}&confirm=1' style='color:red;'>[DELETE]</a>";
        }
        echo "\n";
    }
    echo "</pre>";
    exit;
}

echo "Testing deletion of manufacturer ID: {$test_id}\n";
echo str_repeat("=", 60) . "\n\n";

// Check if exists
$check = $db->query("SELECT manufacturer_id, name FROM {$prefix}manufacturer WHERE manufacturer_id = {$test_id}");
if (!$check->num_rows) {
    // Check if it was just deleted
    $related_check = $db->query("SELECT COUNT(*) as count FROM {$prefix}manufacturer_description WHERE manufacturer_id = {$test_id}");
    $related_count = $related_check->row['count'] ?? 0;
    
    if ($related_count > 0) {
        echo "WARNING: Manufacturer ID {$test_id} not found in main table, but related records still exist!\n";
        echo "This suggests the main record was deleted but related records remain.\n";
        echo "Cleaning up related records...\n\n";
    } else {
        echo "INFO: Manufacturer ID {$test_id} does not exist (may have been deleted already).\n";
        echo "Checking for any orphaned related records...\n\n";
        
        // Clean up any orphaned records
        $tables_to_clean = array(
            'url_alias' => "DELETE FROM {$prefix}url_alias WHERE query = 'manufacturer_id={$test_id}'",
            'manufacturer_to_layout' => "DELETE FROM {$prefix}manufacturer_to_layout WHERE manufacturer_id = {$test_id}",
            'manufacturer_to_store' => "DELETE FROM {$prefix}manufacturer_to_store WHERE manufacturer_id = {$test_id}",
            'manufacturer_description' => "DELETE FROM {$prefix}manufacturer_description WHERE manufacturer_id = {$test_id}"
        );
        
        foreach ($tables_to_clean as $table => $sql) {
            $result = $link->query($sql);
            $affected = $link->affected_rows;
            if ($affected > 0) {
                echo "  Cleaned up {$table}: {$affected} record(s)\n";
            }
        }
        
        echo "\n✓ Cleanup complete. Manufacturer ID {$test_id} is fully deleted.\n";
        echo "</pre>";
        exit;
    }
} else {
    echo "Manufacturer found: {$check->row['name']}\n\n";
}

// Get direct MySQLi link
$reflection = new ReflectionClass($db);
$db_property = $reflection->getProperty('db');
$db_property->setAccessible(true);
$db_driver = $db_property->getValue($db);
$link_reflection = new ReflectionProperty($db_driver, 'link');
$link_reflection->setAccessible(true);
$link = $link_reflection->getValue($db_driver);

if (!is_object($link)) {
    echo "ERROR: Could not get MySQLi link!\n";
    echo "</pre>";
    exit;
}

echo "Using direct MySQLi connection\n\n";

// Step 1: Unlink products (only if manufacturer exists or if confirm is set)
if ($check->num_rows || $confirm) {
    $product_count = $db->query("SELECT COUNT(*) as count FROM {$prefix}product WHERE manufacturer_id = {$test_id}");
    $prod_count = $product_count->row['count'] ?? 0;
    if ($prod_count > 0) {
        if ($confirm) {
            echo "Step 1: Unlinking {$prod_count} products...\n";
            $unlink_sql = "UPDATE {$prefix}product SET manufacturer_id = 0 WHERE manufacturer_id = {$test_id}";
            echo "SQL: {$unlink_sql}\n";
            $unlink_result = $link->query($unlink_sql);
            $unlink_affected = $link->affected_rows;
            echo "Result: " . ($unlink_result ? "SUCCESS" : "FAILED") . ", Affected rows: {$unlink_affected}\n";
            if ($link->error) {
                echo "MySQL Error: {$link->error} (Code: {$link->errno})\n";
            }
        } else {
            echo "Step 1: Preview - {$prod_count} products would be unlinked\n";
        }
        echo "\n";
    } else {
        echo "Step 1: No products linked to this manufacturer\n\n";
    }
}

// Step 2: Delete related records
if ($confirm) {
    echo "Step 2: Deleting related records...\n";
    
    $tables = array(
        'url_alias' => "DELETE FROM {$prefix}url_alias WHERE query = 'manufacturer_id={$test_id}'",
        'manufacturer_to_layout' => "DELETE FROM {$prefix}manufacturer_to_layout WHERE manufacturer_id = {$test_id}",
        'manufacturer_to_store' => "DELETE FROM {$prefix}manufacturer_to_store WHERE manufacturer_id = {$test_id}",
        'manufacturer_description' => "DELETE FROM {$prefix}manufacturer_description WHERE manufacturer_id = {$test_id}"
    );
    
    foreach ($tables as $table => $sql) {
        echo "  Deleting from {$table}...\n";
        echo "  SQL: {$sql}\n";
        $result = $link->query($sql);
        $affected = $link->affected_rows;
        echo "  Result: " . ($result ? "SUCCESS" : "FAILED") . ", Affected rows: {$affected}\n";
        if ($link->error) {
            echo "  MySQL Error: {$link->error} (Code: {$link->errno})\n";
        }
        echo "\n";
    }
} else {
    echo "Step 2: Preview - Related records that would be deleted...\n";
    
    $tables_preview = array(
        'url_alias' => "SELECT COUNT(*) as count FROM {$prefix}url_alias WHERE query = 'manufacturer_id={$test_id}'",
        'manufacturer_to_layout' => "SELECT COUNT(*) as count FROM {$prefix}manufacturer_to_layout WHERE manufacturer_id = {$test_id}",
        'manufacturer_to_store' => "SELECT COUNT(*) as count FROM {$prefix}manufacturer_to_store WHERE manufacturer_id = {$test_id}",
        'manufacturer_description' => "SELECT COUNT(*) as count FROM {$prefix}manufacturer_description WHERE manufacturer_id = {$test_id}"
    );
    
    foreach ($tables_preview as $table => $check_sql) {
        $check_result = $db->query($check_sql);
        $count = $check_result->row['count'] ?? 0;
        echo "  {$table}: {$count} record(s) would be deleted\n";
    }
    echo "\n";
}

// Step 3: Delete main record
if (!$confirm) {
    echo "Step 3: Preview (not deleting yet)\n";
    echo "To actually delete, add &confirm=1 to the URL\n";
    echo "Example: ?id={$test_id}&confirm=1\n\n";
} else {
    echo "Step 3: Deleting main manufacturer record...\n";
    $delete_sql = "DELETE FROM {$prefix}manufacturer WHERE manufacturer_id = {$test_id}";
    echo "SQL: {$delete_sql}\n";

    // Check for foreign key constraints first
    echo "Checking for foreign key constraints...\n";
    $fk_check = $db->query("SELECT 
        CONSTRAINT_NAME, 
        TABLE_NAME, 
        REFERENCED_TABLE_NAME,
        REFERENCED_COLUMN_NAME
    FROM information_schema.KEY_COLUMN_USAGE 
    WHERE REFERENCED_TABLE_SCHEMA = '" . DB_DATABASE . "' 
    AND REFERENCED_TABLE_NAME = '{$prefix}manufacturer'
    AND REFERENCED_COLUMN_NAME = 'manufacturer_id'");

    if ($fk_check->num_rows > 0) {
        echo "WARNING: Foreign key constraints found:\n";
        foreach ($fk_check->rows as $fk) {
            echo "  - Table: {$fk['TABLE_NAME']}, Constraint: {$fk['CONSTRAINT_NAME']}\n";
        }
        echo "Disabling foreign key checks temporarily...\n\n";
    }

    // Try with foreign key checks disabled
    echo "Attempting deletion with FOREIGN_KEY_CHECKS disabled...\n";
    $link->query("SET FOREIGN_KEY_CHECKS = 0");
    $delete_result = $link->query($delete_sql);
    $delete_affected = $link->affected_rows;
    $link->query("SET FOREIGN_KEY_CHECKS = 1");

    echo "Result: " . ($delete_result ? "SUCCESS" : "FAILED") . ", Affected rows: {$delete_affected}\n";
    if ($link->error) {
        echo "MySQL Error: {$link->error} (Code: {$link->errno})\n";
    }
    echo "\n";
}

// Step 4: Verify deletion
if ($confirm) {
    echo "Step 4: Verifying deletion...\n";
    $verify = $db->query("SELECT manufacturer_id FROM {$prefix}manufacturer WHERE manufacturer_id = {$test_id}");
    if (!$verify->num_rows) {
        echo "✓ SUCCESS: Manufacturer record deleted and verified!\n";
    } else {
        echo "✗ FAILED: Manufacturer record still exists!\n";
        echo "This indicates the DELETE query did not work.\n";
        echo "\nPossible causes:\n";
        echo "  1. Database permissions issue\n";
        echo "  2. Foreign key constraint preventing deletion\n";
        echo "  3. Transaction rollback\n";
        echo "  4. Table lock preventing deletion\n";
        echo "  5. Database user lacks DELETE permission\n";
    }
} else {
    echo "Step 4: Skipped (preview mode)\n";
}

echo "\n";
echo str_repeat("=", 60) . "\n";
echo "Test complete.\n";
echo "</pre>";


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

echo "<h1>Direct Manufacturer Deletion Test</h1>";
echo "<pre>";

$prefix = DB_PREFIX;

// Get manufacturer ID from URL
$test_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$confirm = isset($_GET['confirm']) && $_GET['confirm'] == '1';

if ($test_id <= 0) {
    echo "Usage: ?id=X (where X is the manufacturer_id to delete)\n";
    echo "       ?id=X&confirm=1 (to actually delete)\n";
    echo "\nAvailable manufacturers:\n";
    $manufacturers = $db->query("SELECT manufacturer_id, name FROM {$prefix}manufacturer ORDER BY manufacturer_id LIMIT 20");
    foreach ($manufacturers->rows as $mfg) {
        $prod_check = $db->query("SELECT COUNT(*) as count FROM {$prefix}product WHERE manufacturer_id = {$mfg['manufacturer_id']}");
        $has_products = ($prod_check->row['count'] ?? 0) > 0;
        $prod_text = $has_products ? " (has products)" : "";
        echo "  ID {$mfg['manufacturer_id']}: {$mfg['name']}{$prod_text}\n";
        echo "    <a href='?id={$mfg['manufacturer_id']}'>[View Details]</a> ";
        if (!$has_products) {
            echo "<a href='?id={$mfg['manufacturer_id']}&confirm=1' style='color:red;'>[DELETE]</a>";
        }
        echo "\n";
    }
    echo "</pre>";
    exit;
}

echo "Testing deletion of manufacturer ID: {$test_id}\n";
echo str_repeat("=", 60) . "\n\n";

// Check if exists
$check = $db->query("SELECT manufacturer_id, name FROM {$prefix}manufacturer WHERE manufacturer_id = {$test_id}");
if (!$check->num_rows) {
    // Check if it was just deleted
    $related_check = $db->query("SELECT COUNT(*) as count FROM {$prefix}manufacturer_description WHERE manufacturer_id = {$test_id}");
    $related_count = $related_check->row['count'] ?? 0;
    
    if ($related_count > 0) {
        echo "WARNING: Manufacturer ID {$test_id} not found in main table, but related records still exist!\n";
        echo "This suggests the main record was deleted but related records remain.\n";
        echo "Cleaning up related records...\n\n";
    } else {
        echo "INFO: Manufacturer ID {$test_id} does not exist (may have been deleted already).\n";
        echo "Checking for any orphaned related records...\n\n";
        
        // Clean up any orphaned records
        $tables_to_clean = array(
            'url_alias' => "DELETE FROM {$prefix}url_alias WHERE query = 'manufacturer_id={$test_id}'",
            'manufacturer_to_layout' => "DELETE FROM {$prefix}manufacturer_to_layout WHERE manufacturer_id = {$test_id}",
            'manufacturer_to_store' => "DELETE FROM {$prefix}manufacturer_to_store WHERE manufacturer_id = {$test_id}",
            'manufacturer_description' => "DELETE FROM {$prefix}manufacturer_description WHERE manufacturer_id = {$test_id}"
        );
        
        foreach ($tables_to_clean as $table => $sql) {
            $result = $link->query($sql);
            $affected = $link->affected_rows;
            if ($affected > 0) {
                echo "  Cleaned up {$table}: {$affected} record(s)\n";
            }
        }
        
        echo "\n✓ Cleanup complete. Manufacturer ID {$test_id} is fully deleted.\n";
        echo "</pre>";
        exit;
    }
} else {
    echo "Manufacturer found: {$check->row['name']}\n\n";
}

// Get direct MySQLi link
$reflection = new ReflectionClass($db);
$db_property = $reflection->getProperty('db');
$db_property->setAccessible(true);
$db_driver = $db_property->getValue($db);
$link_reflection = new ReflectionProperty($db_driver, 'link');
$link_reflection->setAccessible(true);
$link = $link_reflection->getValue($db_driver);

if (!is_object($link)) {
    echo "ERROR: Could not get MySQLi link!\n";
    echo "</pre>";
    exit;
}

echo "Using direct MySQLi connection\n\n";

// Step 1: Unlink products (only if manufacturer exists or if confirm is set)
if ($check->num_rows || $confirm) {
    $product_count = $db->query("SELECT COUNT(*) as count FROM {$prefix}product WHERE manufacturer_id = {$test_id}");
    $prod_count = $product_count->row['count'] ?? 0;
    if ($prod_count > 0) {
        if ($confirm) {
            echo "Step 1: Unlinking {$prod_count} products...\n";
            $unlink_sql = "UPDATE {$prefix}product SET manufacturer_id = 0 WHERE manufacturer_id = {$test_id}";
            echo "SQL: {$unlink_sql}\n";
            $unlink_result = $link->query($unlink_sql);
            $unlink_affected = $link->affected_rows;
            echo "Result: " . ($unlink_result ? "SUCCESS" : "FAILED") . ", Affected rows: {$unlink_affected}\n";
            if ($link->error) {
                echo "MySQL Error: {$link->error} (Code: {$link->errno})\n";
            }
        } else {
            echo "Step 1: Preview - {$prod_count} products would be unlinked\n";
        }
        echo "\n";
    } else {
        echo "Step 1: No products linked to this manufacturer\n\n";
    }
}

// Step 2: Delete related records
if ($confirm) {
    echo "Step 2: Deleting related records...\n";
    
    $tables = array(
        'url_alias' => "DELETE FROM {$prefix}url_alias WHERE query = 'manufacturer_id={$test_id}'",
        'manufacturer_to_layout' => "DELETE FROM {$prefix}manufacturer_to_layout WHERE manufacturer_id = {$test_id}",
        'manufacturer_to_store' => "DELETE FROM {$prefix}manufacturer_to_store WHERE manufacturer_id = {$test_id}",
        'manufacturer_description' => "DELETE FROM {$prefix}manufacturer_description WHERE manufacturer_id = {$test_id}"
    );
    
    foreach ($tables as $table => $sql) {
        echo "  Deleting from {$table}...\n";
        echo "  SQL: {$sql}\n";
        $result = $link->query($sql);
        $affected = $link->affected_rows;
        echo "  Result: " . ($result ? "SUCCESS" : "FAILED") . ", Affected rows: {$affected}\n";
        if ($link->error) {
            echo "  MySQL Error: {$link->error} (Code: {$link->errno})\n";
        }
        echo "\n";
    }
} else {
    echo "Step 2: Preview - Related records that would be deleted...\n";
    
    $tables_preview = array(
        'url_alias' => "SELECT COUNT(*) as count FROM {$prefix}url_alias WHERE query = 'manufacturer_id={$test_id}'",
        'manufacturer_to_layout' => "SELECT COUNT(*) as count FROM {$prefix}manufacturer_to_layout WHERE manufacturer_id = {$test_id}",
        'manufacturer_to_store' => "SELECT COUNT(*) as count FROM {$prefix}manufacturer_to_store WHERE manufacturer_id = {$test_id}",
        'manufacturer_description' => "SELECT COUNT(*) as count FROM {$prefix}manufacturer_description WHERE manufacturer_id = {$test_id}"
    );
    
    foreach ($tables_preview as $table => $check_sql) {
        $check_result = $db->query($check_sql);
        $count = $check_result->row['count'] ?? 0;
        echo "  {$table}: {$count} record(s) would be deleted\n";
    }
    echo "\n";
}

// Step 3: Delete main record
if (!$confirm) {
    echo "Step 3: Preview (not deleting yet)\n";
    echo "To actually delete, add &confirm=1 to the URL\n";
    echo "Example: ?id={$test_id}&confirm=1\n\n";
} else {
    echo "Step 3: Deleting main manufacturer record...\n";
    $delete_sql = "DELETE FROM {$prefix}manufacturer WHERE manufacturer_id = {$test_id}";
    echo "SQL: {$delete_sql}\n";

    // Check for foreign key constraints first
    echo "Checking for foreign key constraints...\n";
    $fk_check = $db->query("SELECT 
        CONSTRAINT_NAME, 
        TABLE_NAME, 
        REFERENCED_TABLE_NAME,
        REFERENCED_COLUMN_NAME
    FROM information_schema.KEY_COLUMN_USAGE 
    WHERE REFERENCED_TABLE_SCHEMA = '" . DB_DATABASE . "' 
    AND REFERENCED_TABLE_NAME = '{$prefix}manufacturer'
    AND REFERENCED_COLUMN_NAME = 'manufacturer_id'");

    if ($fk_check->num_rows > 0) {
        echo "WARNING: Foreign key constraints found:\n";
        foreach ($fk_check->rows as $fk) {
            echo "  - Table: {$fk['TABLE_NAME']}, Constraint: {$fk['CONSTRAINT_NAME']}\n";
        }
        echo "Disabling foreign key checks temporarily...\n\n";
    }

    // Try with foreign key checks disabled
    echo "Attempting deletion with FOREIGN_KEY_CHECKS disabled...\n";
    $link->query("SET FOREIGN_KEY_CHECKS = 0");
    $delete_result = $link->query($delete_sql);
    $delete_affected = $link->affected_rows;
    $link->query("SET FOREIGN_KEY_CHECKS = 1");

    echo "Result: " . ($delete_result ? "SUCCESS" : "FAILED") . ", Affected rows: {$delete_affected}\n";
    if ($link->error) {
        echo "MySQL Error: {$link->error} (Code: {$link->errno})\n";
    }
    echo "\n";
}

// Step 4: Verify deletion
if ($confirm) {
    echo "Step 4: Verifying deletion...\n";
    $verify = $db->query("SELECT manufacturer_id FROM {$prefix}manufacturer WHERE manufacturer_id = {$test_id}");
    if (!$verify->num_rows) {
        echo "✓ SUCCESS: Manufacturer record deleted and verified!\n";
    } else {
        echo "✗ FAILED: Manufacturer record still exists!\n";
        echo "This indicates the DELETE query did not work.\n";
        echo "\nPossible causes:\n";
        echo "  1. Database permissions issue\n";
        echo "  2. Foreign key constraint preventing deletion\n";
        echo "  3. Transaction rollback\n";
        echo "  4. Table lock preventing deletion\n";
        echo "  5. Database user lacks DELETE permission\n";
    }
} else {
    echo "Step 4: Skipped (preview mode)\n";
}

echo "\n";
echo str_repeat("=", 60) . "\n";
echo "Test complete.\n";
echo "</pre>";

