<?php
// Approve All Reviews Script
// This will set all reviews to approved status (status = 1)

// Database configuration
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

echo "<h2>Approve All Reviews</h2>\n";
echo "<style>body{font-family:Arial;margin:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background:#f5f5f5;}</style>\n";

// Check if status column exists
$columns_check = $mysqli->query("SHOW COLUMNS FROM " . DB_PREFIX . "review LIKE 'status'");
if ($columns_check && $columns_check->num_rows > 0) {
    echo "<p class='ok'>✓ Status column exists</p>\n";
    
    // Count pending reviews
    $pending_query = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "review WHERE status = 0";
    $pending_result = $mysqli->query($pending_query);
    $pending_count = $pending_result->fetch_assoc()['count'];
    
    echo "<p><strong>Pending Reviews:</strong> $pending_count</p>\n";
    
    if ($pending_count > 0) {
        // Approve all pending reviews
        $approve_query = "UPDATE " . DB_PREFIX . "review SET status = 1 WHERE status = 0";
        
        if ($mysqli->query($approve_query)) {
            $affected = $mysqli->affected_rows;
            echo "<p class='ok'>✓ Successfully approved $affected review(s)</p>\n";
        } else {
            echo "<p class='error'>✗ Error approving reviews: " . $mysqli->error . "</p>\n";
        }
    } else {
        echo "<p class='ok'>✓ All reviews are already approved</p>\n";
    }
    
    // Show all reviews after update
    echo "<h3>All Reviews After Update</h3>\n";
    $all_reviews = $mysqli->query("SELECT review_id, author, rating, status, date_added FROM " . DB_PREFIX . "review ORDER BY date_added DESC");
    if ($all_reviews && $all_reviews->num_rows > 0) {
        echo "<table>\n";
        echo "<tr><th>Review ID</th><th>Author</th><th>Rating</th><th>Status</th><th>Date</th></tr>\n";
        while ($row = $all_reviews->fetch_assoc()) {
            $status_text = $row['status'] == 1 ? 'Approved' : 'Pending';
            $status_class = $row['status'] == 1 ? 'ok' : 'warning';
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['review_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['author']) . "</td>";
            echo "<td>" . htmlspecialchars($row['rating']) . "★</td>";
            echo "<td class='$status_class'>" . $status_text . "</td>";
            echo "<td>" . htmlspecialchars($row['date_added']) . "</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
} else {
    echo "<p class='warning'>⚠ Status column does not exist in review table</p>\n";
    echo "<p>Adding status column...</p>\n";
    
    $add_column = "ALTER TABLE " . DB_PREFIX . "review ADD COLUMN status tinyint(1) NOT NULL DEFAULT 1";
    if ($mysqli->query($add_column)) {
        echo "<p class='ok'>✓ Status column added. All existing reviews are now approved.</p>\n";
    } else {
        echo "<p class='error'>✗ Error adding status column: " . $mysqli->error . "</p>\n";
    }
}

$mysqli->close();

echo "<hr>\n";
echo "<p><strong>Next Steps:</strong></p>\n";
echo "<ol>\n";
echo "<li>Clear cache (delete files in system/cache/)</li>\n";
echo "<li>Refresh your frontend page</li>\n";
echo "<li>The Review View module should now display the reviews</li>\n";
echo "</ol>\n";
echo "<p><a href='http://ruplexa1.master.com.bd/'>Go to Frontend</a></p>\n";
?>

