<?php
// Debug script to test product edit functionality
// Run this from: admin/debug_product_edit.php?product_id=1

// Load admin config
require_once(__DIR__ . '/config.php');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Settings
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

// Load product model using loader
$loader->model('catalog/product');
$model = $registry->get('model_catalog_product');

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if ($product_id <= 0) {
    die("Please provide a valid product_id. Usage: ?product_id=1");
}

echo "<h1>Product Edit Debug Report</h1>";
echo "<h2>Product ID: " . $product_id . "</h2>";

// Get product info
$product = $model->getProduct($product_id);
if (!$product) {
    die("Product not found!");
}

echo "<h3>Product: " . htmlspecialchars($product['name']) . "</h3>";

// Test all tabs
$tabs = array(
    'General' => array(
        'table' => 'product_description',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    ),
    'Data' => array(
        'table' => 'product',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
            return $query->num_rows > 0 ? 1 : 0;
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT model, sku, price, quantity, status FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    ),
    'Links - Categories' => array(
        'table' => 'product_to_category',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    ),
    'Links - Downloads' => array(
        'table' => 'product_to_download',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    ),
    'Links - Related' => array(
        'table' => 'product_related',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    ),
    'Links - Compatible' => array(
        'table' => 'product_compatible',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_compatible WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_compatible WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    ),
    'Attribute' => array(
        'table' => 'product_attribute',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    ),
    'Filter' => array(
        'table' => 'product_filter',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    ),
    'Option' => array(
        'table' => 'product_option',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    ),
    'Discount' => array(
        'table' => 'product_discount',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    ),
    'Special' => array(
        'table' => 'product_special',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    ),
    'Image' => array(
        'table' => 'product_image',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order");
            return $query->rows;
        }
    ),
    'Reward Points' => array(
        'table' => 'product_reward',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    ),
    'Design' => array(
        'table' => 'product_to_layout',
        'check' => function($db, $product_id) {
            $query = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
            return $query->row['count'];
        },
        'details' => function($db, $product_id) {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
            return $query->rows;
        }
    )
);

echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'><th>Tab</th><th>Table</th><th>Record Count</th><th>Status</th><th>Details</th></tr>";

foreach ($tabs as $tab_name => $tab_info) {
    $count = $tab_info['check']($db, $product_id);
    $status = $count > 0 ? '<span style="color: green;">✓ Has Data</span>' : '<span style="color: orange;">⚠ No Data</span>';
    
    $details = $tab_info['details']($db, $product_id);
    $details_html = '';
    if (count($details) > 0) {
        $details_html = '<details><summary>View ' . count($details) . ' record(s)</summary><pre style="max-height: 200px; overflow: auto;">';
        $details_html .= print_r($details, true);
        $details_html .= '</pre></details>';
    } else {
        $details_html = '<span style="color: #999;">No records</span>';
    }
    
    echo "<tr>";
    echo "<td><strong>" . htmlspecialchars($tab_name) . "</strong></td>";
    echo "<td>" . DB_PREFIX . htmlspecialchars($tab_info['table']) . "</td>";
    echo "<td>" . $count . "</td>";
    echo "<td>" . $status . "</td>";
    echo "<td>" . $details_html . "</td>";
    echo "</tr>";
}

echo "</table>";

// Check for product_id = 0 issues
echo "<h3>Database Health Check</h3>";
$zero_check = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product WHERE product_id = 0");
$zero_count = $zero_check->row['count'];
if ($zero_count > 0) {
    echo "<p style='color: red;'><strong>⚠ WARNING:</strong> Found " . $zero_count . " product(s) with product_id = 0. This can cause duplicate entry errors!</p>";
} else {
    echo "<p style='color: green;'><strong>✓ OK:</strong> No products with product_id = 0 found.</p>";
}

// Check related tables for product_id = 0
$related_tables = array(
    'product_description', 'product_to_store', 'product_to_category', 'product_image',
    'product_option', 'product_option_value', 'product_related', 'product_compatible',
    'product_reward', 'product_to_layout', 'product_discount', 'product_special',
    'product_to_download', 'product_filter', 'product_attribute'
);

echo "<h4>Checking for product_id = 0 in related tables:</h4>";
echo "<ul>";
foreach ($related_tables as $table) {
    try {
        $check = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . $table . " WHERE product_id = 0");
        $count = $check->row['count'];
        if ($count > 0) {
            echo "<li style='color: red;'><strong>" . $table . ":</strong> " . $count . " record(s) with product_id = 0</li>";
        } else {
            echo "<li style='color: green;'><strong>" . $table . ":</strong> OK</li>";
        }
    } catch (Exception $e) {
        echo "<li style='color: orange;'><strong>" . $table . ":</strong> Error checking - " . $e->getMessage() . "</li>";
    }
}
echo "</ul>";

// Show recent log entries
echo "<h3>Recent Log Entries</h3>";
$log_file = DIR_LOGS . 'product_insert_debug.log';
if (file_exists($log_file)) {
    $lines = file($log_file);
    $recent_lines = array_slice($lines, -50); // Last 50 lines
    echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 400px; overflow: auto; font-size: 11px;'>";
    echo htmlspecialchars(implode('', $recent_lines));
    echo "</pre>";
} else {
    echo "<p>Log file not found: " . $log_file . "</p>";
}

echo "<hr>";
echo "<p><strong>Usage:</strong> Add ?product_id=X to the URL to test a specific product.</p>";
echo "<p><strong>Example:</strong> " . $_SERVER['PHP_SELF'] . "?product_id=1</p>";

