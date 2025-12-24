<?php
/**
 * Install Footer Information Pages for Ruplexa
 * 
 * This script creates all the information pages needed for the footer
 * Run this file once through your browser or command line
 * 
 * Usage: 
 * - Via browser: http://yourdomain.com/install_footer_pages.php
 * - Via command line: php install_footer_pages.php
 */

// Database configuration - Update these values
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'your_database_name');
define('DB_PREFIX', 'sr_');
define('DB_PORT', '3306');

// Language and Store IDs - Update if needed
define('LANGUAGE_ID', 1);
define('STORE_ID', 0);

// Connect to database
try {
    $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
    
    echo "<h2>Installing Footer Information Pages...</h2>";
    echo "<pre>";
    
    $sort_order = 1;
    $pages = array(
        // About Ruplexa Section (sort_order 1-5)
        array('title' => 'About Us', 'description' => '<p>Learn more about Ruplexa and our mission to provide quality beauty products.</p>', 'meta_title' => 'About Us - Ruplexa', 'meta_description' => 'Learn more about Ruplexa', 'meta_keyword' => 'about us, ruplexa'),
        array('title' => 'Blog', 'description' => '<p>Read our latest beauty tips, trends, and product reviews.</p>', 'meta_title' => 'Blog - Ruplexa', 'meta_description' => 'Beauty blog and tips', 'meta_keyword' => 'blog, beauty tips'),
        array('title' => 'Careers', 'description' => '<p>Join the Ruplexa team and help us bring beauty to everyone.</p>', 'meta_title' => 'Careers - Ruplexa', 'meta_description' => 'Career opportunities at Ruplexa', 'meta_keyword' => 'careers, jobs'),
        array('title' => 'Gift cards', 'description' => '<p>Purchase gift cards for your loved ones.</p>', 'meta_title' => 'Gift Cards - Ruplexa', 'meta_description' => 'Buy gift cards', 'meta_keyword' => 'gift cards'),
        array('title' => 'Beauty With Heart', 'description' => '<p>Our commitment to social responsibility and giving back.</p>', 'meta_title' => 'Beauty With Heart - Ruplexa', 'meta_description' => 'Social responsibility', 'meta_keyword' => 'beauty with heart'),
        
        // My Ruplexa Section (sort_order 6-9)
        array('title' => 'Beauty Insider', 'description' => '<p>Join our Beauty Insider program for exclusive benefits.</p>', 'meta_title' => 'Beauty Insider - Ruplexa', 'meta_description' => 'Beauty Insider program', 'meta_keyword' => 'beauty insider'),
        array('title' => 'Beauty Offer', 'description' => '<p>Check out our current beauty offers and promotions.</p>', 'meta_title' => 'Beauty Offer - Ruplexa', 'meta_description' => 'Beauty offers and promotions', 'meta_keyword' => 'beauty offer'),
        array('title' => 'Buying Guides', 'description' => '<p>Expert guides to help you choose the right beauty products.</p>', 'meta_title' => 'Buying Guides - Ruplexa', 'meta_description' => 'Product buying guides', 'meta_keyword' => 'buying guides'),
        array('title' => 'Reward Point', 'description' => '<p>Learn about our reward points program and how to earn points.</p>', 'meta_title' => 'Reward Points - Ruplexa', 'meta_description' => 'Reward points program', 'meta_keyword' => 'reward points'),
        
        // Help Section (sort_order 10+)
        array('title' => 'Customer Service', 'description' => '<p>Get help with your orders and account.</p>', 'meta_title' => 'Customer Service - Ruplexa', 'meta_description' => 'Customer service support', 'meta_keyword' => 'customer service'),
        array('title' => 'Return and exchanges', 'description' => '<p>Learn about our return and exchange policy.</p>', 'meta_title' => 'Returns and Exchanges - Ruplexa', 'meta_description' => 'Return policy', 'meta_keyword' => 'returns, exchanges'),
        array('title' => 'Delivery and Pickup Options', 'description' => '<p>Information about delivery and pickup options.</p>', 'meta_title' => 'Delivery Options - Ruplexa', 'meta_description' => 'Delivery and pickup', 'meta_keyword' => 'delivery, pickup'),
        array('title' => 'Shipping', 'description' => '<p>Shipping information and rates.</p>', 'meta_title' => 'Shipping - Ruplexa', 'meta_description' => 'Shipping information', 'meta_keyword' => 'shipping'),
        array('title' => 'Billing', 'description' => '<p>Billing and payment information.</p>', 'meta_title' => 'Billing - Ruplexa', 'meta_description' => 'Billing information', 'meta_keyword' => 'billing'),
        array('title' => 'Privacy Policy', 'description' => '<p>Our privacy policy and how we protect your data.</p>', 'meta_title' => 'Privacy Policy - Ruplexa', 'meta_description' => 'Privacy policy', 'meta_keyword' => 'privacy policy'),
        array('title' => 'Terms and Condition', 'description' => '<p>Terms and conditions of use.</p>', 'meta_title' => 'Terms and Conditions - Ruplexa', 'meta_description' => 'Terms and conditions', 'meta_keyword' => 'terms and conditions'),
        array('title' => 'Beauty Service FAQ', 'description' => '<p>Frequently asked questions about our beauty services.</p>', 'meta_title' => 'Beauty Service FAQ - Ruplexa', 'meta_description' => 'Beauty service FAQ', 'meta_keyword' => 'faq, beauty service'),
        array('title' => 'Contact Us', 'description' => '<p>Get in touch with us. We are here to help.</p>', 'meta_title' => 'Contact Us - Ruplexa', 'meta_description' => 'Contact Ruplexa', 'meta_keyword' => 'contact us'),
    );
    
    foreach ($pages as $page) {
        // Escape data
        $title = $conn->real_escape_string($page['title']);
        $description = $conn->real_escape_string($page['description']);
        $meta_title = $conn->real_escape_string($page['meta_title']);
        $meta_description = $conn->real_escape_string($page['meta_description']);
        $meta_keyword = $conn->real_escape_string($page['meta_keyword']);
        
        // Check if page already exists
        $check_query = "SELECT information_id FROM " . DB_PREFIX . "information_description WHERE title = '$title' AND language_id = " . LANGUAGE_ID . " LIMIT 1";
        $check_result = $conn->query($check_query);
        
        if ($check_result && $check_result->num_rows > 0) {
            echo "Page '$title' already exists. Skipping...\n";
            continue;
        }
        
        // Insert into information table
        $info_query = "INSERT INTO " . DB_PREFIX . "information (sort_order, bottom, status) VALUES ($sort_order, 1, 1)";
        if ($conn->query($info_query)) {
            $information_id = $conn->insert_id;
            echo "Created information page: $title (ID: $information_id)\n";
            
            // Insert description
            $desc_query = "INSERT INTO " . DB_PREFIX . "information_description 
                (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
                VALUES ($information_id, " . LANGUAGE_ID . ", '$title', '$description', '$meta_title', '$meta_description', '$meta_keyword')";
            
            if ($conn->query($desc_query)) {
                echo "  - Added description for: $title\n";
            } else {
                echo "  - ERROR adding description: " . $conn->error . "\n";
            }
            
            // Link to store
            $store_query = "INSERT INTO " . DB_PREFIX . "information_to_store (information_id, store_id) VALUES ($information_id, " . STORE_ID . ")";
            if ($conn->query($store_query)) {
                echo "  - Linked to store\n";
            } else {
                echo "  - ERROR linking to store: " . $conn->error . "\n";
            }
            
            $sort_order++;
        } else {
            echo "ERROR creating page '$title': " . $conn->error . "\n";
        }
    }
    
    echo "\n\nInstallation completed!\n";
    echo "Total pages created: " . ($sort_order - 1) . "\n";
    echo "\nYou can now:\n";
    echo "1. Go to Admin Panel > Catalog > Information\n";
    echo "2. Edit each page to customize the content\n";
    echo "3. The pages will automatically appear in the footer\n";
    
    echo "</pre>";
    
    $conn->close();
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

