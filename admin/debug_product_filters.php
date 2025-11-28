<?php
// Comprehensive Product Filter Debug Script
// Tests filter saving and loading functionality

error_reporting(E_ALL);
ini_set('display_errors', 1);

// OpenCart bootstrap
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

require_once(DIR_SYSTEM . 'startup.php');

$registry = new Registry();
$config = new Config();

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

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Load settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
foreach ($query->rows as $setting) {
    if (!$setting['serialized']) {
        $config->set($setting['key'], $setting['value']);
    } else {
        $config->set($setting['key'], unserialize($setting['value']));
    }
}

$loader = new Loader($registry);
$registry->set('load', $loader);

$loader->model('catalog/product');
$loader->model('catalog/filter');
$model_product = $registry->get('model_catalog_product');
$model_filter = $registry->get('model_catalog_filter');

echo "<!DOCTYPE html><html><head><title>Product Filter Debug</title>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
h2 { color: #666; margin-top: 20px; border-left: 4px solid #4CAF50; padding-left: 10px; }
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
.test-section { margin: 15px 0; padding: 10px; background: #e8f5e9; border-left: 3px solid #4CAF50; }
</style></head><body>";
echo "<div class='container'>";
echo "<h1>🔍 Product Filter Debug - Complete Analysis</h1>";

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if (!$product_id) {
    // Show list of products with filters
    echo "<div class='section'>";
    echo "<h2>Select a Product to Debug</h2>";
    echo "<p>Add ?product_id=XXX to the URL to debug a specific product</p>";
    
    $products = $db->query("SELECT p.product_id, pd.name, p.model FROM " . DB_PREFIX . "product p 
        LEFT JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id 
        WHERE pd.language_id = '" . (int)$config->get('config_language_id') . "'
        ORDER BY p.product_id DESC LIMIT 20");
    
    if ($products && $products->num_rows) {
        echo "<table>";
        echo "<tr><th>Product ID</th><th>Name</th><th>Model</th><th>Filters</th><th>Action</th></tr>";
        foreach ($products->rows as $product) {
            $filter_count = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product['product_id'] . "'");
            $count = $filter_count && $filter_count->num_rows ? (int)$filter_count->row['count'] : 0;
            echo "<tr>";
            echo "<td>" . $product['product_id'] . "</td>";
            echo "<td>" . htmlspecialchars($product['name']) . "</td>";
            echo "<td>" . htmlspecialchars($product['model']) . "</td>";
            echo "<td>" . $count . " filter(s)</td>";
            echo "<td><a href='?product_id=" . $product['product_id'] . "'>Debug</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    echo "</div>";
    echo "</div></body></html>";
    exit;
}

// Get product info
$product = $db->query("SELECT p.*, pd.name FROM " . DB_PREFIX . "product p 
    LEFT JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id 
    WHERE p.product_id = '" . (int)$product_id . "' 
    AND pd.language_id = '" . (int)$config->get('config_language_id') . "' LIMIT 1");

if (!$product || !$product->num_rows) {
    die("<div class='error'>Product not found!</div></div></body></html>");
}

$product_data = $product->row;
echo "<div class='section'>";
echo "<h2>Product Information</h2>";
echo "<p><strong>Product ID:</strong> " . $product_id . "</p>";
echo "<p><strong>Name:</strong> " . htmlspecialchars($product_data['name']) . "</p>";
echo "<p><strong>Model:</strong> " . htmlspecialchars($product_data['model']) . "</p>";
echo "</div>";

// Step 1: Check what filters are saved in database
echo "<div class='section'>";
echo "<h2>Step 1: Filters Saved in Database</h2>";
$saved_filters_query = $db->query("SELECT pf.*, fd.name as filter_name, fgd.name as group_name
    FROM " . DB_PREFIX . "product_filter pf
    LEFT JOIN " . DB_PREFIX . "filter f ON pf.filter_id = f.filter_id
    LEFT JOIN " . DB_PREFIX . "filter_description fd ON pf.filter_id = fd.filter_id AND fd.language_id = '" . (int)$config->get('config_language_id') . "'
    LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$config->get('config_language_id') . "'
    WHERE pf.product_id = '" . (int)$product_id . "'
    ORDER BY fgd.name, fd.name");

if ($saved_filters_query && $saved_filters_query->num_rows) {
    echo "<div class='success'>✓ Found " . $saved_filters_query->num_rows . " saved filter(s)</div>";
    echo "<table>";
    echo "<tr><th>Filter ID</th><th>Filter Name</th><th>Group Name</th><th>Type</th></tr>";
    $saved_filter_ids = array();
    foreach ($saved_filters_query->rows as $row) {
        $filter_id = (int)$row['filter_id'];
        $saved_filter_ids[] = $filter_id;
        echo "<tr>";
        echo "<td>" . $filter_id . " (" . gettype($filter_id) . ")</td>";
        echo "<td>" . htmlspecialchars($row['filter_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['group_name']) . "</td>";
        echo "<td>" . gettype($filter_id) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<div class='info'>Saved Filter IDs (as array): " . print_r($saved_filter_ids, true) . "</div>";
} else {
    echo "<div class='warning'>⚠ No filters found in database for this product</div>";
    $saved_filter_ids = array();
}
echo "</div>";

// Step 2: Test getProductFilters() method
echo "<div class='section'>";
echo "<h2>Step 2: Test getProductFilters() Method</h2>";
try {
    $retrieved_filters = $model_product->getProductFilters($product_id);
    echo "<div class='info'>Method returned: " . print_r($retrieved_filters, true) . "</div>";
    echo "<div class='info'>Type: " . gettype($retrieved_filters) . "</div>";
    echo "<div class='info'>Count: " . count($retrieved_filters) . "</div>";
    
    if (is_array($retrieved_filters)) {
        echo "<table>";
        echo "<tr><th>Index</th><th>Value</th><th>Type</th><th>Matches Saved</th></tr>";
        foreach ($retrieved_filters as $index => $filter_id) {
            $is_saved = in_array((int)$filter_id, $saved_filter_ids, true);
            echo "<tr>";
            echo "<td>$index</td>";
            echo "<td>$filter_id</td>";
            echo "<td>" . gettype($filter_id) . "</td>";
            echo "<td>" . ($is_saved ? "<span class='success'>✓</span>" : "<span class='error'>✗</span>") . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Compare
        $retrieved_int = array_map('intval', $retrieved_filters);
        $saved_int = array_map('intval', $saved_filter_ids);
        sort($retrieved_int);
        sort($saved_int);
        
        if ($retrieved_int === $saved_int) {
            echo "<div class='success'>✓ Retrieved filters match saved filters perfectly!</div>";
        } else {
            echo "<div class='error'>✗ Mismatch detected!</div>";
            echo "<div class='code'>Saved: " . print_r($saved_int, true) . "<br>";
            echo "Retrieved: " . print_r($retrieved_int, true) . "</div>";
        }
    } else {
        echo "<div class='error'>✗ getProductFilters() did not return an array!</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Error calling getProductFilters(): " . $e->getMessage() . "</div>";
}
echo "</div>";

// Step 3: Test filter controller method
echo "<div class='section'>";
echo "<h2>Step 3: Test Filter Controller (Simulated)</h2>";

// Simulate what the controller does
$product_filters = $model_product->getProductFilters($product_id);
$product_filters_normalized = array_map('intval', $product_filters);
$product_filters_normalized = array_filter($product_filters_normalized, function($id) { return $id > 0; });
$product_filters_normalized = array_values($product_filters_normalized);

echo "<div class='info'>After normalization: " . print_r($product_filters_normalized, true) . "</div>";

// Get filter profiles for this product
$filter_profiles = $model_product->getProductFilterProfiles($product_id);
echo "<div class='info'>Filter Profiles: " . print_r($filter_profiles, true) . "</div>";

if (!empty($filter_profiles)) {
    $filters = $model_filter->getFiltersByProfiles($filter_profiles);
    echo "<div class='info'>Filters from profiles: " . count($filters) . " total</div>";
    
    // Simulate the checked state logic
    echo "<h3>Filter Checkbox States</h3>";
    echo "<table>";
    echo "<tr><th>Filter ID</th><th>Filter Name</th><th>Should Be Checked</th><th>Comparison Result</th></tr>";
    
    foreach ($filters as $filter) {
        if (!isset($filter['filter_id'])) {
            continue;
        }
        
        $current_filter_id = (int)$filter['filter_id'];
        $is_checked = in_array($current_filter_id, $product_filters_normalized, true);
        
        $filter_info = $model_filter->getFilter($current_filter_id);
        $filter_name = isset($filter_info['name']) ? $filter_info['name'] : 'Unknown';
        
        echo "<tr>";
        echo "<td>$current_filter_id</td>";
        echo "<td>" . htmlspecialchars($filter_name) . "</td>";
        echo "<td>" . ($is_checked ? "<span class='success'>YES</span>" : "<span class='warning'>NO</span>") . "</td>";
        echo "<td>";
        echo "Filter ID: $current_filter_id (type: " . gettype($current_filter_id) . ")<br>";
        echo "In array: " . (in_array($current_filter_id, $product_filters_normalized) ? "YES" : "NO") . "<br>";
        echo "Strict: " . (in_array($current_filter_id, $product_filters_normalized, true) ? "YES" : "NO");
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='warning'>⚠ No filter profiles assigned to this product</div>";
    echo "<div class='info'>Note: Filters are only shown if filter profiles are assigned</div>";
}
echo "</div>";

// Step 4: Test filter saving
echo "<div class='section'>";
echo "<h2>Step 4: Test Filter Saving</h2>";

if (isset($_POST['test_save_filters'])) {
    $test_filters = isset($_POST['test_filters']) ? $_POST['test_filters'] : array();
    echo "<div class='test-section'>";
    echo "<h3>Test Save Operation</h3>";
    echo "<div class='info'>Attempting to save filters: " . print_r($test_filters, true) . "</div>";
    
    // Normalize
    $test_filters = array_map('intval', $test_filters);
    $test_filters = array_filter($test_filters, function($id) { return $id > 0; });
    $test_filters = array_values($test_filters);
    
    echo "<div class='info'>After normalization: " . print_r($test_filters, true) . "</div>";
    
    // Save using persistProductFilters
    try {
        $reflection = new ReflectionClass($model_product);
        $method = $reflection->getMethod('persistProductFilters');
        $method->setAccessible(true);
        $method->invoke($model_product, $product_id, $test_filters, 'test');
        
        echo "<div class='success'>✓ Filters saved successfully</div>";
        
        // Verify
        $verify = $model_product->getProductFilters($product_id);
        $verify_normalized = array_map('intval', $verify);
        sort($verify_normalized);
        sort($test_filters);
        
        if ($verify_normalized === $test_filters) {
            echo "<div class='success'>✓ Verification passed! Saved filters match</div>";
        } else {
            echo "<div class='error'>✗ Verification failed!</div>";
            echo "<div class='code'>Expected: " . print_r($test_filters, true) . "<br>";
            echo "Got: " . print_r($verify_normalized, true) . "</div>";
        }
    } catch (Exception $e) {
        echo "<div class='error'>✗ Error saving filters: " . $e->getMessage() . "</div>";
    }
    echo "</div>";
}

// Show form to test saving
echo "<form method='post'>";
echo "<input type='hidden' name='test_save_filters' value='1'>";
echo "<h3>Test Filter Save</h3>";
echo "<p>Select filters to save (this will replace current filters):</p>";

// Get all available filters
$all_filters_query = $db->query("SELECT f.filter_id, fd.name, fgd.name as group_name
    FROM " . DB_PREFIX . "filter f
    LEFT JOIN " . DB_PREFIX . "filter_description fd ON f.filter_id = fd.filter_id AND fd.language_id = '" . (int)$config->get('config_language_id') . "'
    LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$config->get('config_language_id') . "'
    ORDER BY fgd.name, fd.name
    LIMIT 50");

if ($all_filters_query && $all_filters_query->num_rows) {
    $current_saved = $model_product->getProductFilters($product_id);
    $current_saved_int = array_map('intval', $current_saved);
    
    foreach ($all_filters_query->rows as $filter) {
        $filter_id = (int)$filter['filter_id'];
        $is_checked = in_array($filter_id, $current_saved_int, true);
        echo "<label style='display: block; margin: 5px 0;'>";
        echo "<input type='checkbox' name='test_filters[]' value='$filter_id' " . ($is_checked ? "checked" : "") . "> ";
        echo htmlspecialchars($filter['group_name']) . " - " . htmlspecialchars($filter['name']) . " (ID: $filter_id)";
        echo "</label>";
    }
    echo "<br><button type='submit' style='padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 3px; cursor: pointer;'>Test Save Filters</button>";
}
echo "</form>";
echo "</div>";

// Step 5: Check for issues
echo "<div class='section'>";
echo "<h2>Step 5: Issue Detection</h2>";

$issues = array();

// Check 1: Type consistency
$saved_types = array();
foreach ($saved_filter_ids as $id) {
    $saved_types[] = gettype($id);
}
if (count(array_unique($saved_types)) > 1) {
    $issues[] = "Type inconsistency in saved filter IDs: " . implode(', ', array_unique($saved_types));
}

// Check 2: Zero or negative IDs
$invalid_ids = array_filter($saved_filter_ids, function($id) { return $id <= 0; });
if (!empty($invalid_ids)) {
    $issues[] = "Invalid filter IDs found: " . implode(', ', $invalid_ids);
}

// Check 3: Duplicate IDs
$duplicates = array_diff_assoc($saved_filter_ids, array_unique($saved_filter_ids));
if (!empty($duplicates)) {
    $issues[] = "Duplicate filter IDs: " . implode(', ', $duplicates);
}

if (empty($issues)) {
    echo "<div class='success'>✓ No issues detected</div>";
} else {
    echo "<div class='error'>✗ Issues found:</div>";
    echo "<ul>";
    foreach ($issues as $issue) {
        echo "<li>" . htmlspecialchars($issue) . "</li>";
    }
    echo "</ul>";
}
echo "</div>";

// Step 6: Raw database check
echo "<div class='section'>";
echo "<h2>Step 6: Raw Database Check</h2>";
$raw_query = $db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
echo "<div class='code'>";
echo "Raw SQL: SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '$product_id'<br><br>";
if ($raw_query && $raw_query->num_rows) {
    echo "Rows returned: " . $raw_query->num_rows . "<br><br>";
    foreach ($raw_query->rows as $row) {
        echo "product_id: " . $row['product_id'] . " (type: " . gettype($row['product_id']) . ")<br>";
        echo "filter_id: " . $row['filter_id'] . " (type: " . gettype($row['filter_id']) . ")<br>";
        echo "---<br>";
    }
} else {
    echo "No rows found";
}
echo "</div>";
echo "</div>";

echo "</div></body></html>";
?>

