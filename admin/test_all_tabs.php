<?php
// Comprehensive test script for all product tabs
// Place this in your admin root and access via browser

// Suppress deprecation warnings from third-party libraries (Google API)
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);

// Start output buffering to filter deprecation warnings
ob_start(function($buffer) {
    // Remove Google API deprecation warnings
    $buffer = preg_replace('/Deprecated:.*?Google.*?\n/', '', $buffer);
    return $buffer;
});

// Include OpenCart configuration
if (!file_exists('config.php')) {
    die("Error: config.php not found in " . __DIR__);
}

require_once('config.php');

if (!defined('DIR_SYSTEM')) {
    die("Error: DIR_SYSTEM not defined after loading config.php");
}

if (!file_exists(DIR_SYSTEM . 'startup.php')) {
    die("Error: startup.php not found at " . DIR_SYSTEM . 'startup.php');
}

require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
// Try to load config files, but don't fail if they don't exist
try {
    if (file_exists(DIR_SYSTEM . 'config/default.php')) {
        $config->load('default');
    }
} catch (Exception $e) {
    // Ignore config loading errors for default
}

try {
    if (file_exists(DIR_SYSTEM . 'config/admin.php')) {
        $config->load('admin');
    }
} catch (Exception $e) {
    // Ignore config loading errors for admin
}

$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Load language
$language = new Language('english');
$language->load('english');
$registry->set('language', $language);

// Load model
require_once(DIR_APPLICATION . 'model/catalog/product.php');
$model = new ModelCatalogProduct($registry);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>All Tabs Test</title>";
echo "<style>
body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;} 
.container{max-width:1400px;margin:0 auto;background:white;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);} 
h2{color:#333;border-bottom:2px solid #007bff;padding-bottom:10px;} 
h3{color:#555;margin-top:20px;background:#f0f0f0;padding:10px;border-left:4px solid #007bff;} 
.success{color:green;font-weight:bold;} 
.error{color:red;font-weight:bold;} 
.warning{color:orange;font-weight:bold;}
.info{background:#e7f3ff;padding:10px;border-left:4px solid #2196F3;margin:10px 0;} 
pre{background:#f8f8f8;padding:10px;border-radius:4px;overflow-x:auto;font-size:12px;}
.tab-section{margin:15px 0;padding:15px;border:1px solid #ddd;border-radius:4px;background:#fafafa;}
.tab-section h4{margin-top:0;color:#007bff;}
.status-ok{color:green;}
.status-fail{color:red;}
.status-missing{color:orange;}
table{border-collapse:collapse;width:100%;margin:10px 0;}
table th, table td{border:1px solid #ddd;padding:8px;text-align:left;}
table th{background:#007bff;color:white;}
table tr:nth-child(even){background:#f9f9f9;}
</style>";
echo "</head><body><div class='container'>";

echo "<h2>🔍 Comprehensive Product Tabs Test & Debug</h2>";

// Get available data for testing (with error handling)
try {
    $manufacturers = $db->query("SELECT manufacturer_id, name FROM " . DB_PREFIX . "manufacturer LIMIT 5");
} catch (Exception $e) {
    $manufacturers = false;
}

try {
    $categories = $db->query("SELECT category_id, name FROM " . DB_PREFIX . "category LIMIT 5");
} catch (Exception $e) {
    $categories = false;
}

try {
    $filters = $db->query("SELECT filter_id, name FROM " . DB_PREFIX . "filter LIMIT 5");
} catch (Exception $e) {
    $filters = false;
}

try {
    $attributes = $db->query("SELECT attribute_id, name FROM " . DB_PREFIX . "attribute LIMIT 3");
} catch (Exception $e) {
    $attributes = false;
}

try {
    $downloads = $db->query("SELECT download_id, name FROM " . DB_PREFIX . "download LIMIT 3");
} catch (Exception $e) {
    $downloads = false;
}

try {
    $layouts = $db->query("SELECT layout_id, name FROM " . DB_PREFIX . "layout LIMIT 3");
} catch (Exception $e) {
    $layouts = false;
}

try {
    $customer_groups = $db->query("SELECT customer_group_id, name FROM " . DB_PREFIX . "customer_group LIMIT 3");
} catch (Exception $e) {
    $customer_groups = false;
}

try {
    $products = $db->query("SELECT product_id, name FROM " . DB_PREFIX . "product_description WHERE language_id = 1 LIMIT 5");
} catch (Exception $e) {
    $products = false;
}

// Clean up any existing test products first
$db->query("DELETE FROM " . DB_PREFIX . "product WHERE model = 'TEST_ALL_TABS'");

// Prepare comprehensive test data for ALL tabs
$test_data = array(
    'model' => 'TEST_ALL_TABS',
    'sku' => 'TEST_SKU_ALL_TABS',
    'quantity' => 10,
    'minimum' => 1,
    'maximum' => 100,
    'status' => 0, // Set to 0 so it doesn't show in frontend
    'price' => 199.99,
    'cost_price' => 100.00,
    'regular_price' => 249.99,
    'manufacturer_id' => ($manufacturers && $manufacturers->num_rows) ? (int)$manufacturers->row['manufacturer_id'] : 0,
    'parent_id' => ($categories && $categories->num_rows) ? (int)$categories->row['category_id'] : 0,
    'image' => 'catalog/demo/htc_touch_hd_1.jpg', // Main image
    'featured_image' => 'catalog/demo/htc_touch_hd_2.jpg', // Featured image
    'product_description' => array(
        1 => array(
            'name' => 'Test Product - All Tabs',
            'description' => 'This is a comprehensive test product for debugging all tabs',
            'meta_title' => 'Test Product All Tabs',
            'meta_description' => 'Test product with all tabs data',
            'meta_keyword' => 'test, all, tabs'
        )
    ),
    'product_store' => array(0),
    // Links Tab
    'product_category' => array(),
    'product_download' => array(),
    'product_related' => array(),
    'product_compatible' => array(),
    // Filter Tab
    'product_filter' => array(),
    // Attribute Tab
    'product_attribute' => array(),
    // Option Tab
    'product_option' => array(),
    // Discount Tab
    'product_discount' => array(),
    // Special Tab
    'product_special' => array(),
    // Image Tab
    'product_image' => array(),
    // Reward Points Tab
    'product_reward' => array(),
    // Design Tab
    'product_layout' => array()
);

// Populate categories
if ($categories && $categories->num_rows) {
    foreach ($categories->rows as $cat) {
        $test_data['product_category'][] = (int)$cat['category_id'];
    }
}

// Populate downloads
if ($downloads && $downloads->num_rows) {
    foreach ($downloads->rows as $dl) {
        $test_data['product_download'][] = (int)$dl['download_id'];
    }
}

// Populate related products
if ($products && $products->num_rows) {
    $product_ids = array();
    foreach ($products->rows as $prod) {
        $product_ids[] = (int)$prod['product_id'];
    }
    if (count($product_ids) >= 2) {
        $test_data['product_related'] = array_slice($product_ids, 0, 2);
        $test_data['product_compatible'] = array_slice($product_ids, 2, 2);
    }
}

// Populate filters
if ($filters && $filters->num_rows) {
    foreach ($filters->rows as $filter) {
        $test_data['product_filter'][] = (int)$filter['filter_id'];
    }
}

// Populate attributes
if ($attributes && $attributes->num_rows) {
    foreach ($attributes->rows as $attr) {
        $test_data['product_attribute'][] = array(
            'attribute_id' => (int)$attr['attribute_id'],
            'product_attribute_description' => array(
                1 => array('text' => 'Test Attribute Value for ' . $attr['name'])
            )
        );
    }
}

// Populate discounts
if ($customer_groups && isset($customer_groups->rows) && is_array($customer_groups->rows) && count($customer_groups->rows) > 0) {
    foreach ($customer_groups->rows as $cg) {
        if (isset($cg['customer_group_id'])) {
            $test_data['product_discount'][] = array(
                'customer_group_id' => (int)$cg['customer_group_id'],
                'quantity' => 10,
                'priority' => 1,
                'price' => 150.00,
                'date_start' => date('Y-m-d'),
                'date_end' => date('Y-m-d', strtotime('+1 year'))
            );
        }
    }
}

// Populate specials
if ($customer_groups && isset($customer_groups->rows) && is_array($customer_groups->rows) && count($customer_groups->rows) > 0) {
    foreach ($customer_groups->rows as $cg) {
        if (isset($cg['customer_group_id'])) {
            $test_data['product_special'][] = array(
                'customer_group_id' => (int)$cg['customer_group_id'],
                'priority' => 1,
                'price' => 179.99,
                'date_start' => date('Y-m-d'),
                'date_end' => date('Y-m-d', strtotime('+1 month'))
            );
        }
    }
}

// Populate reward points
if ($customer_groups && isset($customer_groups->rows) && is_array($customer_groups->rows) && count($customer_groups->rows) > 0) {
    foreach ($customer_groups->rows as $cg) {
        if (isset($cg['customer_group_id'])) {
            $test_data['product_reward'][(int)$cg['customer_group_id']] = 100;
        }
    }
}

// Populate additional images
$test_data['product_image'] = array(
    array('image' => 'catalog/demo/htc_touch_hd_1.jpg', 'sort_order' => 0),
    array('image' => 'catalog/demo/htc_touch_hd_2.jpg', 'sort_order' => 1),
    array('image' => 'catalog/demo/htc_touch_hd_3.jpg', 'sort_order' => 2)
);

// Populate layouts
if ($layouts && isset($layouts->rows) && is_array($layouts->rows) && count($layouts->rows) > 0) {
    foreach ($layouts->rows as $layout) {
        if (isset($layout['layout_id'])) {
            $test_data['product_layout'][0] = (int)$layout['layout_id'];
            break; // Just use first layout for store 0
        }
    }
}

// Populate options (simple test)
$test_data['product_option'] = array(
    array(
        'option_id' => 1,
        'required' => 1,
        'product_option_value' => array(
            array(
                'option_value_id' => 1,
                'quantity' => 10,
                'subtract' => 1,
                'price' => 10.00,
                'price_prefix' => '+',
                'points' => 0,
                'points_prefix' => '+',
                'weight' => 0,
                'weight_prefix' => '+'
            )
        )
    )
);

echo "<h3>📋 Test Data Summary</h3>";
echo "<table>";
echo "<tr><th>Tab</th><th>Field</th><th>Count/Value</th><th>Status</th></tr>";
echo "<tr><td>General</td><td>Model</td><td>" . $test_data['model'] . "</td><td class='status-ok'>✓</td></tr>";
echo "<tr><td>Data</td><td>Price</td><td>" . $test_data['price'] . "</td><td class='status-ok'>✓</td></tr>";
echo "<tr><td>Data</td><td>Manufacturer</td><td>" . ($test_data['manufacturer_id'] > 0 ? 'ID: ' . $test_data['manufacturer_id'] : 'None') . "</td><td class='" . ($test_data['manufacturer_id'] > 0 ? 'status-ok' : 'status-missing') . "'>" . ($test_data['manufacturer_id'] > 0 ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Data</td><td>Parent</td><td>" . ($test_data['parent_id'] > 0 ? 'ID: ' . $test_data['parent_id'] : 'None') . "</td><td class='" . ($test_data['parent_id'] > 0 ? 'status-ok' : 'status-missing') . "'>" . ($test_data['parent_id'] > 0 ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Image</td><td>Main Image</td><td>" . ($test_data['image'] ?: 'None') . "</td><td class='" . ($test_data['image'] ? 'status-ok' : 'status-missing') . "'>" . ($test_data['image'] ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Image</td><td>Featured Image</td><td>" . ($test_data['featured_image'] ?: 'None') . "</td><td class='" . ($test_data['featured_image'] ? 'status-ok' : 'status-missing') . "'>" . ($test_data['featured_image'] ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Image</td><td>Additional Images</td><td>" . count($test_data['product_image']) . "</td><td class='status-ok'>✓</td></tr>";
echo "<tr><td>Links</td><td>Categories</td><td>" . count($test_data['product_category']) . "</td><td class='" . (count($test_data['product_category']) > 0 ? 'status-ok' : 'status-missing') . "'>" . (count($test_data['product_category']) > 0 ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Links</td><td>Downloads</td><td>" . count($test_data['product_download']) . "</td><td class='" . (count($test_data['product_download']) > 0 ? 'status-ok' : 'status-missing') . "'>" . (count($test_data['product_download']) > 0 ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Links</td><td>Related</td><td>" . count($test_data['product_related']) . "</td><td class='" . (count($test_data['product_related']) > 0 ? 'status-ok' : 'status-missing') . "'>" . (count($test_data['product_related']) > 0 ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Links</td><td>Compatible</td><td>" . count($test_data['product_compatible']) . "</td><td class='" . (count($test_data['product_compatible']) > 0 ? 'status-ok' : 'status-missing') . "'>" . (count($test_data['product_compatible']) > 0 ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Filter</td><td>Filters</td><td>" . count($test_data['product_filter']) . "</td><td class='" . (count($test_data['product_filter']) > 0 ? 'status-ok' : 'status-missing') . "'>" . (count($test_data['product_filter']) > 0 ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Attribute</td><td>Attributes</td><td>" . count($test_data['product_attribute']) . "</td><td class='" . (count($test_data['product_attribute']) > 0 ? 'status-ok' : 'status-missing') . "'>" . (count($test_data['product_attribute']) > 0 ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Option</td><td>Options</td><td>" . count($test_data['product_option']) . "</td><td class='status-ok'>✓</td></tr>";
echo "<tr><td>Discount</td><td>Discounts</td><td>" . count($test_data['product_discount']) . "</td><td class='" . (count($test_data['product_discount']) > 0 ? 'status-ok' : 'status-missing') . "'>" . (count($test_data['product_discount']) > 0 ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Special</td><td>Specials</td><td>" . count($test_data['product_special']) . "</td><td class='" . (count($test_data['product_special']) > 0 ? 'status-ok' : 'status-missing') . "'>" . (count($test_data['product_special']) > 0 ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Reward Points</td><td>Rewards</td><td>" . count($test_data['product_reward']) . "</td><td class='" . (count($test_data['product_reward']) > 0 ? 'status-ok' : 'status-missing') . "'>" . (count($test_data['product_reward']) > 0 ? '✓' : '⚠') . "</td></tr>";
echo "<tr><td>Design</td><td>Layouts</td><td>" . count($test_data['product_layout']) . "</td><td class='" . (count($test_data['product_layout']) > 0 ? 'status-ok' : 'status-missing') . "'>" . (count($test_data['product_layout']) > 0 ? '✓' : '⚠') . "</td></tr>";
echo "</table>";

echo "<h3>🚀 Running Test...</h3>";

try {
    echo "<p>Attempting to insert test product with all tabs data...</p>";
    $product_id = $model->addProduct($test_data);
    
    if ($product_id && $product_id > 0) {
        echo "<p class='success'>✓ Product inserted successfully! Product ID: <strong>$product_id</strong></p>";
        
        // Verify it exists
        $verify = $db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
        if ($verify && $verify->num_rows) {
            $product = $verify->row;
            echo "<p class='success'>✓ Product verified in database</p>";
            
            // Test each tab
            echo "<h3>📊 Verification Results by Tab</h3>";
            
            // General/Data Tab
            echo "<div class='tab-section'><h4>General/Data Tab</h4>";
            echo "<table>";
            echo "<tr><th>Field</th><th>Expected</th><th>Actual</th><th>Status</th></tr>";
            echo "<tr><td>Model</td><td>" . $test_data['model'] . "</td><td>" . $product['model'] . "</td><td class='" . ($product['model'] == $test_data['model'] ? 'status-ok' : 'status-fail') . "'>" . ($product['model'] == $test_data['model'] ? '✓' : '✗') . "</td></tr>";
            echo "<tr><td>Price</td><td>" . $test_data['price'] . "</td><td>" . $product['price'] . "</td><td class='" . (abs($product['price'] - $test_data['price']) < 0.01 ? 'status-ok' : 'status-fail') . "'>" . (abs($product['price'] - $test_data['price']) < 0.01 ? '✓' : '✗') . "</td></tr>";
echo "<tr><td>Manufacturer ID</td><td>" . $test_data['manufacturer_id'] . "</td><td>" . (isset($product['manufacturer_id']) ? $product['manufacturer_id'] : 'N/A') . "</td><td class='" . (isset($product['manufacturer_id']) && $product['manufacturer_id'] == $test_data['manufacturer_id'] ? 'status-ok' : 'status-fail') . "'>" . (isset($product['manufacturer_id']) && $product['manufacturer_id'] == $test_data['manufacturer_id'] ? '✓' : '✗') . "</td></tr>";
echo "<tr><td>Parent ID</td><td>" . $test_data['parent_id'] . "</td><td>" . (isset($product['parent_id']) ? $product['parent_id'] : 'N/A') . "</td><td class='" . (isset($product['parent_id']) && $product['parent_id'] == $test_data['parent_id'] ? 'status-ok' : 'status-fail') . "'>" . (isset($product['parent_id']) && $product['parent_id'] == $test_data['parent_id'] ? '✓' : '✗') . "</td></tr>";
            echo "<tr><td>Main Image</td><td>" . ($test_data['image'] ?: 'None') . "</td><td>" . ($product['image'] ?: 'None') . "</td><td class='" . ($product['image'] == $test_data['image'] ? 'status-ok' : 'status-fail') . "'>" . ($product['image'] == $test_data['image'] ? '✓' : '✗') . "</td></tr>";
            echo "<tr><td>Featured Image</td><td>" . ($test_data['featured_image'] ?: 'None') . "</td><td>" . ($product['featured_image'] ?: 'None') . "</td><td class='" . ($product['featured_image'] == $test_data['featured_image'] ? 'status-ok' : 'status-fail') . "'>" . ($product['featured_image'] == $test_data['featured_image'] ? '✓' : '✗') . "</td></tr>";
            echo "</table></div>";
            
            // Links Tab
            echo "<div class='tab-section'><h4>Links Tab</h4>";
            $categories_db = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
            $downloads_db = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
            $related_db = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
            $compatible_db = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_compatible WHERE product_id = '" . (int)$product_id . "'");
            
            echo "<table>";
            echo "<tr><th>Field</th><th>Expected</th><th>Actual</th><th>Status</th></tr>";
            echo "<tr><td>Categories</td><td>" . count($test_data['product_category']) . "</td><td>" . ($categories_db->row['count'] ?? 0) . "</td><td class='" . (($categories_db->row['count'] ?? 0) == count($test_data['product_category']) ? 'status-ok' : 'status-fail') . "'>" . (($categories_db->row['count'] ?? 0) == count($test_data['product_category']) ? '✓' : '✗') . "</td></tr>";
            echo "<tr><td>Downloads</td><td>" . count($test_data['product_download']) . "</td><td>" . ($downloads_db->row['count'] ?? 0) . "</td><td class='" . (($downloads_db->row['count'] ?? 0) == count($test_data['product_download']) ? 'status-ok' : 'status-fail') . "'>" . (($downloads_db->row['count'] ?? 0) == count($test_data['product_download']) ? '✓' : '✗') . "</td></tr>";
            echo "<tr><td>Related Products</td><td>" . count($test_data['product_related']) . "</td><td>" . ($related_db->row['count'] ?? 0) . "</td><td class='" . (($related_db->row['count'] ?? 0) == count($test_data['product_related']) ? 'status-ok' : 'status-fail') . "'>" . (($related_db->row['count'] ?? 0) == count($test_data['product_related']) ? '✓' : '✗') . "</td></tr>";
            echo "<tr><td>Compatible Products</td><td>" . count($test_data['product_compatible']) . "</td><td>" . ($compatible_db->row['count'] ?? 0) . "</td><td class='" . (($compatible_db->row['count'] ?? 0) == count($test_data['product_compatible']) ? 'status-ok' : 'status-fail') . "'>" . (($compatible_db->row['count'] ?? 0) == count($test_data['product_compatible']) ? '✓' : '✗') . "</td></tr>";
            echo "</table></div>";
            
            // Filter Tab
            echo "<div class='tab-section'><h4>Filter Tab</h4>";
            $filters_db = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
            $filter_list = $db->query("SELECT filter_id FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
            echo "<table>";
            echo "<tr><th>Field</th><th>Expected</th><th>Actual</th><th>Status</th></tr>";
            echo "<tr><td>Filters</td><td>" . count($test_data['product_filter']) . "</td><td>" . ($filters_db->row['count'] ?? 0) . "</td><td class='" . (($filters_db->row['count'] ?? 0) == count($test_data['product_filter']) ? 'status-ok' : 'status-fail') . "'>" . (($filters_db->row['count'] ?? 0) == count($test_data['product_filter']) ? '✓' : '✗') . "</td></tr>";
            echo "</table>";
            if ($filter_list && $filter_list->num_rows > 0) {
                echo "<p>Filter IDs saved: " . implode(', ', array_column($filter_list->rows, 'filter_id')) . "</p>";
            }
            echo "</div>";
            
            // Attribute Tab
            echo "<div class='tab-section'><h4>Attribute Tab</h4>";
            $attributes_db = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
            echo "<table>";
            echo "<tr><th>Field</th><th>Expected</th><th>Actual</th><th>Status</th></tr>";
            echo "<tr><td>Attributes</td><td>" . count($test_data['product_attribute']) . "</td><td>" . ($attributes_db->row['count'] ?? 0) . "</td><td class='" . (($attributes_db->row['count'] ?? 0) >= count($test_data['product_attribute']) ? 'status-ok' : 'status-fail') . "'>" . (($attributes_db->row['count'] ?? 0) >= count($test_data['product_attribute']) ? '✓' : '✗') . "</td></tr>";
            echo "</table></div>";
            
            // Option Tab
            echo "<div class='tab-section'><h4>Option Tab</h4>";
            $options_db = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
            echo "<table>";
            echo "<tr><th>Field</th><th>Expected</th><th>Actual</th><th>Status</th></tr>";
            echo "<tr><td>Options</td><td>" . count($test_data['product_option']) . "</td><td>" . ($options_db->row['count'] ?? 0) . "</td><td class='" . (($options_db->row['count'] ?? 0) == count($test_data['product_option']) ? 'status-ok' : 'status-fail') . "'>" . (($options_db->row['count'] ?? 0) == count($test_data['product_option']) ? '✓' : '✗') . "</td></tr>";
            echo "</table></div>";
            
            // Discount Tab
            echo "<div class='tab-section'><h4>Discount Tab</h4>";
            $discounts_db = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
            echo "<table>";
            echo "<tr><th>Field</th><th>Expected</th><th>Actual</th><th>Status</th></tr>";
            echo "<tr><td>Discounts</td><td>" . count($test_data['product_discount']) . "</td><td>" . ($discounts_db->row['count'] ?? 0) . "</td><td class='" . (($discounts_db->row['count'] ?? 0) == count($test_data['product_discount']) ? 'status-ok' : 'status-fail') . "'>" . (($discounts_db->row['count'] ?? 0) == count($test_data['product_discount']) ? '✓' : '✗') . "</td></tr>";
            echo "</table></div>";
            
            // Special Tab
            echo "<div class='tab-section'><h4>Special Tab</h4>";
            $specials_db = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
            echo "<table>";
            echo "<tr><th>Field</th><th>Expected</th><th>Actual</th><th>Status</th></tr>";
            echo "<tr><td>Specials</td><td>" . count($test_data['product_special']) . "</td><td>" . ($specials_db->row['count'] ?? 0) . "</td><td class='" . (($specials_db->row['count'] ?? 0) == count($test_data['product_special']) ? 'status-ok' : 'status-fail') . "'>" . (($specials_db->row['count'] ?? 0) == count($test_data['product_special']) ? '✓' : '✗') . "</td></tr>";
            echo "</table></div>";
            
            // Image Tab
            echo "<div class='tab-section'><h4>Image Tab</h4>";
            $images_db = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
            echo "<table>";
            echo "<tr><th>Field</th><th>Expected</th><th>Actual</th><th>Status</th></tr>";
            echo "<tr><td>Additional Images</td><td>" . count($test_data['product_image']) . "</td><td>" . ($images_db->row['count'] ?? 0) . "</td><td class='" . (($images_db->row['count'] ?? 0) == count($test_data['product_image']) ? 'status-ok' : 'status-fail') . "'>" . (($images_db->row['count'] ?? 0) == count($test_data['product_image']) ? '✓' : '✗') . "</td></tr>";
            echo "</table></div>";
            
            // Reward Points Tab
            echo "<div class='tab-section'><h4>Reward Points Tab</h4>";
            $rewards_db = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
            echo "<table>";
            echo "<tr><th>Field</th><th>Expected</th><th>Actual</th><th>Status</th></tr>";
            echo "<tr><td>Rewards</td><td>" . count($test_data['product_reward']) . "</td><td>" . ($rewards_db->row['count'] ?? 0) . "</td><td class='" . (($rewards_db->row['count'] ?? 0) == count($test_data['product_reward']) ? 'status-ok' : 'status-fail') . "'>" . (($rewards_db->row['count'] ?? 0) == count($test_data['product_reward']) ? '✓' : '✗') . "</td></tr>";
            echo "</table></div>";
            
            // Design Tab
            echo "<div class='tab-section'><h4>Design Tab</h4>";
            $layouts_db = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
            echo "<table>";
            echo "<tr><th>Field</th><th>Expected</th><th>Actual</th><th>Status</th></tr>";
            echo "<tr><td>Layouts</td><td>" . count($test_data['product_layout']) . "</td><td>" . ($layouts_db->row['count'] ?? 0) . "</td><td class='" . (($layouts_db->row['count'] ?? 0) == count($test_data['product_layout']) ? 'status-ok' : 'status-fail') . "'>" . (($layouts_db->row['count'] ?? 0) == count($test_data['product_layout']) ? '✓' : '✗') . "</td></tr>";
            echo "</table></div>";
            
        } else {
            echo "<p class='error'>✗ Product ID returned but product not found in database!</p>";
        }
        
        // Show log file
        echo "<h3>📝 Recent Log Entries</h3>";
        $log_file = DIR_LOGS . 'product_insert_debug.log';
        if (file_exists($log_file)) {
            $log_content = file_get_contents($log_file);
            $log_lines = explode("\n", $log_content);
            $recent_lines = array_slice($log_lines, -100); // Last 100 lines
            echo "<pre>" . htmlspecialchars(implode("\n", $recent_lines)) . "</pre>";
        } else {
            echo "<p class='info'>Log file not found: $log_file</p>";
        }
        
        // Clean up test product
        echo "<h3>🧹 Cleanup</h3>";
        $db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product_compatible WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
        $db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
        echo "<p class='success'>✓ Test product cleaned up</p>";
        
    } else {
        echo "<p class='error'>✗ Product insertion failed! Product ID returned: " . ($product_id ? $product_id : 'NULL') . "</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Exception occurred: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</div></body></html>";

// End output buffering and output filtered content
ob_end_flush();
?>

