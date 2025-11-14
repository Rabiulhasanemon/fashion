<?php
// Fix Review with review_id = 0
// This script will fix the review with review_id = 0 by giving it a proper ID

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

echo "<h2>Fix Review with review_id = 0</h2>\n";

// Step 1: Check if review with ID 0 exists
echo "<h3>Step 1: Checking for review with review_id = 0</h3>\n";
$result = $mysqli->query("SELECT * FROM " . DB_PREFIX . "review WHERE review_id = 0");
if ($result && $result->num_rows > 0) {
    $review = $result->fetch_assoc();
    echo "<p>Found review with review_id = 0:</p>\n";
    echo "<pre>" . print_r($review, true) . "</pre>\n";
    
    // Step 2: Get the next available review_id
    echo "<h3>Step 2: Getting next available review_id</h3>\n";
    $max_result = $mysqli->query("SELECT MAX(review_id) as max_id FROM " . DB_PREFIX . "review");
    $max_row = $max_result->fetch_assoc();
    $max_id = isset($max_row['max_id']) && $max_row['max_id'] !== null ? (int)$max_row['max_id'] : 0;
    $next_id = max($max_id + 1, 1);
    
    echo "<p>Current max review_id: " . $max_id . "</p>\n";
    echo "<p>Next available review_id: " . $next_id . "</p>\n";
    
    // Step 3: Update the review to use the new ID
    echo "<h3>Step 3: Updating review_id from 0 to " . $next_id . "</h3>\n";
    
    // First, temporarily set it to a high number to avoid conflicts
    $temp_id = 999999;
    $mysqli->query("UPDATE " . DB_PREFIX . "review SET review_id = " . $temp_id . " WHERE review_id = 0");
    
    // Then set it to the correct ID
    $mysqli->query("UPDATE " . DB_PREFIX . "review SET review_id = " . $next_id . " WHERE review_id = " . $temp_id);
    
    if ($mysqli->affected_rows > 0) {
        echo "<p style='color:green;'><strong>✓ Successfully updated review_id from 0 to " . $next_id . "</strong></p>\n";
    } else {
        echo "<p style='color:red;'><strong>✗ Failed to update review_id</strong></p>\n";
    }
    
    // Step 4: Fix AUTO_INCREMENT
    echo "<h3>Step 4: Fixing AUTO_INCREMENT</h3>\n";
    $new_next_id = $next_id + 1;
    $mysqli->query("ALTER TABLE " . DB_PREFIX . "review AUTO_INCREMENT = " . $new_next_id);
    echo "<p style='color:green;'><strong>✓ AUTO_INCREMENT set to " . $new_next_id . "</strong></p>\n";
    
    // Step 5: Verify
    echo "<h3>Step 5: Verification</h3>\n";
    $verify_result = $mysqli->query("SELECT review_id, author, rating, status, date_added FROM " . DB_PREFIX . "review WHERE review_id = " . $next_id);
    if ($verify_result && $verify_result->num_rows > 0) {
        $verify_review = $verify_result->fetch_assoc();
        echo "<p style='color:green;'><strong>✓ Review now has proper ID:</strong></p>\n";
        echo "<pre>" . print_r($verify_review, true) . "</pre>\n";
    }
    
    // Check for any remaining reviews with ID 0
    $check_zero = $mysqli->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "review WHERE review_id = 0");
    $zero_row = $check_zero->fetch_assoc();
    if ($zero_row['count'] > 0) {
        echo "<p style='color:red;'><strong>✗ Warning: Still found " . $zero_row['count'] . " review(s) with review_id = 0</strong></p>\n";
    } else {
        echo "<p style='color:green;'><strong>✓ No reviews with review_id = 0 remaining</strong></p>\n";
    }
    
} else {
    echo "<p>No review with review_id = 0 found.</p>\n";
}

// Step 6: Show all reviews
echo "<h3>Step 6: All Reviews After Fix</h3>\n";
$all_reviews = $mysqli->query("SELECT review_id, product_id, author, rating, status, date_added FROM " . DB_PREFIX . "review ORDER BY date_added DESC");
if ($all_reviews && $all_reviews->num_rows > 0) {
    echo "<p><strong>Total reviews: " . $all_reviews->num_rows . "</strong></p>\n";
    echo "<table border='1' cellpadding='5' cellspacing='0'>\n";
    echo "<tr><th>Review ID</th><th>Product ID</th><th>Author</th><th>Rating</th><th>Status</th><th>Date Added</th></tr>\n";
    while ($row = $all_reviews->fetch_assoc()) {
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
}

$mysqli->close();

echo "<hr>\n";
echo "<p><strong>Fix completed! Now try accessing the admin panel again.</strong></p>\n";
echo "<p><a href='http://ruplexa1.master.com.bd/admin/index.php?route=catalog/review'>Go to Admin Reviews</a></p>\n";
?>

