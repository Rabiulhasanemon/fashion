<?php
// Comprehensive Product System Test & Debug Script
// Access: your-site/admin/test_product_system.php?token=YOUR_TOKEN

require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

$registry = new Registry();
$config = new Config();
$registry->set('config', $config);

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

$request = new Request();
$registry->set('request', $request);

$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

$session = new Session();
$registry->set('session', $session);

$loader = new Loader($registry);
$registry->set('load', $loader);

$user = new User($registry);
$registry->set('user', $user);

if (!$user->isLogged()) {
    die('Access Denied. Please login to admin panel first.');
}

$action = isset($_GET['action']) ? $_GET['action'] : 'test';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Product System Comprehensive Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1400px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        h2 { color: #007bff; margin-top: 30px; border-left: 4px solid #007bff; padding-left: 10px; }
        .section { background: #f8f9fa; padding: 15px; margin: 15px 0; border-radius: 5px; border: 1px solid #dee2e6; }
        .error { background: #fff5f5; border-color: #dc3545; }
        .success { background: #f0fff4; border-color: #28a745; }
        .warning { background: #fffbf0; border-color: #ffc107; }
        .info { background: #e7f3ff; border-color: #17a2b8; }
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
        .badge-info { background: #17a2b8; color: white; }
        .test-result { margin: 10px 0; padding: 10px; border-radius: 4px; }
        .test-pass { background: #d4edda; border-left: 4px solid #28a745; }
        .test-fail { background: #f8d7da; border-left: 4px solid #dc3545; }
        .test-warn { background: #fff3cd; border-left: 4px solid #ffc107; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Product System Comprehensive Test & Debug</h1>
        
        <div class="section">
            <h3>Quick Actions</h3>
            <a href="?token=<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>&action=test" class="btn">Run All Tests</a>
            <a href="?token=<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>&action=cleanup" class="btn btn-danger">Full Cleanup</a>
            <a href="?token=<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>&action=structure" class="btn btn-success">Check Table Structures</a>
            <a href="?token=<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>&action=testinsert" class="btn" style="background: #ff6a00;">Test Product Insert</a>
        </div>

        <?php
        if ($action == 'cleanup') {
            echo '<div class="section success">';
            echo '<h2>Full System Cleanup</h2>';
            performFullCleanup($db);
            echo '</div>';
        } elseif ($action == 'structure') {
            echo '<div class="section info">';
            echo '<h2>Table Structure Analysis</h2>';
            checkTableStructures($db);
            echo '</div>';
        } elseif ($action == 'testinsert') {
            echo '<div class="section info">';
            echo '<h2>Test Product Insert</h2>';
            testProductInsert($db);
            echo '</div>';
        } else {
            echo '<div class="section">';
            echo '<h2>Comprehensive System Tests</h2>';
            runAllTests($db);
            echo '</div>';
        }
        ?>

    </div>
</body>
</html>

<?php

function runAllTests($db) {
    $results = array(
        'passed' => 0,
        'failed' => 0,
        'warnings' => 0
    );
    
    // Test 1: Database Connection
    testDatabaseConnection($db, $results);
    
    // Test 2: Table Existence
    testTableExistence($db, $results);
    
    // Test 3: ID = 0 Records
    testZeroRecords($db, $results);
    
    // Test 4: Auto-Increment Values
    testAutoIncrement($db, $results);
    
    // Test 5: Required Columns
    testRequiredColumns($db, $results);
    
    // Test 6: Foreign Key Integrity
    testForeignKeyIntegrity($db, $results);
    
    // Test 7: Duplicate Data
    testDuplicateData($db, $results);
    
    // Test 8: Data Types
    testDataTypes($db, $results);
    
    // Test 9: Indexes
    testIndexes($db, $results);
    
    // Test 10: Model Methods
    testModelMethods($db, $results);
    
    // Summary
    echo '<div class="section ' . ($results['failed'] > 0 ? 'error' : 'success') . '">';
    echo '<h2>Test Summary</h2>';
    echo '<p><strong>Total Tests:</strong> ' . ($results['passed'] + $results['failed'] + $results['warnings']) . '</p>';
    echo '<p><span class="badge badge-success">Passed: ' . $results['passed'] . '</span> ';
    echo '<span class="badge badge-warning">Warnings: ' . $results['warnings'] . '</span> ';
    echo '<span class="badge badge-danger">Failed: ' . $results['failed'] . '</span></p>';
    echo '</div>';
}

function testDatabaseConnection($db, &$results) {
    echo '<h3>Test 1: Database Connection</h3>';
    try {
        $test = $db->query("SELECT 1");
        if ($test) {
            echo '<div class="test-result test-pass">✓ Database connection successful</div>';
            $results['passed']++;
        } else {
            echo '<div class="test-result test-fail">✗ Database connection failed</div>';
            $results['failed']++;
        }
    } catch (Exception $e) {
        echo '<div class="test-result test-fail">✗ Database connection error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        $results['failed']++;
    }
}

function testTableExistence($db, &$results) {
    echo '<h3>Test 2: Required Tables Existence</h3>';
    $required_tables = array(
        'product', 'product_description', 'product_to_store', 'product_to_category',
        'product_image', 'product_option', 'product_option_value', 'product_filter',
        'product_attribute', 'product_discount', 'product_special', 'product_reward',
        'product_related', 'product_compatible', 'product_to_layout', 'product_to_download'
    );
    
    $missing = array();
    foreach ($required_tables as $table) {
        $check = $db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table . "'");
        if (!$check || !$check->num_rows) {
            $missing[] = $table;
        }
    }
    
    if (empty($missing)) {
        echo '<div class="test-result test-pass">✓ All required tables exist</div>';
        $results['passed']++;
    } else {
        echo '<div class="test-result test-fail">✗ Missing tables: ' . implode(', ', $missing) . '</div>';
        $results['failed']++;
    }
}

function testZeroRecords($db, &$results) {
    echo '<h3>Test 3: ID = 0 Records Check</h3>';
    $tables = array(
        'product' => 'product_id',
        'product_reward' => 'product_reward_id',
        'product_image' => 'product_image_id',
        'product_option' => 'product_option_id',
        'product_option_value' => 'product_option_value_id',
        'product_discount' => 'product_discount_id',
        'product_special' => 'product_special_id'
    );
    
    $found_zero = false;
    $zero_details = array();
    
    foreach ($tables as $table => $id_field) {
        try {
            $check_col = $db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE '" . $id_field . "'");
            if ($check_col && $check_col->num_rows) {
                $count = $db->query("SELECT COUNT(*) as count FROM `" . DB_PREFIX . $table . "` WHERE " . $id_field . " = 0");
                if ($count && $count->num_rows && $count->row['count'] > 0) {
                    $found_zero = true;
                    $zero_details[] = $table . ' (' . $count->row['count'] . ' records)';
                }
            }
        } catch (Exception $e) {
            // Table might not have this field
        }
    }
    
    if (!$found_zero) {
        echo '<div class="test-result test-pass">✓ No ID = 0 records found</div>';
        $results['passed']++;
    } else {
        echo '<div class="test-result test-fail">✗ Found ID = 0 records in: ' . implode(', ', $zero_details) . '</div>';
        $results['failed']++;
    }
}

function testAutoIncrement($db, &$results) {
    echo '<h3>Test 4: Auto-Increment Values</h3>';
    $tables = array(
        'product' => 'product_id',
        'product_reward' => 'product_reward_id',
        'product_image' => 'product_image_id',
        'product_option' => 'product_option_id',
        'product_option_value' => 'product_option_value_id',
        'product_discount' => 'product_discount_id',
        'product_special' => 'product_special_id'
    );
    
    $issues = array();
    
    foreach ($tables as $table => $id_field) {
        try {
            $check_col = $db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` WHERE Extra LIKE '%auto_increment%'");
            if ($check_col && $check_col->num_rows) {
                $status = $db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . $table . "'");
                if ($status && $status->num_rows) {
                    $auto_inc = isset($status->row['Auto_increment']) ? $status->row['Auto_increment'] : null;
                    $max_id = $db->query("SELECT MAX(" . $id_field . ") as max_id FROM `" . DB_PREFIX . $table . "` WHERE " . $id_field . " > 0");
                    $max = $max_id && $max_id->num_rows && isset($max_id->row['max_id']) ? (int)$max_id->row['max_id'] : 0;
                    
                    if ($auto_inc !== null && $auto_inc <= $max) {
                        $issues[] = $table . ' (AUTO_INCREMENT=' . $auto_inc . ', MAX=' . $max . ')';
                    }
                }
            }
        } catch (Exception $e) {
            // Skip
        }
    }
    
    if (empty($issues)) {
        echo '<div class="test-result test-pass">✓ All AUTO_INCREMENT values are correct</div>';
        $results['passed']++;
    } else {
        echo '<div class="test-result test-warn">⚠ AUTO_INCREMENT issues: ' . implode(', ', $issues) . '</div>';
        $results['warnings']++;
    }
}

function testRequiredColumns($db, &$results) {
    echo '<h3>Test 5: Required Columns Check</h3>';
    $required = array(
        'product' => array('product_id', 'status', 'date_added'),
        'product_description' => array('product_id', 'language_id', 'name')
    );
    
    $missing = array();
    foreach ($required as $table => $columns) {
        $cols = $db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`");
        $existing = array();
        if ($cols && $cols->num_rows) {
            foreach ($cols->rows as $row) {
                $existing[] = $row['Field'];
            }
        }
        
        foreach ($columns as $col) {
            if (!in_array($col, $existing)) {
                $missing[] = $table . '.' . $col;
            }
        }
    }
    
    if (empty($missing)) {
        echo '<div class="test-result test-pass">✓ All required columns exist</div>';
        $results['passed']++;
    } else {
        echo '<div class="test-result test-fail">✗ Missing columns: ' . implode(', ', $missing) . '</div>';
        $results['failed']++;
    }
}

function testForeignKeyIntegrity($db, &$results) {
    echo '<h3>Test 6: Foreign Key Integrity</h3>';
    $orphans = array();
    
    // Check product_description with invalid product_id
    $check = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_description pd 
        LEFT JOIN " . DB_PREFIX . "product p ON pd.product_id = p.product_id 
        WHERE p.product_id IS NULL");
    if ($check && $check->num_rows && $check->row['count'] > 0) {
        $orphans[] = 'product_description: ' . $check->row['count'] . ' orphaned records';
    }
    
    // Check product_to_store with invalid product_id
    $check = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_to_store p2s 
        LEFT JOIN " . DB_PREFIX . "product p ON p2s.product_id = p.product_id 
        WHERE p.product_id IS NULL");
    if ($check && $check->num_rows && $check->row['count'] > 0) {
        $orphans[] = 'product_to_store: ' . $check->row['count'] . ' orphaned records';
    }
    
    if (empty($orphans)) {
        echo '<div class="test-result test-pass">✓ No orphaned records found</div>';
        $results['passed']++;
    } else {
        echo '<div class="test-result test-warn">⚠ Orphaned records: ' . implode(', ', $orphans) . '</div>';
        $results['warnings']++;
    }
}

function testDuplicateData($db, &$results) {
    echo '<h3>Test 7: Duplicate Data Check</h3>';
    $duplicates = array();
    
    // Check duplicate models
    $dup = $db->query("SELECT model, COUNT(*) as count FROM " . DB_PREFIX . "product 
        WHERE model != '' AND model IS NOT NULL 
        GROUP BY model HAVING count > 1");
    if ($dup && $dup->num_rows > 0) {
        $duplicates[] = 'Models: ' . $dup->num_rows . ' duplicates';
    }
    
    // Check duplicate SKUs
    $dup = $db->query("SELECT sku, COUNT(*) as count FROM " . DB_PREFIX . "product 
        WHERE sku != '' AND sku IS NOT NULL 
        GROUP BY sku HAVING count > 1");
    if ($dup && $dup->num_rows > 0) {
        $duplicates[] = 'SKUs: ' . $dup->num_rows . ' duplicates';
    }
    
    if (empty($duplicates)) {
        echo '<div class="test-result test-pass">✓ No duplicate models or SKUs</div>';
        $results['passed']++;
    } else {
        echo '<div class="test-result test-warn">⚠ ' . implode(', ', $duplicates) . '</div>';
        $results['warnings']++;
    }
}

function testDataTypes($db, &$results) {
    echo '<h3>Test 8: Data Type Validation</h3>';
    $issues = array();
    
    // Check for invalid product_id values
    $check = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product WHERE product_id <= 0");
    if ($check && $check->num_rows && $check->row['count'] > 0) {
        $issues[] = 'Invalid product_id values: ' . $check->row['count'];
    }
    
    // Check for invalid status values
    $check = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product WHERE status NOT IN (0, 1)");
    if ($check && $check->num_rows && $check->row['count'] > 0) {
        $issues[] = 'Invalid status values: ' . $check->row['count'];
    }
    
    if (empty($issues)) {
        echo '<div class="test-result test-pass">✓ Data types are valid</div>';
        $results['passed']++;
    } else {
        echo '<div class="test-result test-warn">⚠ ' . implode(', ', $issues) . '</div>';
        $results['warnings']++;
    }
}

function testIndexes($db, &$results) {
    echo '<h3>Test 9: Index Check</h3>';
    $missing = array();
    
    // Check if product table has primary key
    $check = $db->query("SHOW INDEXES FROM " . DB_PREFIX . "product WHERE Key_name = 'PRIMARY'");
    if (!$check || !$check->num_rows) {
        $missing[] = 'product table missing PRIMARY KEY';
    }
    
    if (empty($missing)) {
        echo '<div class="test-result test-pass">✓ Required indexes exist</div>';
        $results['passed']++;
    } else {
        echo '<div class="test-result test-fail">✗ ' . implode(', ', $missing) . '</div>';
        $results['failed']++;
    }
}

function testModelMethods($db, &$results) {
    echo '<h3>Test 10: Model Method Availability</h3>';
    
    // Check if model file exists
    $model_file = DIR_APPLICATION . 'model/catalog/product.php';
    if (!file_exists($model_file)) {
        echo '<div class="test-result test-fail">✗ Model file not found: ' . $model_file . '</div>';
        $results['failed']++;
        return;
    }
    
    // Check if class exists
    if (!class_exists('ModelCatalogProduct')) {
        require_once($model_file);
    }
    
    // Check methods
    $methods = array('getProduct', 'getProducts', 'addProduct', 'editProduct', 'deleteProduct');
    $missing = array();
    
    foreach ($methods as $method) {
        if (!method_exists('ModelCatalogProduct', $method)) {
            $missing[] = $method;
        }
    }
    
    if (empty($missing)) {
        echo '<div class="test-result test-pass">✓ All required model methods exist</div>';
        $results['passed']++;
    } else {
        echo '<div class="test-result test-fail">✗ Missing methods: ' . implode(', ', $missing) . '</div>';
        $results['failed']++;
    }
}

function performFullCleanup($db) {
    echo '<h3>Performing Full System Cleanup</h3>';
    
    $cleaned = 0;
    $tables = array('product', 'product_description', 'product_to_store', 'product_to_category', 
                   'product_image', 'product_option', 'product_option_value', 'product_filter', 
                   'product_attribute', 'product_discount', 'product_special', 'product_reward', 
                   'product_related', 'product_compatible', 'product_to_layout', 'product_to_download');
    
    foreach ($tables as $table) {
        try {
            $result = $db->query("DELETE FROM `" . DB_PREFIX . $table . "` WHERE product_id = 0");
            if ($result) $cleaned++;
        } catch (Exception $e) {
            // Ignore
        }
    }
    
    // Clean auto-increment ID = 0 records
    $auto_inc_tables = array(
        'product_reward' => 'product_reward_id',
        'product_image' => 'product_image_id',
        'product_option' => 'product_option_id',
        'product_option_value' => 'product_option_value_id',
        'product_discount' => 'product_discount_id',
        'product_special' => 'product_special_id'
    );
    
    foreach ($auto_inc_tables as $table => $id_field) {
        try {
            $check_col = $db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE '" . $id_field . "'");
            if ($check_col && $check_col->num_rows) {
                $db->query("DELETE FROM `" . DB_PREFIX . $table . "` WHERE " . $id_field . " = 0");
                
                // Fix AUTO_INCREMENT
                $max_check = $db->query("SELECT MAX(" . $id_field . ") as max_id FROM `" . DB_PREFIX . $table . "` WHERE " . $id_field . " > 0");
                $max_id = 0;
                if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
                    $max_id = (int)$max_check->row['max_id'];
                }
                $next_id = max($max_id + 1, 1);
                $db->query("ALTER TABLE `" . DB_PREFIX . $table . "` AUTO_INCREMENT = " . $next_id);
                echo "<p>✓ Fixed AUTO_INCREMENT for " . $table . " to " . $next_id . "</p>";
            }
        } catch (Exception $e) {
            // Skip
        }
    }
    
    echo "<p><strong>Cleanup complete! Cleaned " . $cleaned . " tables.</strong></p>";
}

function checkTableStructures($db) {
    $tables = array('product', 'product_reward', 'product_discount', 'product_special', 
                   'product_image', 'product_description');
    
    foreach ($tables as $table) {
        echo '<h4>' . $table . '</h4>';
        try {
            $structure = $db->query("SHOW CREATE TABLE `" . DB_PREFIX . $table . "`");
            if ($structure && $structure->num_rows) {
                echo '<pre>' . htmlspecialchars($structure->row['Create Table']) . '</pre>';
            }
        } catch (Exception $e) {
            echo '<p class="error">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}

function testProductInsert($db) {
    echo '<h3>Testing Product Insert Mechanism</h3>';
    
    // Test 1: Check if we can insert into product_reward
    echo '<h4>Test: Insert into product_reward</h4>';
    try {
        // Clean up first
        $db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = 999999");
        
        // Fix AUTO_INCREMENT
        $max_check = $db->query("SELECT MAX(product_reward_id) as max_id FROM " . DB_PREFIX . "product_reward WHERE product_reward_id > 0");
        $max_id = 0;
        if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
            $max_id = (int)$max_check->row['max_id'];
        }
        $next_id = max($max_id + 1, 1);
        $db->query("ALTER TABLE " . DB_PREFIX . "product_reward AUTO_INCREMENT = " . $next_id);
        
        // Try REPLACE INTO
        $result = $db->query("REPLACE INTO " . DB_PREFIX . "product_reward SET product_id = '999999', customer_group_id = '1', points = '100'");
        if ($result) {
            echo '<div class="test-result test-pass">✓ REPLACE INTO product_reward successful</div>';
            
            // Clean up test record
            $db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = 999999");
        } else {
            echo '<div class="test-result test-fail">✗ REPLACE INTO product_reward failed</div>';
        }
    } catch (Exception $e) {
        echo '<div class="test-result test-fail">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    
    // Test 2: Check product_discount
    echo '<h4>Test: Insert into product_discount</h4>';
    try {
        $db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = 999999");
        
        $max_check = $db->query("SELECT MAX(product_discount_id) as max_id FROM " . DB_PREFIX . "product_discount WHERE product_discount_id > 0");
        $max_id = 0;
        if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
            $max_id = (int)$max_check->row['max_id'];
        }
        $next_id = max($max_id + 1, 1);
        $db->query("ALTER TABLE " . DB_PREFIX . "product_discount AUTO_INCREMENT = " . $next_id);
        
        $result = $db->query("REPLACE INTO " . DB_PREFIX . "product_discount SET product_id = '999999', customer_group_id = '1', quantity = '1', priority = '1', price = '10.00', date_start = '0000-00-00', date_end = '0000-00-00'");
        if ($result) {
            echo '<div class="test-result test-pass">✓ REPLACE INTO product_discount successful</div>';
            $db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = 999999");
        } else {
            echo '<div class="test-result test-fail">✗ REPLACE INTO product_discount failed</div>';
        }
    } catch (Exception $e) {
        echo '<div class="test-result test-fail">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    
    // Test 3: Check product_special
    echo '<h4>Test: Insert into product_special</h4>';
    try {
        $db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = 999999");
        
        $max_check = $db->query("SELECT MAX(product_special_id) as max_id FROM " . DB_PREFIX . "product_special WHERE product_special_id > 0");
        $max_id = 0;
        if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
            $max_id = (int)$max_check->row['max_id'];
        }
        $next_id = max($max_id + 1, 1);
        $db->query("ALTER TABLE " . DB_PREFIX . "product_special AUTO_INCREMENT = " . $next_id);
        
        $result = $db->query("REPLACE INTO " . DB_PREFIX . "product_special SET product_id = '999999', customer_group_id = '1', priority = '1', price = '10.00', date_start = '0000-00-00', date_end = '0000-00-00'");
        if ($result) {
            echo '<div class="test-result test-pass">✓ REPLACE INTO product_special successful</div>';
            $db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = 999999");
        } else {
            echo '<div class="test-result test-fail">✗ REPLACE INTO product_special failed</div>';
        }
    } catch (Exception $e) {
        echo '<div class="test-result test-fail">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    
    echo '<div class="test-result test-pass" style="margin-top: 20px;"><strong>All insert tests completed. Check results above.</strong></div>';
}

?>

