<?php
// Test Reviews Query
// This script tests the exact query used in admin panel

// Database configuration - Update these with your actual database credentials
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'your_database_name');
define('DB_PREFIX', 'oc_'); // Change to your table prefix
define('LANGUAGE_ID', 1); // Change to your language ID

// Connect to database
$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

echo "<h2>Test Admin Review Query</h2>\n";

// Test 1: Count all reviews
echo "<h3>Test 1: Count All Reviews</h3>\n";
$result = $mysqli->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review");
$row = $result->fetch_assoc();
echo "<p><strong>Total reviews in database: " . $row['total'] . "</strong></p>\n";

// Test 2: Get all reviews with the exact query from admin model
echo "<h3>Test 2: Admin Query (No Filters)</h3>\n";
$sql = "SELECT r.review_id, 
    (SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . LANGUAGE_ID . "' LIMIT 1) AS name, 
    r.author, r.rating, r.status, r.date_added 
    FROM " . DB_PREFIX . "review r 
    WHERE 1=1
    ORDER BY r.date_added DESC
    LIMIT 0, 20";

echo "<p><strong>SQL Query:</strong></p>\n";
echo "<pre>" . htmlspecialchars($sql) . "</pre>\n";

$result = $mysqli->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<p><strong>Reviews returned: " . $result->num_rows . "</strong></p>\n";
    echo "<table border='1' cellpadding='5' cellspacing='0'>\n";
    echo "<tr><th>Review ID</th><th>Product Name</th><th>Author</th><th>Rating</th><th>Status</th><th>Date Added</th></tr>\n";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['review_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name'] !== null ? $row['name'] : 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['author']) . "</td>";
        echo "<td>" . htmlspecialchars($row['rating']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date_added']) . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
} else {
    echo "<p>No reviews returned by query.</p>\n";
}

// Test 3: Check for duplicate review_ids
echo "<h3>Test 3: Check for Duplicate Review IDs</h3>\n";
$result = $mysqli->query("SELECT review_id, COUNT(*) as count FROM " . DB_PREFIX . "review GROUP BY review_id HAVING count > 1");
if ($result && $result->num_rows > 0) {
    echo "<p style='color:red;'><strong>WARNING: Found duplicate review_ids!</strong></p>\n";
    echo "<table border='1' cellpadding='5' cellspacing='0'>\n";
    echo "<tr><th>Review ID</th><th>Count</th></tr>\n";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row['review_id']) . "</td><td>" . htmlspecialchars($row['count']) . "</td></tr>\n";
    }
    echo "</table>\n";
} else {
    echo "<p style='color:green;'>No duplicate review_ids found.</p>\n";
}

// Test 4: List all reviews with their IDs
echo "<h3>Test 4: All Reviews in Database</h3>\n";
$result = $mysqli->query("SELECT review_id, product_id, author, rating, status, date_added FROM " . DB_PREFIX . "review ORDER BY date_added DESC");
if ($result && $result->num_rows > 0) {
    echo "<p><strong>Total: " . $result->num_rows . " reviews</strong></p>\n";
    echo "<table border='1' cellpadding='5' cellspacing='0'>\n";
    echo "<tr><th>Review ID</th><th>Product ID</th><th>Author</th><th>Rating</th><th>Status</th><th>Date Added</th></tr>\n";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['review_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['author']) . "</td>";
        echo "<td>" . htmlspecialchars($row['rating']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date_added']) . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
} else {
    echo "<p>No reviews found in database.</p>\n";
}

$mysqli->close();
?>

