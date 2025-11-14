<?php
// Fix Review Table AUTO_INCREMENT
// Run this script once to fix the review table AUTO_INCREMENT issue

// Database configuration - Update these with your actual database credentials
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'your_database_name');
define('DB_PREFIX', 'oc_'); // Change to your table prefix

// Connect to database
$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

echo "Fixing review table AUTO_INCREMENT...\n\n";

// Step 1: Delete any review with review_id = 0
echo "Step 1: Removing any review with review_id = 0...\n";
$mysqli->query("DELETE FROM " . DB_PREFIX . "review WHERE review_id = 0");
echo "Deleted " . $mysqli->affected_rows . " row(s) with review_id = 0\n\n";

// Step 2: Get the maximum review_id
echo "Step 2: Finding maximum review_id...\n";
$result = $mysqli->query("SELECT MAX(review_id) as max_id FROM " . DB_PREFIX . "review");
$row = $result->fetch_assoc();
$max_id = isset($row['max_id']) && $row['max_id'] !== null ? (int)$row['max_id'] : 0;
echo "Maximum review_id found: " . $max_id . "\n\n";

// Step 3: Set AUTO_INCREMENT to next available value
$next_id = max($max_id + 1, 1);
echo "Step 3: Setting AUTO_INCREMENT to " . $next_id . "...\n";
$mysqli->query("ALTER TABLE " . DB_PREFIX . "review AUTO_INCREMENT = " . $next_id);
echo "AUTO_INCREMENT set to " . $next_id . "\n\n";

// Step 4: Verify the fix
echo "Step 4: Verifying AUTO_INCREMENT...\n";
$result = $mysqli->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "review'");
$row = $result->fetch_assoc();
echo "Current AUTO_INCREMENT value: " . $row['Auto_increment'] . "\n\n";

echo "Review table AUTO_INCREMENT has been fixed!\n";
echo "You can now submit reviews without the duplicate key error.\n";

$mysqli->close();
?>

