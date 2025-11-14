<?php
// Install Review View Module
// This script will add the review_view module to the extension table

// Database configuration - Using your actual database credentials
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'masterco_rup');
define('DB_PASSWORD', 'masterco_new1');
define('DB_DATABASE', 'masterco_rup');
define('DB_PREFIX', 'sr_');

// Connect to database
$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

echo "<h2>Install Review View Module</h2>\n";

// Check if module is already installed
$check_query = "SELECT * FROM " . DB_PREFIX . "extension WHERE type = 'module' AND code = 'review_view'";
$result = $mysqli->query($check_query);

if ($result && $result->num_rows > 0) {
    echo "<p style='color:orange;'><strong>⚠ Module 'review_view' is already installed.</strong></p>\n";
    echo "<p>If you're still not seeing it in the admin panel, try:</p>\n";
    echo "<ol>\n";
    echo "<li>Clear your browser cache</li>\n";
    echo "<li>Log out and log back into the admin panel</li>\n";
    echo "<li>Check that the file <code>admin/controller/module/review_view.php</code> exists</li>\n";
    echo "<li>Check that the file <code>admin/language/english/module/review_view.php</code> exists</li>\n";
    echo "</ol>\n";
} else {
    // Install the module
    $install_query = "INSERT INTO " . DB_PREFIX . "extension SET type = 'module', code = 'review_view'";
    
    if ($mysqli->query($install_query)) {
        echo "<p style='color:green;'><strong>✓ Successfully installed Review View module!</strong></p>\n";
        echo "<p>You can now:</p>\n";
        echo "<ol>\n";
        echo "<li>Go to <strong>Extensions → Modules</strong> in your admin panel</li>\n";
        echo "<li>Find <strong>Review View</strong> in the list</li>\n";
        echo "<li>Click the green <strong>+</strong> button to add a new instance</li>\n";
        echo "</ol>\n";
    } else {
        echo "<p style='color:red;'><strong>✗ Error installing module: " . $mysqli->error . "</strong></p>\n";
    }
}

// Show current installed modules
echo "<hr>\n";
echo "<h3>Currently Installed Modules</h3>\n";
$modules_query = "SELECT * FROM " . DB_PREFIX . "extension WHERE type = 'module' ORDER BY code";
$modules_result = $mysqli->query($modules_query);

if ($modules_result && $modules_result->num_rows > 0) {
    echo "<table border='1' cellpadding='5' cellspacing='0'>\n";
    echo "<tr><th>Extension ID</th><th>Type</th><th>Code</th></tr>\n";
    while ($row = $modules_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['extension_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['code']) . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
} else {
    echo "<p>No modules installed.</p>\n";
}

$mysqli->close();

echo "<hr>\n";
echo "<p><strong>Installation complete! Now go to your admin panel and check Extensions → Modules.</strong></p>\n";
echo "<p><a href='http://ruplexa1.master.com.bd/admin/index.php?route=extension/module'>Go to Admin Modules</a></p>\n";
?>

