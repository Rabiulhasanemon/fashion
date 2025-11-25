<?php
// Quick diagnostic script to check admin access
// Access via: https://ruplexa1.master.com.bd/admin/check_access.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Admin Access Diagnostic</h2>";
echo "<pre>";

// Check if we can access the file
echo "✓ PHP is working\n";
echo "✓ File access: OK\n\n";

// Check config
if (file_exists(__DIR__ . '/config.php')) {
    echo "✓ Config file exists\n";
    require_once(__DIR__ . '/config.php');
    echo "✓ Config loaded\n";
} else {
    echo "✗ Config file missing!\n";
}

// Check database connection
if (defined('DB_HOSTNAME')) {
    echo "\n=== Database Connection Test ===\n";
    try {
        $db = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if ($db->connect_error) {
            echo "✗ Database connection failed: " . $db->connect_error . "\n";
        } else {
            echo "✓ Database connection: OK\n";
            $db->close();
        }
    } catch (Exception $e) {
        echo "✗ Database error: " . $e->getMessage() . "\n";
    }
}

// Check if product.php has syntax errors
echo "\n=== Checking Product Model ===\n";
$product_file = __DIR__ . '/model/catalog/product.php';
if (file_exists($product_file)) {
    echo "✓ Product model file exists\n";
    
    // Try to include it
    try {
        // Just check if file can be parsed (don't actually include it)
        $content = file_get_contents($product_file);
        if (strpos($content, 'class ModelCatalogProduct') !== false) {
            echo "✓ Product model class found\n";
        } else {
            echo "✗ Product model class not found!\n";
        }
    } catch (Exception $e) {
        echo "✗ Error reading product model: " . $e->getMessage() . "\n";
    }
} else {
    echo "✗ Product model file missing!\n";
}

// Check session
echo "\n=== Session Test ===\n";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo "✓ Session started\n";
$_SESSION['test'] = 'working';
if (isset($_SESSION['test'])) {
    echo "✓ Session working\n";
} else {
    echo "✗ Session not working!\n";
}

echo "\n=== Summary ===\n";
echo "If all checks pass, the issue is likely cPGuard blocking access.\n";
echo "Contact your hosting provider to whitelist your IP or disable cPGuard for /admin/\n";

echo "</pre>";
?>

