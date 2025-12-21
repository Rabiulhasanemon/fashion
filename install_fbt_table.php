<?php
// Installation script for Frequently Bought Together table
// Run this file once to create the table

// Database configuration - UPDATE THESE VALUES
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'your_database_name');
define('DB_PREFIX', 'oc_');

// Create connection
$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_frequently_bought_together` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `fbt_product_id` int(11) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `fbt_product_id` (`fbt_product_id`),
  UNIQUE KEY `unique_product_fbt` (`product_id`, `fbt_product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table 'product_frequently_bought_together' created successfully!<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$conn->close();
echo "Installation complete!";
?>


