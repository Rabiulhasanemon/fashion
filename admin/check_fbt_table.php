<?php
/**
 * Frequently Bought Together Table Diagnostic Script
 * Access: https://ruplexa1.master.com.bd/admin/check_fbt_table.php
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>FBT Table Diagnostic</title>";
echo "<style>body { font-family: Arial; margin: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow: auto; } table { border-collapse: collapse; width: 100%; margin: 10px 0; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } th { background-color: #4CAF50; color: white; }</style>";
echo "</head><body>";
echo "<h1>Frequently Bought Together Table Diagnostic</h1>";

try {
    // Load OpenCart
    require_once(__DIR__ . '/config.php');
    echo "<p class='success'>✓ config.php loaded</p>";
    
    require_once(DIR_SYSTEM . 'startup.php');
    echo "<p class='success'>✓ startup.php loaded</p>";
    
    // Database connection
    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    echo "<p class='success'>✓ Database connection created</p>";
    
    $prefix = DB_PREFIX;
    $table_name = $prefix . 'product_frequently_bought_together';
    
    // Test database connection
    $test_query = $db->query("SELECT 1 as test");
    if ($test_query) {
        echo "<p class='success'>✓ Database query works</p>";
    } else {
        echo "<p class='error'>❌ Database query failed</p>";
        die();
    }
    
    // Check if table exists
    echo "<h2>Table Status</h2>";
    $table_check = $db->query("SHOW TABLES LIKE '" . $table_name . "'");
    
    if ($table_check && $table_check->num_rows > 0) {
        echo "<p class='success'>✓ Table exists: <strong>" . $table_name . "</strong></p>";
        
        // Check table structure
        echo "<h3>Table Structure</h3>";
        $structure = $db->query("DESCRIBE " . $table_name);
        if ($structure && $structure->num_rows > 0) {
            echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
            foreach ($structure->rows as $row) {
                echo "<tr>";
                echo "<td><strong>" . $row['Field'] . "</strong></td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Null'] . "</td>";
                echo "<td>" . $row['Key'] . "</td>";
                echo "<td>" . ($row['Default'] !== null ? $row['Default'] : 'NULL') . "</td>";
                echo "<td>" . $row['Extra'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // Count records
        $count = $db->query("SELECT COUNT(*) as total FROM " . $table_name);
        $total_records = $count && $count->num_rows ? (int)$count->row['total'] : 0;
        echo "<p class='info'>Total FBT records: <strong>" . $total_records . "</strong></p>";
        
        // Show sample records
        if ($total_records > 0) {
            echo "<h3>Recent Records (Last 20)</h3>";
            $samples = $db->query("SELECT * FROM " . $table_name . " ORDER BY id DESC LIMIT 20");
            if ($samples && $samples->num_rows > 0) {
                echo "<table><tr><th>ID</th><th>Product ID</th><th>FBT Product ID</th><th>Sort Order</th><th>Date Added</th></tr>";
                foreach ($samples->rows as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['product_id'] . "</td>";
                    echo "<td>" . $row['fbt_product_id'] . "</td>";
                    echo "<td>" . $row['sort_order'] . "</td>";
                    echo "<td>" . $row['date_added'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
            // Show records by product
            echo "<h3>FBT Products by Main Product</h3>";
            $by_product = $db->query("SELECT product_id, COUNT(*) as fbt_count, GROUP_CONCAT(fbt_product_id ORDER BY sort_order) as fbt_ids FROM " . $table_name . " GROUP BY product_id ORDER BY product_id DESC LIMIT 20");
            if ($by_product && $by_product->num_rows > 0) {
                echo "<table><tr><th>Product ID</th><th>FBT Count</th><th>FBT Product IDs</th></tr>";
                foreach ($by_product->rows as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['product_id'] . "</td>";
                    echo "<td>" . $row['fbt_count'] . "</td>";
                    echo "<td>" . $row['fbt_ids'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
        } else {
            echo "<p class='info'>No records found in the table. Add FBT products via product edit page.</p>";
        }
        
    } else {
        echo "<p class='error'>❌ Table does NOT exist: <strong>" . $table_name . "</strong></p>";
        echo "<h3>Solution: Create the Table</h3>";
        echo "<p>Run this SQL in phpMyAdmin or your database tool:</p>";
        echo "<pre>";
        echo "CREATE TABLE IF NOT EXISTS `" . $table_name . "` (\n";
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
        
        // Offer to create table
        if (isset($_GET['create_table']) && $_GET['create_table'] == '1') {
            echo "<h3>Creating Table...</h3>";
            $create_sql = "CREATE TABLE IF NOT EXISTS `" . $table_name . "` (
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
            
            $create_result = $db->query($create_sql);
            if ($create_result) {
                echo "<p class='success'>✓ Table created successfully! <a href='?'>Refresh page</a></p>";
            } else {
                echo "<p class='error'>❌ Error creating table. Please create it manually using the SQL above.</p>";
            }
        } else {
            echo "<p><a href='?create_table=1' style='background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Create Table Now</a></p>";
        }
    }
    
    // Check recent log entries
    echo "<h2>Recent Log Entries (FBT Related)</h2>";
    $log_file = DIR_LOGS . 'product_insert_debug.log';
    if (file_exists($log_file)) {
        $log_content = file_get_contents($log_file);
        $lines = explode("\n", $log_content);
        $fbt_lines = array_filter($lines, function($line) {
            return stripos($line, 'FBT') !== false || stripos($line, 'frequently') !== false;
        });
        if (count($fbt_lines) > 0) {
            echo "<pre>";
            echo implode("\n", array_slice($fbt_lines, -30)); // Last 30 FBT log entries
            echo "</pre>";
        } else {
            echo "<p class='info'>No FBT-related log entries found.</p>";
        }
    } else {
        echo "<p class='info'>Log file not found: " . $log_file . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</body></html>";
?>

