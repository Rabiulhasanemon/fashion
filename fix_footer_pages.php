<?php
/**
 * Fix Footer Pages - Update Existing Pages to Correct Sort Order
 * Place this in your OpenCart root directory and run via browser
 */

if (!file_exists('config.php')) {
    die("Error: config.php not found. Place this file in OpenCart root directory.");
}

require_once('config.php');

$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, defined('DB_PORT') ? DB_PORT : 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
$language_id = 1; // Change if needed

echo "<!DOCTYPE html><html><head><title>Fix Footer Pages</title>";
echo "<style>body{font-family:Arial;max-width:800px;margin:50px auto;padding:20px;}";
echo "pre{background:#f5f5f5;padding:15px;border-radius:5px;}";
echo ".success{color:green;} .error{color:red;}</style></head><body>";
echo "<h2>Fixing Footer Pages...</h2><pre>";

// Step 1: Disable all existing footer pages
$conn->query("UPDATE " . DB_PREFIX . "information SET bottom = 0 WHERE bottom = 1");
echo "✓ Disabled all existing footer pages\n\n";

// Step 2: Define pages with their sort_order
$pages = array(
    // About Ruplexa (1-5)
    array('title' => 'About Us', 'sort' => 1),
    array('title' => 'Blog', 'sort' => 2),
    array('title' => 'Careers', 'sort' => 3),
    array('title' => 'Gift cards', 'sort' => 4),
    array('title' => 'Beauty With Heart', 'sort' => 5),
    
    // My Ruplexa (6-9)
    array('title' => 'Beauty Insider', 'sort' => 6),
    array('title' => 'Beauty Offer', 'sort' => 7),
    array('title' => 'Buying Guides', 'sort' => 8),
    array('title' => 'Reward Point', 'sort' => 9),
    
    // Help (10-18)
    array('title' => 'Customer Service', 'sort' => 10),
    array('title' => 'Return and exchanges', 'sort' => 11),
    array('title' => 'Delivery and Pickup Options', 'sort' => 12),
    array('title' => 'Shipping', 'sort' => 13),
    array('title' => 'Billing', 'sort' => 14),
    array('title' => 'Privacy Policy', 'sort' => 15),
    array('title' => 'Terms and Condition', 'sort' => 16),
    array('title' => 'Beauty Service FAQ', 'sort' => 17),
    array('title' => 'Contact Us', 'sort' => 18),
);

$updated = 0;
$created = 0;

foreach ($pages as $page) {
    $title = $conn->real_escape_string($page['title']);
    $sort = $page['sort'];
    
    // Check if page exists
    $check = $conn->query("SELECT i.information_id FROM " . DB_PREFIX . "information i 
        INNER JOIN " . DB_PREFIX . "information_description id ON i.information_id = id.information_id 
        WHERE id.title = '$title' AND id.language_id = $language_id LIMIT 1");
    
    if ($check && $check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $info_id = $row['information_id'];
        
        // Update existing page
        $conn->query("UPDATE " . DB_PREFIX . "information SET sort_order = $sort, bottom = 1, status = 1 WHERE information_id = $info_id");
        echo "<span class='success'>✓</span> Updated: $title (sort_order: $sort)\n";
        $updated++;
    } else {
        // Create new page
        $conn->query("INSERT INTO " . DB_PREFIX . "information (sort_order, bottom, status) VALUES ($sort, 1, 1)");
        $info_id = $conn->insert_id;
        
        $description = '<div class="ruplexa-info-content"><h3>' . $title . '</h3><p>Content for ' . $title . ' page.</p></div>';
        $meta_title = $title . ' - Ruplexa';
        
        $conn->query("INSERT INTO " . DB_PREFIX . "information_description 
            (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
            VALUES ($info_id, $language_id, '$title', '" . $conn->real_escape_string($description) . "', '$meta_title', '$meta_title', '')");
        
        $conn->query("INSERT IGNORE INTO " . DB_PREFIX . "information_to_store (information_id, store_id) VALUES ($info_id, 0)");
        
        echo "<span class='success'>✓</span> Created: $title (sort_order: $sort)\n";
        $created++;
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "<span class='success'>Summary:</span>\n";
echo "  Updated: $updated pages\n";
echo "  Created: $created pages\n";
echo "\n<span class='success'>✓ Footer pages are now correctly configured!</span>\n";
echo "\nThe footer will now show:\n";
echo "- About Ruplexa: About Us, Blog, Careers, Gift cards, Beauty With Heart\n";
echo "- My Ruplexa: Beauty Insider, Beauty Offer, Buying Guides, Reward Point + Specials, Wish List, Order History, My Account\n";
echo "- Help: Customer Service, Return and exchanges, Delivery and Pickup Options, Shipping, Billing, Privacy Policy, Terms and Condition, Beauty Service FAQ, Contact Us\n";

echo "</pre></body></html>";

$conn->close();
?>


