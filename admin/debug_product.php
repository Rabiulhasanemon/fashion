<?php
// Standalone Product Debug Script
// Access: your-site/admin/debug_product.php?token=YOUR_TOKEN

// Start session and load OpenCart
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Session
$session = new Session();
$registry->set('session', $session);

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// User
$user = new User($registry);
$registry->set('user', $user);

// Check if user is logged in
if (!$user->isLogged()) {
    die('Access Denied. Please login to admin panel first.');
}

// Get product_id from request
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Debug Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        h2 { color: #007bff; margin-top: 30px; border-left: 4px solid #007bff; padding-left: 10px; }
        .section { background: #f8f9fa; padding: 15px; margin: 15px 0; border-radius: 5px; border: 1px solid #dee2e6; }
        .error { background: #fff5f5; border-color: #dc3545; }
        .success { background: #f0fff4; border-color: #28a745; }
        .warning { background: #fffbf0; border-color: #ffc107; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table th, table td { padding: 10px; text-align: left; border: 1px solid #dee2e6; }
        table th { background: #007bff; color: white; }
        table tr:nth-child(even) { background: #f8f9fa; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .btn-danger { background: #dc3545; }
        .btn-success { background: #28a745; }
        .btn:hover { opacity: 0.8; }
        pre { background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 12px; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-success { background: #28a745; color: white; }
        .badge-warning { background: #ffc107; color: #333; }
        form { margin: 10px 0; }
        input[type="number"] { padding: 8px; width: 150px; margin: 0 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Product Debug Tool</h1>
        
        <div class="section">
            <h3>Quick Actions</h3>
            <form method="get" style="display: inline-block;">
                <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">
                <label>Check Product ID:</label>
                <input type="number" name="product_id" placeholder="Enter Product ID" value="<?php echo $product_id; ?>">
                <button type="submit" class="btn">Check Product</button>
            </form>
            <a href="?token=<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>&action=cleanup" class="btn btn-danger" onclick="return confirm('Clean up all product_id = 0 records?');">Cleanup product_id = 0</a>
            <a href="?token=<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>" class="btn btn-success">Refresh</a>
        </div>

        <?php
        // Handle cleanup action
        if ($action == 'cleanup') {
            echo '<div class="section success">';
            echo '<h3>Cleanup Results</h3>';
            $cleaned = 0;
            $tables = array('product', 'product_description', 'product_to_store', 'product_to_category', 
                           'product_image', 'product_option', 'product_option_value', 'product_filter', 
                           'product_attribute', 'product_discount', 'product_special', 'product_reward', 
                           'product_related', 'product_compatible', 'product_to_layout', 'product_to_download');
            
            foreach ($tables as $table) {
                try {
                    $result = $db->query("DELETE FROM `" . DB_PREFIX . $table . "` WHERE product_id = 0");
                    if ($result) {
                        $cleaned++;
                        echo "<p>✓ Cleaned table: " . $table . " (product_id = 0)</p>";
                    }
                } catch (Exception $e) {
                    echo "<p>✗ Error cleaning " . $table . ": " . $e->getMessage() . "</p>";
                }
            }
            
            // Also clean up ID = 0 records in auto-increment tables
            $auto_inc_cleanup = array(
                'product_reward' => 'product_reward_id',
                'product_image' => 'product_image_id',
                'product_option' => 'product_option_id',
                'product_option_value' => 'product_option_value_id',
                'product_attribute' => 'product_attribute_id',
                'product_discount' => 'product_discount_id',
                'product_special' => 'product_special_id'
            );
            
            foreach ($auto_inc_cleanup as $table => $id_field) {
                try {
                    $result = $db->query("DELETE FROM `" . DB_PREFIX . $table . "` WHERE " . $id_field . " = 0");
                    if ($result) {
                        $cleaned++;
                        echo "<p>✓ Cleaned table: " . $table . " (" . $id_field . " = 0)</p>";
                    }
                } catch (Exception $e) {
                    // Table might not have this field
                }
            }
            
            try {
                $db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query = 'product_id=0'");
                echo "<p>✓ Cleaned url_alias</p>";
            } catch (Exception $e) {
                echo "<p>✗ Error cleaning url_alias: " . $e->getMessage() . "</p>";
            }
            
            echo "<p><strong>Total tables cleaned: " . $cleaned . "</strong></p>";
            echo '</div>';
        }
        ?>

        <!-- Check for ID = 0 records in auto-increment tables -->
        <div class="section">
            <h2>1. ID = 0 Records Check (Auto-increment Tables)</h2>
            <?php
            // Check tables with auto-increment IDs that might have ID = 0
            $auto_inc_tables = array(
                'product_reward' => 'product_reward_id',
                'product_image' => 'product_image_id',
                'product_option' => 'product_option_id',
                'product_option_value' => 'product_option_value_id',
                'product_attribute' => 'product_attribute_id',
                'product_discount' => 'product_discount_id',
                'product_special' => 'product_special_id'
            );
            
            $found_id_zero = false;
            echo '<h3>Auto-increment ID = 0 Records</h3>';
            echo '<table>';
            echo '<tr><th>Table</th><th>ID Field</th><th>Count</th><th>Status</th></tr>';
            
            foreach ($auto_inc_tables as $table => $id_field) {
                try {
                    $check = $db->query("SELECT COUNT(*) as count FROM `" . DB_PREFIX . $table . "` WHERE " . $id_field . " = 0");
                    if ($check && $check->num_rows) {
                        $count = (int)$check->row['count'];
                        if ($count > 0) {
                            $found_id_zero = true;
                            echo '<tr><td>' . $table . '</td><td>' . $id_field . '</td><td><span class="badge badge-danger">' . $count . '</span></td><td>⚠️ Found</td></tr>';
                        } else {
                            echo '<tr><td>' . $table . '</td><td>' . $id_field . '</td><td><span class="badge badge-success">0</span></td><td>✓ Clean</td></tr>';
                        }
                    }
                } catch (Exception $e) {
                    echo '<tr><td>' . $table . '</td><td>' . $id_field . '</td><td>-</td><td>❌ Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                }
            }
            echo '</table>';
            
            if ($found_id_zero) {
                echo '<div class="error" style="margin-top: 15px; padding: 10px;"><strong>⚠️ Warning:</strong> Found ID = 0 records in auto-increment tables! This can cause duplicate entry errors.</div>';
            } else {
                echo '<div class="success" style="margin-top: 15px; padding: 10px;"><strong>✓ Good:</strong> No ID = 0 records found in auto-increment tables.</div>';
            }
            ?>
        </div>

        <!-- Check for product_id = 0 records -->
        <div class="section">
            <h2>2. Product ID = 0 Records Check</h2>
            <?php
            $tables_to_check = array('product', 'product_description', 'product_to_store', 'product_to_category', 
                                    'product_image', 'product_option', 'product_option_value', 'product_filter', 
                                    'product_attribute', 'product_discount', 'product_special', 'product_reward', 
                                    'product_related', 'product_compatible', 'product_to_layout', 'product_to_download');
            
            $found_zero = false;
            echo '<table>';
            echo '<tr><th>Table</th><th>Count</th><th>Status</th></tr>';
            
            foreach ($tables_to_check as $table) {
                try {
                    $check = $db->query("SELECT COUNT(*) as count FROM `" . DB_PREFIX . $table . "` WHERE product_id = 0");
                    if ($check && $check->num_rows) {
                        $count = (int)$check->row['count'];
                        if ($count > 0) {
                            $found_zero = true;
                            echo '<tr><td>' . $table . '</td><td><span class="badge badge-danger">' . $count . '</span></td><td>⚠️ Found</td></tr>';
                        } else {
                            echo '<tr><td>' . $table . '</td><td><span class="badge badge-success">0</span></td><td>✓ Clean</td></tr>';
                        }
                    }
                } catch (Exception $e) {
                    echo '<tr><td>' . $table . '</td><td>-</td><td>❌ Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                }
            }
            echo '</table>';
            
            if ($found_zero) {
                echo '<div class="error" style="margin-top: 15px; padding: 10px;"><strong>⚠️ Warning:</strong> Found product_id = 0 records! This can cause duplicate entry errors.</div>';
            } else {
                echo '<div class="success" style="margin-top: 15px; padding: 10px;"><strong>✓ Good:</strong> No product_id = 0 records found.</div>';
            }
            ?>
        </div>

        <!-- Duplicate Models -->
        <div class="section">
            <h2>3. Duplicate Models Check</h2>
            <?php
            try {
                $dup_models = $db->query("SELECT model, COUNT(*) as count, GROUP_CONCAT(product_id) as product_ids 
                    FROM `" . DB_PREFIX . "product` 
                    WHERE model != '' AND model IS NOT NULL 
                    GROUP BY model 
                    HAVING count > 1");
                
                if ($dup_models && $dup_models->num_rows > 0) {
                    echo '<table>';
                    echo '<tr><th>Model</th><th>Count</th><th>Product IDs</th></tr>';
                    foreach ($dup_models->rows as $row) {
                        echo '<tr><td>' . htmlspecialchars($row['model']) . '</td><td><span class="badge badge-warning">' . $row['count'] . '</span></td><td>' . $row['product_ids'] . '</td></tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<div class="success" style="padding: 10px;"><strong>✓ Good:</strong> No duplicate models found.</div>';
                }
            } catch (Exception $e) {
                echo '<div class="error" style="padding: 10px;"><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
        </div>

        <!-- Duplicate SKUs -->
        <div class="section">
            <h2>4. Duplicate SKUs Check</h2>
            <?php
            try {
                $dup_skus = $db->query("SELECT sku, COUNT(*) as count, GROUP_CONCAT(product_id) as product_ids 
                    FROM `" . DB_PREFIX . "product` 
                    WHERE sku != '' AND sku IS NOT NULL 
                    GROUP BY sku 
                    HAVING count > 1");
                
                if ($dup_skus && $dup_skus->num_rows > 0) {
                    echo '<table>';
                    echo '<tr><th>SKU</th><th>Count</th><th>Product IDs</th></tr>';
                    foreach ($dup_skus->rows as $row) {
                        echo '<tr><td>' . htmlspecialchars($row['sku']) . '</td><td><span class="badge badge-warning">' . $row['count'] . '</span></td><td>' . $row['product_ids'] . '</td></tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<div class="success" style="padding: 10px;"><strong>✓ Good:</strong> No duplicate SKUs found.</div>';
                }
            } catch (Exception $e) {
                echo '<div class="error" style="padding: 10px;"><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
        </div>

        <!-- Product Information -->
        <?php if ($product_id > 0) { ?>
        <div class="section">
            <h2>5. Product Information (ID: <?php echo $product_id; ?>)</h2>
            <?php
            try {
                $product = $db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE product_id = '" . (int)$product_id . "'");
                if ($product && $product->num_rows) {
                    echo '<table>';
                    foreach ($product->row as $key => $value) {
                        echo '<tr><th>' . htmlspecialchars($key) . '</th><td>' . htmlspecialchars(is_array($value) ? print_r($value, true) : $value) . '</td></tr>';
                    }
                    echo '</table>';
                    
                    // Check related records
                    echo '<h3>Related Records</h3>';
                    echo '<table>';
                    echo '<tr><th>Table</th><th>Count</th></tr>';
                    foreach ($tables_to_check as $table) {
                        if ($table == 'product') continue;
                        try {
                            $count = $db->query("SELECT COUNT(*) as count FROM `" . DB_PREFIX . $table . "` WHERE product_id = '" . (int)$product_id . "'");
                            if ($count && $count->num_rows) {
                                $cnt = (int)$count->row['count'];
                                if ($cnt > 0) {
                                    echo '<tr><td>' . $table . '</td><td><span class="badge badge-info">' . $cnt . '</span></td></tr>';
                                }
                            }
                        } catch (Exception $e) {
                            // Skip
                        }
                    }
                    echo '</table>';
                } else {
                    echo '<div class="error" style="padding: 10px;"><strong>Error:</strong> Product with ID ' . $product_id . ' not found.</div>';
                }
            } catch (Exception $e) {
                echo '<div class="error" style="padding: 10px;"><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
        </div>
        <?php } ?>

        <!-- Database Info -->
        <div class="section">
            <h2>6. Database Information</h2>
            <?php
            try {
                $auto_inc = $db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "product'");
                if ($auto_inc && $auto_inc->num_rows) {
                    echo '<table>';
                    echo '<tr><th>Next Auto Increment</th><td>' . (isset($auto_inc->row['Auto_increment']) ? $auto_inc->row['Auto_increment'] : 'N/A') . '</td></tr>';
                    echo '<tr><th>Total Rows</th><td>' . (isset($auto_inc->row['Rows']) ? $auto_inc->row['Rows'] : 'N/A') . '</td></tr>';
                    echo '</table>';
                }
            } catch (Exception $e) {
                echo '<div class="error" style="padding: 10px;"><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
        </div>

        <!-- Recent Errors -->
        <div class="section">
            <h2>7. Recent Error Logs</h2>
            <?php
            $error_log = DIR_LOGS . 'product_insert_error.log';
            if (file_exists($error_log)) {
                $lines = file($error_log);
                $recent = array_slice($lines, -30); // Last 30 lines
                echo '<pre>';
                foreach ($recent as $line) {
                    echo htmlspecialchars($line);
                }
                echo '</pre>';
            } else {
                echo '<div class="warning" style="padding: 10px;">Error log file not found: ' . $error_log . '</div>';
            }
            ?>
        </div>

    </div>
</body>
</html>

