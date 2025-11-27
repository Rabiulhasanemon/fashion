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

if ($test_id <= 0) {
    echo "Usage: ?id=X (where X is the manufacturer_id to delete)\n";
    echo "\nAvailable manufacturers:\n";
    $manufacturers = $db->query("SELECT manufacturer_id, name FROM {$prefix}manufacturer ORDER BY manufacturer_id LIMIT 20");
    foreach ($manufacturers->rows as $mfg) {
        echo "  ID {$mfg['manufacturer_id']}: {$mfg['name']}\n";
    }
    echo "</pre>";
    exit;
}

echo "Testing deletion of manufacturer ID: {$test_id}\n";
echo str_repeat("=", 60) . "\n\n";

// Check if exists
$check = $db->query("SELECT manufacturer_id, name FROM {$prefix}manufacturer WHERE manufacturer_id = {$test_id}");
if (!$check->num_rows) {
    echo "ERROR: Manufacturer ID {$test_id} does not exist!\n";
    echo "</pre>";
    exit;
}

echo "Manufacturer found: {$check->row['name']}\n\n";

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

// Step 1: Unlink products
$product_count = $db->query("SELECT COUNT(*) as count FROM {$prefix}product WHERE manufacturer_id = {$test_id}");
$prod_count = $product_count->row['count'] ?? 0;
if ($prod_count > 0) {
    echo "Step 1: Unlinking {$prod_count} products...\n";
    $unlink_sql = "UPDATE {$prefix}product SET manufacturer_id = 0 WHERE manufacturer_id = {$test_id}";
    echo "SQL: {$unlink_sql}\n";
    $unlink_result = $link->query($unlink_sql);
    $unlink_affected = $link->affected_rows;
    echo "Result: " . ($unlink_result ? "SUCCESS" : "FAILED") . ", Affected rows: {$unlink_affected}\n";
    if ($link->error) {
        echo "MySQL Error: {$link->error} (Code: {$link->errno})\n";
    }
    echo "\n";
}

// Step 2: Delete related records
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

// Step 3: Delete main record
echo "Step 3: Deleting main manufacturer record...\n";
$delete_sql = "DELETE FROM {$prefix}manufacturer WHERE manufacturer_id = {$test_id}";
echo "SQL: {$delete_sql}\n";
$delete_result = $link->query($delete_sql);
$delete_affected = $link->affected_rows;
echo "Result: " . ($delete_result ? "SUCCESS" : "FAILED") . ", Affected rows: {$delete_affected}\n";
if ($link->error) {
    echo "MySQL Error: {$link->error} (Code: {$link->errno})\n";
}
echo "\n";

// Step 4: Verify deletion
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
}

echo "\n";
echo str_repeat("=", 60) . "\n";
echo "Test complete.\n";
echo "</pre>";

