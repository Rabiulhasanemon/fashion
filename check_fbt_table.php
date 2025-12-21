<?php
// Diagnostic script to check FBT table and test functionality
// Access via: http://your-site.com/admin/check_fbt_table.php

// Include OpenCart configuration
require_once('config.php');

// Check if table exists
$table_check = $db->query("SHOW TABLES LIKE '" . DB_PREFIX . "product_frequently_bought_together'");

echo "<h2>Frequently Bought Together Table Diagnostic</h2>";

if ($table_check && $table_check->num_rows > 0) {
    echo "<p style='color: green;'>✓ Table exists: " . DB_PREFIX . "product_frequently_bought_together</p>";
    
    // Check table structure
    $structure = $db->query("DESCRIBE " . DB_PREFIX . "product_frequently_bought_together");
    echo "<h3>Table Structure:</h3><ul>";
    foreach ($structure->rows as $row) {
        echo "<li>" . $row['Field'] . " (" . $row['Type'] . ")</li>";
    }
    echo "</ul>";
    
    // Count records
    $count = $db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_frequently_bought_together");
    echo "<p>Total FBT records: " . ($count->row['total']) . "</p>";
    
    // Show sample records
    $samples = $db->query("SELECT * FROM " . DB_PREFIX . "product_frequently_bought_together ORDER BY id DESC LIMIT 10");
    if ($samples->num_rows > 0) {
        echo "<h3>Recent Records:</h3><table border='1' cellpadding='5'><tr><th>ID</th><th>Product ID</th><th>FBT Product ID</th><th>Sort Order</th><th>Date Added</th></tr>";
        foreach ($samples->rows as $row) {
            echo "<tr><td>" . $row['id'] . "</td><td>" . $row['product_id'] . "</td><td>" . $row['fbt_product_id'] . "</td><td>" . $row['sort_order'] . "</td><td>" . $row['date_added'] . "</td></tr>";
        }
        echo "</table>";
    }
    
} else {
    echo "<p style='color: red;'>✗ Table does NOT exist: " . DB_PREFIX . "product_frequently_bought_together</p>";
    echo "<p><strong>Solution:</strong> Run the SQL file: <code>create_fbt_table.sql</code></p>";
    echo "<p>Or use phpMyAdmin to execute:</p>";
    echo "<pre>";
    echo "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_frequently_bought_together` (\n";
    echo "  `id` int(11) NOT NULL AUTO_INCREMENT,\n";
    echo "  `product_id` int(11) NOT NULL,\n";
    echo "  `fbt_product_id` int(11) NOT NULL,\n";
    echo "  `sort_order` int(3) NOT NULL DEFAULT '0',\n";
    echo "  `date_added` datetime NOT NULL,\n";
    echo "  PRIMARY KEY (`id`),\n";
    echo "  KEY `product_id` (`product_id`),\n";
    echo "  KEY `fbt_product_id` (`fbt_product_id`),\n";
    echo "  UNIQUE KEY `unique_product_fbt` (`product_id`, `fbt_product_id`)\n";
    echo ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
    echo "</pre>";
}

// Check recent log entries
$log_file = DIR_LOGS . 'product_insert_debug.log';
if (file_exists($log_file)) {
    echo "<h3>Recent Log Entries (FBT related):</h3>";
    $log_content = file_get_contents($log_file);
    $lines = explode("\n", $log_content);
    $fbt_lines = array_filter($lines, function($line) {
        return stripos($line, 'FBT') !== false;
    });
    if (count($fbt_lines) > 0) {
        echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 300px; overflow: auto;'>";
        echo implode("\n", array_slice($fbt_lines, -20)); // Last 20 FBT log entries
        echo "</pre>";
    } else {
        echo "<p>No FBT-related log entries found.</p>";
    }
} else {
    echo "<p>Log file not found: " . $log_file . "</p>";
}
?>

