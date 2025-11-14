<?php
// Check Reviews in Database
// Run this script to see all reviews in the database

// Database configuration - Using your actual database credentials from config.php
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'masterco_rup');
define('DB_PASSWORD', 'masterco_new1');
define('DB_DATABASE', 'masterco_rup');
define('DB_PREFIX', 'sr_'); // Your table prefix

// Connect to database
$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

echo "<h2>All Reviews in Database</h2>\n";
echo "<table border='1' cellpadding='5' cellspacing='0'>\n";
echo "<tr><th>Review ID</th><th>Product ID</th><th>Author</th><th>Rating</th><th>Status</th><th>Date Added</th><th>Text Preview</th></tr>\n";

// Get all reviews
$result = $mysqli->query("SELECT review_id, product_id, author, rating, status, date_added, text FROM " . DB_PREFIX . "review ORDER BY date_added DESC");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['review_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['author']) . "</td>";
        echo "<td>" . htmlspecialchars($row['rating']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date_added']) . "</td>";
        echo "<td>" . htmlspecialchars(substr($row['text'], 0, 50)) . "...</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo "<p><strong>Total Reviews: " . $result->num_rows . "</strong></p>\n";
} else {
    echo "</table>\n";
    echo "<p>No reviews found in database.</p>\n";
}

// Check for reviews with product_id that don't have product descriptions
echo "<h2>Reviews with Missing Product Descriptions</h2>\n";
$result2 = $mysqli->query("SELECT r.review_id, r.product_id, r.author, r.date_added 
    FROM " . DB_PREFIX . "review r 
    LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id AND pd.language_id = 1)
    WHERE pd.product_id IS NULL
    ORDER BY r.date_added DESC");

if ($result2 && $result2->num_rows > 0) {
    echo "<p>Found " . $result2->num_rows . " reviews with missing product descriptions:</p>\n";
    echo "<table border='1' cellpadding='5' cellspacing='0'>\n";
    echo "<tr><th>Review ID</th><th>Product ID</th><th>Author</th><th>Date Added</th></tr>\n";
    while ($row = $result2->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['review_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['author']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date_added']) . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
} else {
    echo "<p>All reviews have product descriptions.</p>\n";
}

$mysqli->close();
?>

