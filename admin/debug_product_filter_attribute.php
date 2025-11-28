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
    // Get default language ID
    $config_query = $db->query("SELECT config_language_id FROM {$prefix}setting WHERE `key` = 'config_language_id' LIMIT 1");
    $default_lang_id = isset($config_query->row['config_language_id']) ? (int)$config_query->row['config_language_id'] : 1;
    
    $filters = $db->query("SELECT pf.filter_id, 
                                  fd.name as filter_name,
                                  fgd.name as group_name 
                           FROM {$prefix}product_filter pf 
                           LEFT JOIN {$prefix}filter f ON pf.filter_id = f.filter_id 
                           LEFT JOIN {$prefix}filter_description fd ON (f.filter_id = fd.filter_id AND fd.language_id = {$default_lang_id})
                           LEFT JOIN {$prefix}filter_group fg ON f.filter_group_id = fg.filter_group_id 
                           LEFT JOIN {$prefix}filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id AND fgd.language_id = {$default_lang_id})
                           WHERE pf.product_id = {$product_id} 
                           ORDER BY fg.sort_order, f.sort_order");
    
    if ($filters->num_rows > 0) {
        echo "Found {$filters->num_rows} filter(s):\n";
        foreach ($filters->rows as $filter) {
            $filter_name = isset($filter['filter_name']) ? $filter['filter_name'] : 'Unknown';
            $group_name = isset($filter['group_name']) ? $filter['group_name'] : 'Unknown';
            echo "  - Filter ID {$filter['filter_id']}: {$filter_name} (Group: {$group_name})\n";
        }
    } else {
        echo "No filters found for this product.\n";
    }
    
    // Check attributes
    echo "\n=== ATTRIBUTES ===\n";
    $attributes = $db->query("SELECT pa.attribute_id, pa.language_id, pa.text, 
                                      ad.name as attribute_name,
                                      agd.name as group_name 
                               FROM {$prefix}product_attribute pa 
                               LEFT JOIN {$prefix}attribute a ON pa.attribute_id = a.attribute_id 
                               LEFT JOIN {$prefix}attribute_description ad ON (a.attribute_id = ad.attribute_id AND ad.language_id = {$default_lang_id})
                               LEFT JOIN {$prefix}attribute_group ag ON a.attribute_group_id = ag.attribute_group_id 
                               LEFT JOIN {$prefix}attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id AND agd.language_id = {$default_lang_id})
                               WHERE pa.product_id = {$product_id} 
                               ORDER BY ag.sort_order, a.sort_order, pa.language_id");
    
    if ($attributes->num_rows > 0) {
        echo "Found {$attributes->num_rows} attribute record(s):\n";
        $current_attr_id = 0;
        foreach ($attributes->rows as $attr) {
            if ($current_attr_id != $attr['attribute_id']) {
                if ($current_attr_id > 0) echo "\n";
                $attr_name = isset($attr['attribute_name']) ? $attr['attribute_name'] : 'Unknown';
                $group_name = isset($attr['group_name']) ? $attr['group_name'] : 'Unknown';
                echo "  Attribute ID {$attr['attribute_id']}: {$attr_name} (Group: {$group_name})\n";
                $current_attr_id = $attr['attribute_id'];
            }
            $text = isset($attr['text']) ? $attr['text'] : '';
            echo "    Language {$attr['language_id']}: " . (strlen($text) > 0 ? substr($text, 0, 50) . '...' : '(empty)') . "\n";
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

