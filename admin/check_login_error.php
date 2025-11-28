<?php
// Simple script to check for PHP errors that might prevent login
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Admin Login Error Check</h1>";

// Check if config file exists
if (!file_exists(__DIR__ . '/config.php')) {
    die("ERROR: config.php not found!");
}
echo "✓ config.php exists<br>";

// Load config
require_once(__DIR__ . '/config.php');
echo "✓ config.php loaded<br>";

// Check if startup file exists
if (!file_exists(DIR_SYSTEM . 'startup.php')) {
    die("ERROR: startup.php not found!");
}
echo "✓ startup.php exists<br>";

// Load startup
require_once(DIR_SYSTEM . 'startup.php');
echo "✓ startup.php loaded<br>";

// Try to create registry
try {
    $registry = new Registry();
    echo "✓ Registry created<br>";
} catch (Exception $e) {
    die("ERROR creating Registry: " . $e->getMessage());
}

// Try to create config
try {
    $config = new Config();
    $registry->set('config', $config);
    echo "✓ Config created<br>";
} catch (Exception $e) {
    die("ERROR creating Config: " . $e->getMessage());
}

// Try to create database connection
try {
    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    $registry->set('db', $db);
    echo "✓ Database connection created<br>";
} catch (Exception $e) {
    die("ERROR creating database connection: " . $e->getMessage());
}

// Try to load login controller
try {
    $loader = new Loader($registry);
    $registry->set('load', $loader);
    echo "✓ Loader created<br>";
    
    // Try to load login controller class
    $controller = $loader->controller('common/login');
    echo "✓ Login controller loaded<br>";
} catch (Exception $e) {
    die("ERROR loading login controller: " . $e->getMessage());
} catch (Error $e) {
    die("FATAL ERROR loading login controller: " . $e->getMessage() . "<br>File: " . $e->getFile() . "<br>Line: " . $e->getLine());
}

// Check error log
$error_log = DIR_LOGS . 'error.log';
if (file_exists($error_log)) {
    echo "<hr><h2>Recent Error Log Entries:</h2>";
    $log_content = file_get_contents($error_log);
    $log_lines = explode("\n", $log_content);
    $recent_lines = array_slice($log_lines, -20);
    echo "<pre>" . htmlspecialchars(implode("\n", $recent_lines)) . "</pre>";
} else {
    echo "<hr><p>No error log found at: " . $error_log . "</p>";
}

echo "<hr><p>✓ All basic checks passed. If login still fails, check the error log above.</p>";
?>

