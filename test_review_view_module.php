<?php
// Test Review View Module
// This script tests if the Review View module can load reviews

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

echo "<h2>Review View Module Test</h2>\n";
echo "<style>body{font-family:Arial;margin:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background:#f5f5f5;}</style>\n";

// Check 1: Reviews in database
echo "<h3>1. Reviews in Database</h3>\n";
$reviews_query = "SELECT review_id, author, product_id, rating, status, date_added FROM " . DB_PREFIX . "review ORDER BY date_added DESC LIMIT 10";
$reviews_result = $mysqli->query($reviews_query);

if ($reviews_result && $reviews_result->num_rows > 0) {
    echo "<p class='ok'>✓ Found " . $reviews_result->num_rows . " reviews</p>\n";
    echo "<table>\n";
    echo "<tr><th>Review ID</th><th>Author</th><th>Product ID</th><th>Rating</th><th>Status</th><th>Date</th></tr>\n";
    while ($row = $reviews_result->fetch_assoc()) {
        $status_text = $row['status'] == 1 ? 'Approved' : 'Pending';
        $status_class = $row['status'] == 1 ? 'ok' : 'warning';
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['review_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['author']) . "</td>";
        echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['rating']) . "★</td>";
        echo "<td class='$status_class'>" . $status_text . "</td>";
        echo "<td>" . htmlspecialchars($row['date_added']) . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
} else {
    echo "<p class='error'>✗ No reviews found in database</p>\n";
}

// Check 2: Module instances
echo "<h3>2. Review View Module Instances</h3>\n";
$modules_query = "SELECT module_id, name, code, setting FROM " . DB_PREFIX . "module WHERE code = 'review_view'";
$modules_result = $mysqli->query($modules_query);

if ($modules_result && $modules_result->num_rows > 0) {
    echo "<p class='ok'>✓ Found " . $modules_result->num_rows . " Review View module instance(s)</p>\n";
    while ($row = $modules_result->fetch_assoc()) {
        $setting = unserialize($row['setting']);
        echo "<div style='border:1px solid #ddd;padding:15px;margin:10px 0;'>\n";
        echo "<h4>Module: " . htmlspecialchars($row['name']) . " (ID: " . $row['module_id'] . ")</h4>\n";
        echo "<p><strong>Status:</strong> " . (isset($setting['status']) && $setting['status'] ? 'Enabled' : 'Disabled') . "</p>\n";
        echo "<p><strong>Title:</strong> " . (isset($setting['title']) ? htmlspecialchars($setting['title']) : 'None') . "</p>\n";
        echo "<p><strong>Limit:</strong> " . (isset($setting['limit']) ? $setting['limit'] : 'Not set') . "</p>\n";
        
        if (isset($setting['review_ids']) && is_array($setting['review_ids']) && !empty($setting['review_ids'])) {
            echo "<p class='ok'><strong>Selected Reviews:</strong> " . count($setting['review_ids']) . " review(s)</p>\n";
            echo "<ul>\n";
            foreach ($setting['review_ids'] as $review_id) {
                // Check if review exists and is approved
                $check_review = $mysqli->query("SELECT review_id, author, status FROM " . DB_PREFIX . "review WHERE review_id = " . (int)$review_id);
                if ($check_review && $check_review->num_rows > 0) {
                    $review_row = $check_review->fetch_assoc();
                    $status_icon = $review_row['status'] == 1 ? '✓' : '⚠';
                    $status_text = $review_row['status'] == 1 ? 'Approved' : 'Pending';
                    echo "<li>$status_icon Review ID: $review_id - " . htmlspecialchars($review_row['author']) . " ($status_text)</li>\n";
                } else {
                    echo "<li class='error'>✗ Review ID: $review_id - NOT FOUND</li>\n";
                }
            }
            echo "</ul>\n";
        } else {
            echo "<p class='warning'>⚠ No reviews selected for this module</p>\n";
        }
        echo "</div>\n";
    }
} else {
    echo "<p class='warning'>⚠ No Review View module instances found</p>\n";
    echo "<p>You need to:</p>\n";
    echo "<ol>\n";
    echo "<li>Go to Admin Panel → Extensions → Modules</li>\n";
    echo "<li>Find 'Review View' module</li>\n";
    echo "<li>Click the green + button to add a new instance</li>\n";
    echo "<li>Select reviews and configure the module</li>\n";
    echo "<li>Assign it to a layout position</li>\n";
    echo "</ol>\n";
}

// Check 3: Layout assignments
echo "<h3>3. Layout Assignments</h3>\n";
$layout_query = "SELECT lm.*, l.name as layout_name FROM " . DB_PREFIX . "layout_module lm 
                 LEFT JOIN " . DB_PREFIX . "layout l ON lm.layout_id = l.layout_id 
                 WHERE lm.code LIKE 'review_view%'";
$layout_result = $mysqli->query($layout_query);

if ($layout_result && $layout_result->num_rows > 0) {
    echo "<p class='ok'>✓ Found " . $layout_result->num_rows . " layout assignment(s)</p>\n";
    echo "<table>\n";
    echo "<tr><th>Layout</th><th>Position</th><th>Code</th><th>Sort Order</th></tr>\n";
    while ($row = $layout_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['layout_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['position']) . "</td>";
        echo "<td>" . htmlspecialchars($row['code']) . "</td>";
        echo "<td>" . htmlspecialchars($row['sort_order']) . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
} else {
    echo "<p class='warning'>⚠ Module is not assigned to any layout</p>\n";
    echo "<p>You need to:</p>\n";
    echo "<ol>\n";
    echo "<li>Go to Admin Panel → Extensions → Modules</li>\n";
    echo "<li>Edit your Review View module</li>\n";
    echo "<li>Scroll down and assign it to a layout position (e.g., Content Top, Home, etc.)</li>\n";
    echo "</ol>\n";
}

$mysqli->close();

echo "<hr>\n";
echo "<h3>Summary</h3>\n";
echo "<p>If the module is still not showing:</p>\n";
echo "<ol>\n";
echo "<li>Make sure you have reviews in the database</li>\n";
echo "<li>Make sure reviews have status = 1 (approved)</li>\n";
echo "<li>Create a Review View module instance in admin panel</li>\n";
echo "<li>Select at least one review in the module settings</li>\n";
echo "<li>Enable the module (Status = Enabled)</li>\n";
echo "<li>Assign the module to a layout position</li>\n";
echo "<li>Clear cache (system/cache/*)</li>\n";
echo "</ol>\n";
?>

