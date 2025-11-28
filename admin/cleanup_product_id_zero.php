<?php
// Cleanup script to remove all product_id = 0 records from database
// Run this script if you're getting "Duplicate entry" errors

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

echo "<h1>Product ID = 0 Cleanup Script</h1>";
echo "<pre>";

$prefix = DB_PREFIX;

echo "Starting cleanup of product_id = 0 records...\n";
echo str_repeat("=", 60) . "\n\n";

// List of all tables that might have product_id
$cleanup_tables = array(
	'product_description',
	'product_to_store',
	'product_to_category',
	'product_image',
	'product_option',
	'product_option_value',
	'product_filter',
	'product_attribute',
	'product_discount',
	'product_special',
	'product_reward',
	'product_related',
	'product_compatible',
	'product_to_layout',
	'product_to_download',
	'product_variation'
);

$total_deleted = 0;

// Clean up each table
foreach ($cleanup_tables as $table) {
	$table_name = $prefix . $table;
	
	// Check if table exists
	$check_table = $db->query("SHOW TABLES LIKE '" . $table_name . "'");
	if (!$check_table || !$check_table->num_rows) {
		echo "  Table {$table_name} does not exist, skipping...\n";
		continue;
	}
	
	// Check if product_id column exists
	$check_column = $db->query("SHOW COLUMNS FROM `{$table_name}` LIKE 'product_id'");
	if (!$check_column || !$check_column->num_rows) {
		echo "  Table {$table_name} does not have product_id column, skipping...\n";
		continue;
	}
	
	// Count records with product_id = 0
	$count_query = $db->query("SELECT COUNT(*) as count FROM {$table_name} WHERE product_id = 0");
	$count = isset($count_query->row['count']) ? (int)$count_query->row['count'] : 0;
	
	if ($count > 0) {
		echo "  Found {$count} record(s) with product_id = 0 in {$table_name}\n";
		
		// Delete them
		try {
			$delete_result = $db->query("DELETE FROM {$table_name} WHERE product_id = 0");
			$deleted = $db->countAffected();
			$total_deleted += $deleted;
			echo "    ✓ Deleted {$deleted} record(s)\n";
		} catch (Exception $e) {
			echo "    ✗ Error deleting from {$table_name}: " . $e->getMessage() . "\n";
		}
	} else {
		echo "  No records with product_id = 0 in {$table_name}\n";
	}
}

// Clean up main product table
echo "\nChecking main product table...\n";
$product_count = $db->query("SELECT COUNT(*) as count FROM {$prefix}product WHERE product_id = 0");
$product_zero_count = isset($product_count->row['count']) ? (int)$product_count->row['count'] : 0;

if ($product_zero_count > 0) {
	echo "  Found {$product_zero_count} product(s) with product_id = 0\n";
	try {
		$delete_result = $db->query("DELETE FROM {$prefix}product WHERE product_id = 0");
		$deleted = $db->countAffected();
		$total_deleted += $deleted;
		echo "    ✓ Deleted {$deleted} product(s)\n";
	} catch (Exception $e) {
		echo "    ✗ Error deleting products: " . $e->getMessage() . "\n";
	}
} else {
	echo "  No products with product_id = 0\n";
}

// Clean up url_alias
echo "\nChecking url_alias table...\n";
$url_count = $db->query("SELECT COUNT(*) as count FROM {$prefix}url_alias WHERE query = 'product_id=0'");
$url_zero_count = isset($url_count->row['count']) ? (int)$url_count->row['count'] : 0;

if ($url_zero_count > 0) {
	echo "  Found {$url_zero_count} url_alias record(s) for product_id=0\n";
	try {
		$delete_result = $db->query("DELETE FROM {$prefix}url_alias WHERE query = 'product_id=0'");
		$deleted = $db->countAffected();
		$total_deleted += $deleted;
		echo "    ✓ Deleted {$deleted} url_alias record(s)\n";
	} catch (Exception $e) {
		echo "    ✗ Error deleting url_alias: " . $e->getMessage() . "\n";
	}
} else {
	echo "  No url_alias records for product_id=0\n";
}

// Fix AUTO_INCREMENT
echo "\nFixing AUTO_INCREMENT...\n";
$max_check = $db->query("SELECT MAX(product_id) as max_id FROM {$prefix}product WHERE product_id > 0");
$max_id = 0;
if ($max_check && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
	$max_id = (int)$max_check->row['max_id'];
}
$next_id = max($max_id + 1, 1);

try {
	$db->query("ALTER TABLE {$prefix}product AUTO_INCREMENT = {$next_id}");
	echo "  ✓ Set AUTO_INCREMENT to {$next_id}\n";
} catch (Exception $e) {
	echo "  ✗ Error setting AUTO_INCREMENT: " . $e->getMessage() . "\n";
}

// Verify cleanup
echo "\nVerifying cleanup...\n";
$verify_product = $db->query("SELECT COUNT(*) as count FROM {$prefix}product WHERE product_id = 0");
$remaining = isset($verify_product->row['count']) ? (int)$verify_product->row['count'] : 0;

if ($remaining == 0) {
	echo "  ✓ Cleanup successful! No product_id = 0 records remaining.\n";
} else {
	echo "  ✗ WARNING: {$remaining} product(s) with product_id = 0 still exist!\n";
	echo "  You may need to manually delete these records from the database.\n";
}

echo "\n";
echo str_repeat("=", 60) . "\n";
echo "Cleanup complete!\n";
echo "Total records deleted: {$total_deleted}\n";
echo "\nYou can now try saving your product again.\n";
echo "</pre>";

