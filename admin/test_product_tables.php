<?php
// Test script to check product_option and product_option_value table structure
// Run this from browser: http://your-site.com/admin/test_product_tables.php

// Include OpenCart configuration
require_once('../config.php');

// Start output
echo "<h2>Product Tables Structure Test</h2>";
echo "<pre>";

// Database connection
$db = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check product_option table
echo "\n=== Checking " . DB_PREFIX . "product_option table ===\n";
$table_check = $db->query("SHOW TABLES LIKE '" . DB_PREFIX . "product_option'");
if ($table_check && $table_check->num_rows > 0) {
    echo "✓ Table exists\n";
    
    // Get columns
    $columns = $db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product_option`");
    echo "\nColumns in " . DB_PREFIX . "product_option:\n";
    $has_product_id = false;
    while ($row = $columns->fetch_assoc()) {
        echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
        if ($row['Field'] == 'product_id') {
            $has_product_id = true;
        }
    }
    
    if (!$has_product_id) {
        echo "\n✗ ERROR: product_id column is MISSING!\n";
    } else {
        echo "\n✓ product_id column exists\n";
    }
} else {
    echo "✗ ERROR: Table does not exist!\n";
}

// Check product_option_value table
echo "\n=== Checking " . DB_PREFIX . "product_option_value table ===\n";
$table_check = $db->query("SHOW TABLES LIKE '" . DB_PREFIX . "product_option_value'");
if ($table_check && $table_check->num_rows > 0) {
    echo "✓ Table exists\n";
    
    // Get columns
    $columns = $db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product_option_value`");
    echo "\nColumns in " . DB_PREFIX . "product_option_value:\n";
    $has_product_id = false;
    while ($row = $columns->fetch_assoc()) {
        echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
        if ($row['Field'] == 'product_id') {
            $has_product_id = true;
        }
    }
    
    if (!$has_product_id) {
        echo "\n✗ ERROR: product_id column is MISSING!\n";
    } else {
        echo "\n✓ product_id column exists\n";
    }
} else {
    echo "✗ ERROR: Table does not exist!\n";
}

// Test a query
echo "\n=== Testing DELETE query ===\n";
try {
    $test_id = 999999; // Non-existent ID for testing
    $sql = "DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . $test_id . "'";
    $result = $db->query($sql);
    if ($result) {
        echo "✓ DELETE query on product_option works\n";
    } else {
        echo "✗ DELETE query failed: " . $db->error . "\n";
    }
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

try {
    $test_id = 999999; // Non-existent ID for testing
    $sql = "DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . $test_id . "'";
    $result = $db->query($sql);
    if ($result) {
        echo "✓ DELETE query on product_option_value works\n";
    } else {
        echo "✗ DELETE query failed: " . $db->error . "\n";
    }
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

$db->close();
echo "</pre>";
?>

