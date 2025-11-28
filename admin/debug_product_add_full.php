<?php
// Comprehensive Product Add Debug Script
// Tests all input fields and database insertion

error_reporting(E_ALL);
ini_set('display_errors', 1);

// OpenCart bootstrap - load config first
if (!file_exists(__DIR__ . '/config.php')) {
    die("Error: config.php not found in " . __DIR__);
}

require_once(__DIR__ . '/config.php');

if (!defined('DIR_SYSTEM')) {
    die("Error: DIR_SYSTEM not defined after loading config.php");
}

if (!file_exists(DIR_SYSTEM . 'startup.php')) {
    die("Error: startup.php not found at " . DIR_SYSTEM . 'startup.php');
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
// Load config files
try {
    if (file_exists(DIR_SYSTEM . 'config/default.php')) {
        $config->load('default');
    }
} catch (Exception $e) {
    // Ignore
}

try {
    if (file_exists(DIR_SYSTEM . 'config/admin.php')) {
        $config->load('admin');
    }
} catch (Exception $e) {
    // Ignore
}

$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Load settings from database
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
foreach ($query->rows as $setting) {
    if (!$setting['serialized']) {
        $config->set($setting['key'], $setting['value']);
    } else {
        $config->set($setting['key'], unserialize($setting['value']));
    }
}

// Load settings from database
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
foreach ($query->rows as $setting) {
    if (!$setting['serialized']) {
        $config->set($setting['key'], $setting['value']);
    } else {
        $config->set($setting['key'], unserialize($setting['value']));
    }
}

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Logging
$log = new Log('product_add_debug_full.log');
$registry->set('log', $log);

echo "<!DOCTYPE html><html><head><title>Product Add Full Debug</title>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
h2 { color: #666; margin-top: 30px; border-left: 4px solid #4CAF50; padding-left: 10px; }
.section { margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 3px; }
.success { color: #4CAF50; font-weight: bold; }
.error { color: #f44336; font-weight: bold; }
.warning { color: #ff9800; font-weight: bold; }
.info { color: #2196F3; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
th { background: #4CAF50; color: white; }
tr:nth-child(even) { background: #f9f9f9; }
.code { background: #f4f4f4; padding: 10px; border-radius: 3px; font-family: monospace; overflow-x: auto; }
.step { margin: 10px 0; padding: 10px; background: #e8f5e9; border-left: 3px solid #4CAF50; }
</style></head><body>";
echo "<div class='container'>";
echo "<h1>🔍 Product Add Functionality - Full Debug</h1>";

// Step 1: Database Connection Test
echo "<div class='section'>";
echo "<h2>Step 1: Database Connection</h2>";
try {
    $test_query = $db->query("SELECT 1");
    echo "<div class='success'>✓ Database connection successful</div>";
} catch (Exception $e) {
    echo "<div class='error'>✗ Database connection failed: " . $e->getMessage() . "</div>";
    exit;
}
echo "</div>";

// Step 2: Check Table Structure
echo "<div class='section'>";
echo "<h2>Step 2: Table Structure Verification</h2>";
$tables = array(
    'product',
    'product_description',
    'product_to_store',
    'product_to_category',
    'product_image',
    'product_filter',
    'product_attribute',
    'product_option',
    'product_discount',
    'product_special',
    'product_reward',
    'product_to_download',
    'product_to_layout',
    'product_related',
    'product_compatible',
    'url_alias'
);

$table_status = array();
foreach ($tables as $table) {
    $check = $db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table . "'");
    $exists = $check && $check->num_rows > 0;
    $table_status[$table] = $exists;
    echo $exists ? "<div class='success'>✓ Table: " . DB_PREFIX . $table . "</div>" : "<div class='error'>✗ Table missing: " . DB_PREFIX . $table . "</div>";
}
echo "</div>";

// Step 3: Check for product_id = 0 records
echo "<div class='section'>";
echo "<h2>Step 3: Cleanup Check (product_id = 0)</h2>";
$zero_check_tables = array(
    'product',
    'product_description',
    'product_to_store',
    'product_to_category',
    'product_image',
    'product_filter',
    'product_attribute'
);

$zero_counts = array();
foreach ($zero_check_tables as $table) {
    if (isset($table_status[$table]) && $table_status[$table]) {
        try {
            $check = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . $table . " WHERE product_id = 0");
            $count = $check && $check->num_rows ? (int)$check->row['count'] : 0;
            $zero_counts[$table] = $count;
            if ($count > 0) {
                echo "<div class='warning'>⚠ Found $count record(s) with product_id = 0 in " . DB_PREFIX . $table . "</div>";
            } else {
                echo "<div class='success'>✓ No product_id = 0 records in " . DB_PREFIX . $table . "</div>";
            }
        } catch (Exception $e) {
            echo "<div class='error'>✗ Error checking " . DB_PREFIX . $table . ": " . $e->getMessage() . "</div>";
        }
    }
}
echo "</div>";

// Step 4: Load Model
echo "<div class='section'>";
echo "<h2>Step 4: Load Product Model</h2>";
try {
    // Use Loader to properly load the model
    $loader = new Loader($registry);
    $registry->set('load', $loader);
    
    $loader->model('catalog/product');
    $model = $registry->get('model_catalog_product');
    
    if (!$model) {
        throw new Exception("Model not found in registry");
    }
    
    echo "<div class='success'>✓ Product model loaded successfully</div>";
} catch (Exception $e) {
    echo "<div class='error'>✗ Failed to load model: " . $e->getMessage() . "</div>";
    echo "<div class='code'>" . nl2br(htmlspecialchars($e->getTraceAsString())) . "</div>";
    exit;
}
echo "</div>";

// Step 5: Prepare Test Data
echo "<div class='section'>";
echo "<h2>Step 5: Prepare Comprehensive Test Data</h2>";

// Get default language
$lang_query = $db->query("SELECT language_id FROM " . DB_PREFIX . "language WHERE code = '" . $db->escape($config->get('config_language')) . "' LIMIT 1");
$default_language_id = $lang_query && $lang_query->num_rows ? (int)$lang_query->row['language_id'] : 1;

// Get a category
$cat_query = $db->query("SELECT category_id FROM " . DB_PREFIX . "category LIMIT 1");
$test_category_id = $cat_query && $cat_query->num_rows ? (int)$cat_query->row['category_id'] : 0;

// Get filters
$filter_query = $db->query("SELECT filter_id FROM " . DB_PREFIX . "filter LIMIT 3");
$test_filters = array();
if ($filter_query && $filter_query->num_rows) {
    foreach ($filter_query->rows as $row) {
        $test_filters[] = (int)$row['filter_id'];
    }
}

// Get attributes
$attr_query = $db->query("SELECT attribute_id FROM " . DB_PREFIX . "attribute LIMIT 2");
$test_attributes = array();
if ($attr_query && $attr_query->num_rows) {
    foreach ($attr_query->rows as $row) {
        $test_attributes[(int)$row['attribute_id']] = array(
            'attribute_id' => (int)$row['attribute_id'],
            'product_attribute_description' => array(
                $default_language_id => array(
                    'text' => 'Test attribute text for attribute ' . (int)$row['attribute_id']
                )
            )
        );
    }
}

$test_data = array(
    'model' => 'TEST-DEBUG-' . time(),
    'sku' => 'SKU-DEBUG-' . time(),
    'mpn' => 'MPN-DEBUG-' . time(),
    'short_note' => 'Short note test',
    'quantity' => 100,
    'minimum' => 1,
    'maximum' => 10,
    'subtract' => 1,
    'stock_status_id' => 7,
    'date_available' => date('Y-m-d'),
    'manufacturer_id' => 0,
    'is_manufacturer_is_parent' => 0,
    'parent_id' => 0,
    'attribute_profile_id' => 0,
    'shipping' => 1,
    'emi' => 0,
    'cost_price' => 50.00,
    'price' => 100.00,
    'regular_price' => 120.00,
    'points' => 100,
    'weight' => 1.5,
    'weight_class_id' => 1,
    'length' => 10,
    'width' => 5,
    'height' => 3,
    'length_class_id' => 1,
    'status' => 1,
    'tax_class_id' => 0,
    'sort_order' => 0,
    'view' => 'grid',
    'image' => '',
    'featured_image' => '',
    'keyword' => 'test-product-debug-' . time(),
    'product_description' => array(
        $default_language_id => array(
            'name' => 'Test Product Debug ' . time(),
            'sub_name' => 'Sub Name Test',
            'description' => 'Full description test content',
            'short_description' => 'Short description test',
            'video_url' => 'https://www.youtube.com/watch?v=test',
            'tag' => 'test, debug, product',
            'meta_title' => 'Test Product Meta Title',
            'meta_description' => 'Test Product Meta Description',
            'meta_keyword' => 'test, product, meta'
        )
    ),
    'product_store' => array(0),
    'product_category' => $test_category_id > 0 ? array($test_category_id) : array(),
    'product_image' => array(),
    'product_filter' => $test_filters,
    'product_attribute' => $test_attributes,
    'product_option' => array(),
    'product_discount' => array(),
    'product_special' => array(),
    'product_reward' => array(),
    'product_download' => array(),
    'product_layout' => array(),
    'product_related' => array(),
    'product_compatible' => array()
);

echo "<div class='info'>Test data prepared with:</div>";
echo "<ul>";
echo "<li>Model: " . htmlspecialchars($test_data['model']) . "</li>";
echo "<li>SKU: " . htmlspecialchars($test_data['sku']) . "</li>";
echo "<li>Categories: " . count($test_data['product_category']) . "</li>";
echo "<li>Filters: " . count($test_data['product_filter']) . "</li>";
echo "<li>Attributes: " . count($test_data['product_attribute']) . "</li>";
echo "<li>Languages: " . count($test_data['product_description']) . "</li>";
echo "</ul>";
echo "</div>";

// Step 6: Execute addProduct
echo "<div class='section'>";
echo "<h2>Step 6: Execute addProduct()</h2>";
echo "<div class='step'>Calling addProduct() with test data...</div>";

try {
    $product_id = $model->addProduct($test_data);
    
    if ($product_id && $product_id > 0) {
        echo "<div class='success'>✓ Product added successfully! Product ID: $product_id</div>";
    } else {
        echo "<div class='error'>✗ addProduct() returned invalid product_id: " . var_export($product_id, true) . "</div>";
        exit;
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Exception during addProduct(): " . $e->getMessage() . "</div>";
    echo "<div class='code'>" . nl2br(htmlspecialchars($e->getTraceAsString())) . "</div>";
    exit;
}
echo "</div>";

// Step 7: Verify All Data in Database
echo "<div class='section'>";
echo "<h2>Step 7: Verify All Data in Database</h2>";

// 7.1 Main Product Table
echo "<h3>7.1 Main Product Table</h3>";
$product_check = $db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
if ($product_check && $product_check->num_rows) {
    $product_row = $product_check->row;
    echo "<table>";
    echo "<tr><th>Field</th><th>Expected</th><th>Actual</th><th>Status</th></tr>";
    
    $fields_to_check = array(
        'model' => $test_data['model'],
        'sku' => $test_data['sku'],
        'mpn' => $test_data['mpn'],
        'quantity' => $test_data['quantity'],
        'price' => $test_data['price'],
        'regular_price' => $test_data['regular_price'],
        'status' => $test_data['status']
    );
    
    foreach ($fields_to_check as $field => $expected) {
        $actual = isset($product_row[$field]) ? $product_row[$field] : 'NULL';
        $match = ($actual == $expected);
        echo "<tr>";
        echo "<td>$field</td>";
        echo "<td>" . htmlspecialchars(var_export($expected, true)) . "</td>";
        echo "<td>" . htmlspecialchars(var_export($actual, true)) . "</td>";
        echo "<td>" . ($match ? "<span class='success'>✓</span>" : "<span class='error'>✗</span>") . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='error'>✗ Product not found in database!</div>";
}

// 7.2 Product Description
echo "<h3>7.2 Product Description</h3>";
$desc_check = $db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
if ($desc_check && $desc_check->num_rows) {
    echo "<div class='success'>✓ Found " . $desc_check->num_rows . " description record(s)</div>";
    echo "<table>";
    echo "<tr><th>Language ID</th><th>Name</th><th>Sub Name</th><th>Description</th><th>Short Description</th></tr>";
    foreach ($desc_check->rows as $desc_row) {
        echo "<tr>";
        echo "<td>" . $desc_row['language_id'] . "</td>";
        echo "<td>" . htmlspecialchars(substr($desc_row['name'], 0, 50)) . "</td>";
        echo "<td>" . htmlspecialchars(substr($desc_row['sub_name'], 0, 30)) . "</td>";
        echo "<td>" . htmlspecialchars(substr($desc_row['description'], 0, 50)) . "</td>";
        echo "<td>" . htmlspecialchars(substr($desc_row['short_description'], 0, 50)) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='error'>✗ No product descriptions found!</div>";
}

// 7.3 Product to Store
echo "<h3>7.3 Product to Store</h3>";
$store_check = $db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
if ($store_check && $store_check->num_rows) {
    echo "<div class='success'>✓ Found " . $store_check->num_rows . " store link(s)</div>";
    echo "<table>";
    echo "<tr><th>Store ID</th></tr>";
    foreach ($store_check->rows as $store_row) {
        echo "<tr><td>" . $store_row['store_id'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<div class='error'>✗ No store links found!</div>";
}

// 7.4 Product Categories
echo "<h3>7.4 Product Categories</h3>";
$cat_check = $db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
if ($cat_check && $cat_check->num_rows) {
    echo "<div class='success'>✓ Found " . $cat_check->num_rows . " category link(s)</div>";
    echo "<table>";
    echo "<tr><th>Category ID</th></tr>";
    foreach ($cat_check->rows as $cat_row) {
        echo "<tr><td>" . $cat_row['category_id'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<div class='warning'>⚠ No category links found (may be expected if no categories available)</div>";
}

// 7.5 Product Filters
echo "<h3>7.5 Product Filters</h3>";
$filter_check = $db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
if ($filter_check && $filter_check->num_rows) {
    echo "<div class='success'>✓ Found " . $filter_check->num_rows . " filter link(s)</div>";
    echo "<table>";
    echo "<tr><th>Filter ID</th><th>Expected</th><th>Status</th></tr>";
    $saved_filters = array();
    foreach ($filter_check->rows as $filter_row) {
        $saved_filters[] = (int)$filter_row['filter_id'];
        $expected = in_array((int)$filter_row['filter_id'], $test_filters);
        echo "<tr>";
        echo "<td>" . $filter_row['filter_id'] . "</td>";
        echo "<td>" . ($expected ? "Yes" : "No") . "</td>";
        echo "<td>" . ($expected ? "<span class='success'>✓</span>" : "<span class='warning'>⚠</span>") . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Compare
    $missing = array_diff($test_filters, $saved_filters);
    $extra = array_diff($saved_filters, $test_filters);
    if (!empty($missing)) {
        echo "<div class='error'>✗ Missing filters: " . implode(', ', $missing) . "</div>";
    }
    if (!empty($extra)) {
        echo "<div class='warning'>⚠ Extra filters: " . implode(', ', $extra) . "</div>";
    }
    if (empty($missing) && empty($extra) && count($test_filters) == count($saved_filters)) {
        echo "<div class='success'>✓ All filters match perfectly!</div>";
    }
} else {
    if (!empty($test_filters)) {
        echo "<div class='error'>✗ No filter links found! Expected " . count($test_filters) . " filter(s)</div>";
    } else {
        echo "<div class='info'>No filters to check (test data had no filters)</div>";
    }
}

// 7.6 Product Attributes
echo "<h3>7.6 Product Attributes</h3>";
$attr_check = $db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
if ($attr_check && $attr_check->num_rows) {
    echo "<div class='success'>✓ Found " . $attr_check->num_rows . " attribute record(s)</div>";
    echo "<table>";
    echo "<tr><th>Attribute ID</th><th>Language ID</th><th>Text</th><th>Expected</th><th>Status</th></tr>";
    foreach ($attr_check->rows as $attr_row) {
        $attr_id = (int)$attr_row['attribute_id'];
        $lang_id = (int)$attr_row['language_id'];
        $expected_text = '';
        if (isset($test_attributes[$attr_id]['product_attribute_description'][$lang_id]['text'])) {
            $expected_text = $test_attributes[$attr_id]['product_attribute_description'][$lang_id]['text'];
        }
        $match = ($attr_row['text'] == $expected_text);
        echo "<tr>";
        echo "<td>$attr_id</td>";
        echo "<td>$lang_id</td>";
        echo "<td>" . htmlspecialchars(substr($attr_row['text'], 0, 50)) . "</td>";
        echo "<td>" . htmlspecialchars(substr($expected_text, 0, 50)) . "</td>";
        echo "<td>" . ($match ? "<span class='success'>✓</span>" : "<span class='error'>✗</span>") . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Count check
    $expected_attr_count = 0;
    foreach ($test_attributes as $attr) {
        $expected_attr_count += count($attr['product_attribute_description']);
    }
    if ($attr_check->num_rows == $expected_attr_count) {
        echo "<div class='success'>✓ Attribute count matches! Expected: $expected_attr_count, Found: " . $attr_check->num_rows . "</div>";
    } else {
        echo "<div class='error'>✗ Attribute count mismatch! Expected: $expected_attr_count, Found: " . $attr_check->num_rows . "</div>";
    }
} else {
    if (!empty($test_attributes)) {
        echo "<div class='error'>✗ No attribute records found! Expected " . count($test_attributes) . " attribute(s)</div>";
    } else {
        echo "<div class='info'>No attributes to check (test data had no attributes)</div>";
    }
}

// 7.7 SEO Keyword
echo "<h3>7.7 SEO Keyword (url_alias)</h3>";
$keyword_check = $db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
if ($keyword_check && $keyword_check->num_rows) {
    echo "<div class='success'>✓ SEO keyword found</div>";
    echo "<table>";
    echo "<tr><th>Keyword</th><th>Query</th></tr>";
    foreach ($keyword_check->rows as $keyword_row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($keyword_row['keyword']) . "</td>";
        echo "<td>" . htmlspecialchars($keyword_row['query']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='warning'>⚠ No SEO keyword found</div>";
}

// 7.8 Summary
echo "<h3>7.8 Summary</h3>";
$summary = array(
    'Main Product' => $product_check && $product_check->num_rows ? '✓' : '✗',
    'Description' => $desc_check && $desc_check->num_rows ? '✓' : '✗',
    'Store Links' => $store_check && $store_check->num_rows ? '✓' : '✗',
    'Category Links' => $cat_check && $cat_check->num_rows ? '✓' : '⚠',
    'Filter Links' => $filter_check && $filter_check->num_rows ? '✓' : (empty($test_filters) ? 'N/A' : '✗'),
    'Attribute Records' => $attr_check && $attr_check->num_rows ? '✓' : (empty($test_attributes) ? 'N/A' : '✗'),
    'SEO Keyword' => $keyword_check && $keyword_check->num_rows ? '✓' : '⚠'
);

echo "<table>";
echo "<tr><th>Component</th><th>Status</th></tr>";
foreach ($summary as $component => $status) {
    $class = ($status == '✓') ? 'success' : (($status == '⚠' || $status == 'N/A') ? 'warning' : 'error');
    echo "<tr><td>$component</td><td><span class='$class'>$status</span></td></tr>";
}
echo "</table>";

echo "</div>";

// Step 8: Check Log Files
echo "<div class='section'>";
echo "<h2>Step 8: Check Log Files</h2>";
$log_file = DIR_LOGS . 'product_insert_debug.log';
if (file_exists($log_file)) {
    $log_content = file_get_contents($log_file);
    $log_lines = explode("\n", $log_content);
    $recent_lines = array_slice($log_lines, -50); // Last 50 lines
    echo "<div class='info'>Recent log entries (last 50 lines):</div>";
    echo "<div class='code'>" . nl2br(htmlspecialchars(implode("\n", $recent_lines))) . "</div>";
} else {
    echo "<div class='warning'>⚠ Log file not found: $log_file</div>";
}
echo "</div>";

// Step 9: Cleanup Option
echo "<div class='section'>";
echo "<h2>Step 9: Cleanup</h2>";
echo "<div class='info'>Test product created with ID: <strong>$product_id</strong></div>";
echo "<div class='info'>To delete this test product, run:</div>";
echo "<div class='code'>DELETE FROM " . DB_PREFIX . "product WHERE product_id = $product_id;</div>";
echo "<div class='warning'>⚠ This will also delete all related records (descriptions, filters, attributes, etc.)</div>";
echo "</div>";

echo "</div></body></html>";
?>

