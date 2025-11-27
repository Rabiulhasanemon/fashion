<?php
// Deep debug - check for hidden records and table constraints
// Access via: https://ruplexa1.master.com.bd/admin/debug_manufacturer_deep.php

require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

$registry = new Registry();
$config = new Config();
$registry->set('config', $config);

$db = new \DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

echo "<h1>Deep Manufacturer Debug - Hidden Records & Constraints</h1>";
echo "<style>
body { font-family: Arial; margin: 20px; } 
.success { color: green; font-weight: bold; } 
.error { color: red; font-weight: bold; } 
.info { color: blue; } 
pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
table { border-collapse: collapse; width: 100%; margin: 10px 0; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
</style>";

$prefix = DB_PREFIX;
$table = $prefix . 'manufacturer';

// 1. Check for ANY record with ID 0 (including NULL or empty)
echo "<h2>1. Check for Records with ID 0 (All Methods)</h2>";

$checks = array(
    "manufacturer_id = 0" => "SELECT * FROM `{$table}` WHERE manufacturer_id = 0",
    "manufacturer_id <= 0" => "SELECT * FROM `{$table}` WHERE manufacturer_id <= 0",
    "manufacturer_id IS NULL" => "SELECT * FROM `{$table}` WHERE manufacturer_id IS NULL",
    "CAST(manufacturer_id AS CHAR) = '0'" => "SELECT * FROM `{$table}` WHERE CAST(manufacturer_id AS CHAR) = '0'",
);

foreach ($checks as $check_name => $sql) {
    $result = $db->query($sql);
    $count = $result ? $result->num_rows : 0;
    if ($count > 0) {
        echo "<p class='error'>❌ Found {$count} record(s) with: {$check_name}</p>";
        echo "<table>";
        echo "<tr><th>manufacturer_id</th><th>name</th><th>image</th><th>sort_order</th></tr>";
        foreach ($result->rows as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['manufacturer_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['image']) . "</td>";
            echo "<td>" . htmlspecialchars($row['sort_order']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='success'>✓ No records found with: {$check_name}</p>";
    }
}

// 2. Check table structure and constraints
echo "<h2>2. Table Structure & Constraints</h2>";
$structure = $db->query("SHOW CREATE TABLE `{$table}`");
if ($structure && $structure->num_rows) {
    echo "<pre>" . htmlspecialchars($structure->row['Create Table']) . "</pre>";
}

// 3. Check indexes
echo "<h2>3. Table Indexes</h2>";
$indexes = $db->query("SHOW INDEXES FROM `{$table}`");
if ($indexes && $indexes->num_rows) {
    echo "<table>";
    echo "<tr><th>Key_name</th><th>Column_name</th><th>Non_unique</th><th>Seq_in_index</th></tr>";
    foreach ($indexes->rows as $idx) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($idx['Key_name']) . "</td>";
        echo "<td>" . htmlspecialchars($idx['Column_name']) . "</td>";
        echo "<td>" . htmlspecialchars($idx['Non_unique']) . "</td>";
        echo "<td>" . htmlspecialchars($idx['Seq_in_index']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// 4. Check AUTO_INCREMENT
echo "<h2>4. AUTO_INCREMENT Status</h2>";
$status = $db->query("SHOW TABLE STATUS LIKE '{$table}'");
if ($status && $status->num_rows) {
    $row = $status->row;
    $ai = isset($row['Auto_increment']) ? $row['Auto_increment'] : (isset($row['AUTO_INCREMENT']) ? $row['AUTO_INCREMENT'] : 'N/A');
    echo "<p>AUTO_INCREMENT: <strong>{$ai}</strong></p>";
    
    $max_id = $db->query("SELECT MAX(manufacturer_id) as max_id FROM `{$table}` WHERE manufacturer_id > 0");
    $max = $max_id && $max_id->num_rows ? (int)$max_id->row['max_id'] : 0;
    echo "<p>Max manufacturer_id: <strong>{$max}</strong></p>";
    
    if ($ai !== 'N/A' && (int)$ai <= $max) {
        echo "<p class='error'>❌ AUTO_INCREMENT ({$ai}) is less than or equal to max ID ({$max})!</p>";
    }
}

// 5. Try a direct insert to see what happens
echo "<h2>5. Test Direct Insert</h2>";
$test_name = 'DEEP_TEST_' . time();
$max_check = $db->query("SELECT MAX(manufacturer_id) as max_id FROM `{$table}` WHERE manufacturer_id > 0");
$next_id = 1;
if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
    $next_id = (int)$max_check->row['max_id'] + 1;
}

// Clean up any test records
$db->query("DELETE FROM `{$table}` WHERE name LIKE 'DEEP_TEST_%'");

echo "<p>Attempting insert with manufacturer_id = {$next_id}</p>";
$test_sql = "INSERT INTO `{$table}` SET manufacturer_id = '{$next_id}', name = '" . $db->escape($test_name) . "', image = '', thumb = '', sort_order = '0'";
echo "<p>SQL: <code>" . htmlspecialchars($test_sql) . "</code></p>";

$result = $db->query($test_sql);

if ($result) {
    echo "<p class='success'>✓ Insert successful!</p>";
    $inserted_id = $db->getLastId();
    echo "<p>getLastId() returned: <strong>{$inserted_id}</strong></p>";
    
    // Check what was actually inserted
    $verify = $db->query("SELECT * FROM `{$table}` WHERE manufacturer_id = '{$next_id}'");
    if ($verify && $verify->num_rows) {
        echo "<p class='success'>✓ Record found with ID: {$next_id}</p>";
        $db->query("DELETE FROM `{$table}` WHERE manufacturer_id = '{$next_id}'");
    } else {
        echo "<p class='error'>❌ Record not found with ID: {$next_id}</p>";
        
        // Check if it was inserted with ID 0
        $check_zero = $db->query("SELECT * FROM `{$table}` WHERE name = '" . $db->escape($test_name) . "'");
        if ($check_zero && $check_zero->num_rows) {
            echo "<p class='error'>❌ Record was inserted with manufacturer_id = " . $check_zero->row['manufacturer_id'] . "</p>";
            $db->query("DELETE FROM `{$table}` WHERE name = '" . $db->escape($test_name) . "'");
        }
    }
} else {
    echo "<p class='error'>❌ Insert failed!</p>";
    
    // Get error
    $error = '';
    $errno = 0;
    if (property_exists($db, 'link') && is_object($db->link)) {
        if (property_exists($db->link, 'error')) {
            $error = $db->link->error;
        }
        if (property_exists($db->link, 'errno')) {
            $errno = $db->link->errno;
        }
    }
    echo "<p class='error'>MySQL Error: <strong>" . htmlspecialchars($error) . "</strong> (Code: {$errno})</p>";
}

// 6. Check for foreign key constraints
echo "<h2>6. Foreign Key Constraints</h2>";
$fks = $db->query("SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '{$table}' AND REFERENCED_TABLE_NAME IS NOT NULL");
if ($fks && $fks->num_rows) {
    echo "<p class='info'>Found foreign key constraints:</p>";
    echo "<pre>" . print_r($fks->rows, true) . "</pre>";
} else {
    echo "<p class='success'>✓ No foreign key constraints found</p>";
}

echo "<hr>";
echo "<p><strong>Deep debug complete.</strong></p>";


// Deep debug - check for hidden records and table constraints
// Access via: https://ruplexa1.master.com.bd/admin/debug_manufacturer_deep.php

require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

$registry = new Registry();
$config = new Config();
$registry->set('config', $config);

$db = new \DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

echo "<h1>Deep Manufacturer Debug - Hidden Records & Constraints</h1>";
echo "<style>
body { font-family: Arial; margin: 20px; } 
.success { color: green; font-weight: bold; } 
.error { color: red; font-weight: bold; } 
.info { color: blue; } 
pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
table { border-collapse: collapse; width: 100%; margin: 10px 0; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
</style>";

$prefix = DB_PREFIX;
$table = $prefix . 'manufacturer';

// 1. Check for ANY record with ID 0 (including NULL or empty)
echo "<h2>1. Check for Records with ID 0 (All Methods)</h2>";

$checks = array(
    "manufacturer_id = 0" => "SELECT * FROM `{$table}` WHERE manufacturer_id = 0",
    "manufacturer_id <= 0" => "SELECT * FROM `{$table}` WHERE manufacturer_id <= 0",
    "manufacturer_id IS NULL" => "SELECT * FROM `{$table}` WHERE manufacturer_id IS NULL",
    "CAST(manufacturer_id AS CHAR) = '0'" => "SELECT * FROM `{$table}` WHERE CAST(manufacturer_id AS CHAR) = '0'",
);

foreach ($checks as $check_name => $sql) {
    $result = $db->query($sql);
    $count = $result ? $result->num_rows : 0;
    if ($count > 0) {
        echo "<p class='error'>❌ Found {$count} record(s) with: {$check_name}</p>";
        echo "<table>";
        echo "<tr><th>manufacturer_id</th><th>name</th><th>image</th><th>sort_order</th></tr>";
        foreach ($result->rows as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['manufacturer_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['image']) . "</td>";
            echo "<td>" . htmlspecialchars($row['sort_order']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='success'>✓ No records found with: {$check_name}</p>";
    }
}

// 2. Check table structure and constraints
echo "<h2>2. Table Structure & Constraints</h2>";
$structure = $db->query("SHOW CREATE TABLE `{$table}`");
if ($structure && $structure->num_rows) {
    echo "<pre>" . htmlspecialchars($structure->row['Create Table']) . "</pre>";
}

// 3. Check indexes
echo "<h2>3. Table Indexes</h2>";
$indexes = $db->query("SHOW INDEXES FROM `{$table}`");
if ($indexes && $indexes->num_rows) {
    echo "<table>";
    echo "<tr><th>Key_name</th><th>Column_name</th><th>Non_unique</th><th>Seq_in_index</th></tr>";
    foreach ($indexes->rows as $idx) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($idx['Key_name']) . "</td>";
        echo "<td>" . htmlspecialchars($idx['Column_name']) . "</td>";
        echo "<td>" . htmlspecialchars($idx['Non_unique']) . "</td>";
        echo "<td>" . htmlspecialchars($idx['Seq_in_index']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// 4. Check AUTO_INCREMENT
echo "<h2>4. AUTO_INCREMENT Status</h2>";
$status = $db->query("SHOW TABLE STATUS LIKE '{$table}'");
if ($status && $status->num_rows) {
    $row = $status->row;
    $ai = isset($row['Auto_increment']) ? $row['Auto_increment'] : (isset($row['AUTO_INCREMENT']) ? $row['AUTO_INCREMENT'] : 'N/A');
    echo "<p>AUTO_INCREMENT: <strong>{$ai}</strong></p>";
    
    $max_id = $db->query("SELECT MAX(manufacturer_id) as max_id FROM `{$table}` WHERE manufacturer_id > 0");
    $max = $max_id && $max_id->num_rows ? (int)$max_id->row['max_id'] : 0;
    echo "<p>Max manufacturer_id: <strong>{$max}</strong></p>";
    
    if ($ai !== 'N/A' && (int)$ai <= $max) {
        echo "<p class='error'>❌ AUTO_INCREMENT ({$ai}) is less than or equal to max ID ({$max})!</p>";
    }
}

// 5. Try a direct insert to see what happens
echo "<h2>5. Test Direct Insert</h2>";
$test_name = 'DEEP_TEST_' . time();
$max_check = $db->query("SELECT MAX(manufacturer_id) as max_id FROM `{$table}` WHERE manufacturer_id > 0");
$next_id = 1;
if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
    $next_id = (int)$max_check->row['max_id'] + 1;
}

// Clean up any test records
$db->query("DELETE FROM `{$table}` WHERE name LIKE 'DEEP_TEST_%'");

echo "<p>Attempting insert with manufacturer_id = {$next_id}</p>";
$test_sql = "INSERT INTO `{$table}` SET manufacturer_id = '{$next_id}', name = '" . $db->escape($test_name) . "', image = '', thumb = '', sort_order = '0'";
echo "<p>SQL: <code>" . htmlspecialchars($test_sql) . "</code></p>";

$result = $db->query($test_sql);

if ($result) {
    echo "<p class='success'>✓ Insert successful!</p>";
    $inserted_id = $db->getLastId();
    echo "<p>getLastId() returned: <strong>{$inserted_id}</strong></p>";
    
    // Check what was actually inserted
    $verify = $db->query("SELECT * FROM `{$table}` WHERE manufacturer_id = '{$next_id}'");
    if ($verify && $verify->num_rows) {
        echo "<p class='success'>✓ Record found with ID: {$next_id}</p>";
        $db->query("DELETE FROM `{$table}` WHERE manufacturer_id = '{$next_id}'");
    } else {
        echo "<p class='error'>❌ Record not found with ID: {$next_id}</p>";
        
        // Check if it was inserted with ID 0
        $check_zero = $db->query("SELECT * FROM `{$table}` WHERE name = '" . $db->escape($test_name) . "'");
        if ($check_zero && $check_zero->num_rows) {
            echo "<p class='error'>❌ Record was inserted with manufacturer_id = " . $check_zero->row['manufacturer_id'] . "</p>";
            $db->query("DELETE FROM `{$table}` WHERE name = '" . $db->escape($test_name) . "'");
        }
    }
} else {
    echo "<p class='error'>❌ Insert failed!</p>";
    
    // Get error
    $error = '';
    $errno = 0;
    if (property_exists($db, 'link') && is_object($db->link)) {
        if (property_exists($db->link, 'error')) {
            $error = $db->link->error;
        }
        if (property_exists($db->link, 'errno')) {
            $errno = $db->link->errno;
        }
    }
    echo "<p class='error'>MySQL Error: <strong>" . htmlspecialchars($error) . "</strong> (Code: {$errno})</p>";
}

// 6. Check for foreign key constraints
echo "<h2>6. Foreign Key Constraints</h2>";
$fks = $db->query("SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '{$table}' AND REFERENCED_TABLE_NAME IS NOT NULL");
if ($fks && $fks->num_rows) {
    echo "<p class='info'>Found foreign key constraints:</p>";
    echo "<pre>" . print_r($fks->rows, true) . "</pre>";
} else {
    echo "<p class='success'>✓ No foreign key constraints found</p>";
}

echo "<hr>";
echo "<p><strong>Deep debug complete.</strong></p>";

