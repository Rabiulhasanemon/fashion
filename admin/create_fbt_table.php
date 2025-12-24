<?php
/**
 * Create Frequently Bought Together Table
 * Access: https://ruplexa1.master.com.bd/admin/create_fbt_table.php
 * This script will create the FBT table if it doesn't exist
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Create FBT Table</title>";
echo "<style>body { font-family: Arial; margin: 20px; } .success { color: green; font-weight: bold; } .error { color: red; font-weight: bold; } .info { color: blue; } pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow: auto; }</style>";
echo "</head><body>";
echo "<h1>Create Frequently Bought Together Table</h1>";

try {
    // Load OpenCart
    require_once(__DIR__ . '/config.php');
    echo "<p class='info'>✓ config.php loaded</p>";
    
    require_once(DIR_SYSTEM . 'startup.php');
    echo "<p class='info'>✓ startup.php loaded</p>";
    
    // Database connection
    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    echo "<p class='info'>✓ Database connection created</p>";
    
    $prefix = DB_PREFIX;
    $table_name = $prefix . 'product_frequently_bought_together';
    
    // Check if table already exists
    $table_check = $db->query("SHOW TABLES LIKE '" . $table_name . "'");
    
    if ($table_check && $table_check->num_rows > 0) {
        echo "<p class='success'>✓ Table already exists: <strong>" . $table_name . "</strong></p>";
        echo "<p><a href='check_fbt_table.php'>Go to Diagnostic Page</a></p>";
    } else {
        echo "<p class='info'>Creating table: <strong>" . $table_name . "</strong></p>";
        
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
        
        echo "<pre>" . htmlspecialchars($create_sql) . "</pre>";
        
        $create_result = $db->query($create_sql);
        
        if ($create_result) {
            echo "<p class='success'>✓✓✓ Table created successfully! ✓✓✓</p>";
            
            // Verify table was created
            $verify_check = $db->query("SHOW TABLES LIKE '" . $table_name . "'");
            if ($verify_check && $verify_check->num_rows > 0) {
                echo "<p class='success'>✓ Table verified and ready to use!</p>";
                
                // Show table structure
                $structure = $db->query("DESCRIBE " . $table_name);
                if ($structure && $structure->num_rows > 0) {
                    echo "<h3>Table Structure:</h3>";
                    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
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
                
                echo "<p class='success' style='font-size: 18px; margin-top: 20px;'>🎉 You can now use the Frequently Bought Together feature in the product edit page!</p>";
                echo "<p><a href='check_fbt_table.php' style='background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Go to Diagnostic Page</a></p>";
            } else {
                echo "<p class='error'>⚠ Warning: Table creation reported success but verification failed. Please check manually.</p>";
            }
        } else {
            echo "<p class='error'>❌ Error creating table. Please check database permissions and try again.</p>";
            echo "<p>You can also run the SQL manually in phpMyAdmin:</p>";
            echo "<pre>" . htmlspecialchars($create_sql) . "</pre>";
        }
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
?>




