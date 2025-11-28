<?php
// Verify Product Data Integrity Script
// Checks if all product data is properly saved

define('HTTP_SERVER', 'http://localhost/');
define('DIR_APPLICATION', dirname(__FILE__) . '/../');
define('DIR_SYSTEM', DIR_APPLICATION . 'system/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_LOGS', DIR_SYSTEM . 'logs/');

require_once(DIR_SYSTEM . 'startup.php');

$registry = new Registry();
$config = new Config();
$registry->set('config', $config);

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if (!$product_id) {
    die("Please provide product_id parameter: ?product_id=123");
}

echo "<!DOCTYPE html><html><head><title>Product Data Verification</title>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
h2 { color: #666; margin-top: 20px; }
.success { color: #4CAF50; }
.error { color: #f44336; }
.warning { color: #ff9800; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
th { background: #4CAF50; color: white; }
</style></head><body>";
echo "<div class='container'>";
echo "<h1>Product Data Verification - Product ID: $product_id</h1>";

// Check main product
$product = $db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
if (!$product || !$product->num_rows) {
    die("<div class='error'>Product not found!</div></div></body></html>");
}

$product_data = $product->row;
echo "<h2>Main Product Data</h2>";
echo "<table>";
echo "<tr><th>Field</th><th>Value</th></tr>";
foreach ($product_data as $key => $value) {
    if (!is_numeric($key)) {
        echo "<tr><td>$key</td><td>" . htmlspecialchars(substr($value, 0, 100)) . "</td></tr>";
    }
}
echo "</table>";

// Check descriptions
echo "<h2>Product Descriptions</h2>";
$descriptions = $db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
if ($descriptions && $descriptions->num_rows) {
    echo "<div class='success'>Found " . $descriptions->num_rows . " description(s)</div>";
    echo "<table>";
    echo "<tr><th>Language ID</th><th>Name</th><th>Sub Name</th><th>Short Description</th></tr>";
    foreach ($descriptions->rows as $desc) {
        echo "<tr>";
        echo "<td>" . $desc['language_id'] . "</td>";
        echo "<td>" . htmlspecialchars($desc['name']) . "</td>";
        echo "<td>" . htmlspecialchars($desc['sub_name']) . "</td>";
        echo "<td>" . htmlspecialchars(substr($desc['short_description'], 0, 50)) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='error'>No descriptions found!</div>";
}

// Check filters
echo "<h2>Product Filters</h2>";
$filters = $db->query("
    SELECT pf.*, fd.name as filter_name, fgd.name as group_name
    FROM " . DB_PREFIX . "product_filter pf
    LEFT JOIN " . DB_PREFIX . "filter_description fd ON pf.filter_id = fd.filter_id
    LEFT JOIN " . DB_PREFIX . "filter f ON pf.filter_id = f.filter_id
    LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON f.filter_group_id = fgd.filter_group_id
    WHERE pf.product_id = '" . (int)$product_id . "'
    AND fd.language_id = '" . (int)$config->get('config_language_id') . "'
    AND fgd.language_id = '" . (int)$config->get('config_language_id') . "'
");
if ($filters && $filters->num_rows) {
    echo "<div class='success'>Found " . $filters->num_rows . " filter(s)</div>";
    echo "<table>";
    echo "<tr><th>Filter ID</th><th>Group</th><th>Filter Name</th></tr>";
    foreach ($filters->rows as $filter) {
        echo "<tr>";
        echo "<td>" . $filter['filter_id'] . "</td>";
        echo "<td>" . htmlspecialchars($filter['group_name']) . "</td>";
        echo "<td>" . htmlspecialchars($filter['filter_name']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='warning'>No filters found</div>";
}

// Check attributes
echo "<h2>Product Attributes</h2>";
$attributes = $db->query("
    SELECT pa.*, ad.name as attribute_name
    FROM " . DB_PREFIX . "product_attribute pa
    LEFT JOIN " . DB_PREFIX . "attribute_description ad ON pa.attribute_id = ad.attribute_id
    WHERE pa.product_id = '" . (int)$product_id . "'
    AND ad.language_id = '" . (int)$config->get('config_language_id') . "'
    ORDER BY pa.attribute_id, pa.language_id
");
if ($attributes && $attributes->num_rows) {
    echo "<div class='success'>Found " . $attributes->num_rows . " attribute record(s)</div>";
    echo "<table>";
    echo "<tr><th>Attribute ID</th><th>Attribute Name</th><th>Language ID</th><th>Text</th></tr>";
    foreach ($attributes->rows as $attr) {
        echo "<tr>";
        echo "<td>" . $attr['attribute_id'] . "</td>";
        echo "<td>" . htmlspecialchars($attr['attribute_name']) . "</td>";
        echo "<td>" . $attr['language_id'] . "</td>";
        echo "<td>" . htmlspecialchars(substr($attr['text'], 0, 100)) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='warning'>No attributes found</div>";
}

// Check categories
echo "<h2>Product Categories</h2>";
$categories = $db->query("
    SELECT ptc.*, cd.name as category_name
    FROM " . DB_PREFIX . "product_to_category ptc
    LEFT JOIN " . DB_PREFIX . "category_description cd ON ptc.category_id = cd.category_id
    WHERE ptc.product_id = '" . (int)$product_id . "'
    AND cd.language_id = '" . (int)$config->get('config_language_id') . "'
");
if ($categories && $categories->num_rows) {
    echo "<div class='success'>Found " . $categories->num_rows . " category link(s)</div>";
    echo "<table>";
    echo "<tr><th>Category ID</th><th>Category Name</th></tr>";
    foreach ($categories->rows as $cat) {
        echo "<tr>";
        echo "<td>" . $cat['category_id'] . "</td>";
        echo "<td>" . htmlspecialchars($cat['category_name']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='warning'>No categories found</div>";
}

// Check images
echo "<h2>Product Images</h2>";
$images = $db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order");
if ($images && $images->num_rows) {
    echo "<div class='success'>Found " . $images->num_rows . " image(s)</div>";
    echo "<table>";
    echo "<tr><th>Image ID</th><th>Image Path</th><th>Sort Order</th></tr>";
    foreach ($images->rows as $img) {
        echo "<tr>";
        echo "<td>" . $img['product_image_id'] . "</td>";
        echo "<td>" . htmlspecialchars($img['image']) . "</td>";
        echo "<td>" . $img['sort_order'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='warning'>No images found</div>";
}

// Check SEO keyword
echo "<h2>SEO Keyword</h2>";
$keyword = $db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
if ($keyword && $keyword->num_rows) {
    echo "<div class='success'>SEO keyword found</div>";
    echo "<table>";
    echo "<tr><th>Keyword</th><th>Query</th></tr>";
    foreach ($keyword->rows as $kw) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($kw['keyword']) . "</td>";
        echo "<td>" . htmlspecialchars($kw['query']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='warning'>No SEO keyword found</div>";
}

echo "</div></body></html>";
?>

