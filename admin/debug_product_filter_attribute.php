<?php
// Debug script for product filter and attribute issues
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

echo "<h1>Product Filter & Attribute Debug</h1>";
echo "<pre>";

$prefix = DB_PREFIX;

// Get product ID from URL
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if ($product_id > 0) {
    echo "Debugging Product ID: {$product_id}\n";
    echo str_repeat("=", 60) . "\n\n";
    
    // Check if product exists
    $product = $db->query("SELECT product_id, model, sku FROM {$prefix}product WHERE product_id = {$product_id}");
    if (!$product->num_rows) {
        echo "ERROR: Product ID {$product_id} does not exist!\n";
        echo "</pre>";
        exit;
    }
    
    echo "Product: {$product->row['model']} (SKU: {$product->row['sku']})\n\n";
    
    // Check filters
    echo "=== FILTERS ===\n";
    $filters = $db->query("SELECT pf.filter_id, f.name, fg.name as group_name 
                           FROM {$prefix}product_filter pf 
                           LEFT JOIN {$prefix}filter f ON pf.filter_id = f.filter_id 
                           LEFT JOIN {$prefix}filter_group fg ON f.filter_group_id = fg.filter_group_id 
                           WHERE pf.product_id = {$product_id} 
                           ORDER BY fg.sort_order, f.sort_order");
    
    if ($filters->num_rows > 0) {
        echo "Found {$filters->num_rows} filter(s):\n";
        foreach ($filters->rows as $filter) {
            echo "  - Filter ID {$filter['filter_id']}: {$filter['name']} (Group: {$filter['group_name']})\n";
        }
    } else {
        echo "No filters found for this product.\n";
    }
    
    // Check attributes
    echo "\n=== ATTRIBUTES ===\n";
    $attributes = $db->query("SELECT pa.attribute_id, pa.language_id, pa.text, a.name, ag.name as group_name 
                               FROM {$prefix}product_attribute pa 
                               LEFT JOIN {$prefix}attribute a ON pa.attribute_id = a.attribute_id 
                               LEFT JOIN {$prefix}attribute_group ag ON a.attribute_group_id = ag.attribute_group_id 
                               WHERE pa.product_id = {$product_id} 
                               ORDER BY ag.sort_order, a.sort_order, pa.language_id");
    
    if ($attributes->num_rows > 0) {
        echo "Found {$attributes->num_rows} attribute record(s):\n";
        $current_attr_id = 0;
        foreach ($attributes->rows as $attr) {
            if ($current_attr_id != $attr['attribute_id']) {
                if ($current_attr_id > 0) echo "\n";
                echo "  Attribute ID {$attr['attribute_id']}: {$attr['name']} (Group: {$attr['group_name']})\n";
                $current_attr_id = $attr['attribute_id'];
            }
            echo "    Language {$attr['language_id']}: " . (strlen($attr['text']) > 0 ? substr($attr['text'], 0, 50) . '...' : '(empty)') . "\n";
        }
    } else {
        echo "No attributes found for this product.\n";
    }
    
    // Check for product_id = 0 records
    echo "\n=== CHECKING FOR product_id = 0 RECORDS ===\n";
    $tables_to_check = array('product_filter', 'product_attribute', 'product_description', 'product_to_store');
    foreach ($tables_to_check as $table) {
        $check = $db->query("SELECT COUNT(*) as count FROM {$prefix}{$table} WHERE product_id = 0");
        $count = isset($check->row['count']) ? (int)$check->row['count'] : 0;
        if ($count > 0) {
            echo "  WARNING: {$table} has {$count} record(s) with product_id = 0\n";
        } else {
            echo "  {$table}: OK (no product_id = 0 records)\n";
        }
    }
    
    // Check recent log entries for this product
    echo "\n=== RECENT LOG ENTRIES FOR THIS PRODUCT ===\n";
    $log_file = DIR_LOGS . 'product_insert_debug.log';
    if (file_exists($log_file)) {
        $log_content = file_get_contents($log_file);
        $lines = explode("\n", $log_content);
        $relevant_lines = array();
        
        // Get last 50 lines that mention this product_id
        foreach (array_reverse($lines) as $line) {
            if (strpos($line, "product_id: {$product_id}") !== false || 
                strpos($line, "[FILTER]") !== false || 
                strpos($line, "[ATTRIBUTE]") !== false) {
                $relevant_lines[] = $line;
                if (count($relevant_lines) >= 20) break;
            }
        }
        
        if (count($relevant_lines) > 0) {
            echo "Found " . count($relevant_lines) . " relevant log entries:\n";
            foreach (array_reverse($relevant_lines) as $line) {
                echo "  " . substr($line, 0, 100) . "\n";
            }
        } else {
            echo "No recent log entries found for this product.\n";
        }
    } else {
        echo "Log file not found: {$log_file}\n";
    }
    
    // Check if product has attribute_profile_id
    $product_info = $db->query("SELECT attribute_profile_id FROM {$prefix}product WHERE product_id = {$product_id}");
    if ($product_info->num_rows && isset($product_info->row['attribute_profile_id'])) {
        $attr_profile_id = (int)$product_info->row['attribute_profile_id'];
        echo "\n=== ATTRIBUTE PROFILE ===\n";
        echo "Product has attribute_profile_id: {$attr_profile_id}\n";
        if ($attr_profile_id > 0) {
            $profile = $db->query("SELECT name FROM {$prefix}attribute_profile WHERE attribute_profile_id = {$attr_profile_id}");
            if ($profile->num_rows) {
                echo "Profile name: {$profile->row['name']}\n";
            }
        }
    }
    
    // Check filter profiles
    $filter_profiles = $db->query("SELECT fp.filter_profile_id, fp.name 
                                   FROM {$prefix}product_to_filter_profile ptfp 
                                   LEFT JOIN {$prefix}filter_profile fp ON ptfp.filter_profile_id = fp.filter_profile_id 
                                   WHERE ptfp.product_id = {$product_id}");
    if ($filter_profiles->num_rows > 0) {
        echo "\n=== FILTER PROFILES ===\n";
        foreach ($filter_profiles->rows as $fp) {
            echo "  - Profile ID {$fp['filter_profile_id']}: {$fp['name']}\n";
        }
    }
    
} else {
    echo "Usage: ?product_id=X (where X is the product_id to debug)\n";
    echo "\nRecent products:\n";
    $products = $db->query("SELECT product_id, model, sku FROM {$prefix}product ORDER BY product_id DESC LIMIT 20");
    foreach ($products->rows as $prod) {
        echo "  ID {$prod['product_id']}: {$prod['model']} (SKU: {$prod['sku']})\n";
    }
}

echo "\n";
echo str_repeat("=", 60) . "\n";
echo "Debug complete.\n";
echo "</pre>";

