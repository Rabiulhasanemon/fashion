<?php
// Debug triggers related to manufacturer tables
// Access via: https://ruplexa1.master.com.bd/admin/debug_manufacturer_triggers.php

require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

$registry = new Registry();
$config = new Config();
$registry->set('config', $config);

$db = new \DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

if (!$db) {
    die('Database connection failed');
}

echo "<h1>Manufacturer Table Triggers</h1>";
echo "<style>body { font-family: Arial; margin: 20px; } pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }</style>";

$prefix = DB_PREFIX;
$tables = array(
    $prefix . 'manufacturer',
    $prefix . 'manufacturer_description',
    $prefix . 'manufacturer_to_store',
    $prefix . 'manufacturer_to_layout'
);

foreach ($tables as $table) {
    echo "<h2>Table: {$table}</h2>";
    $triggers = $db->query("SHOW TRIGGERS LIKE '" . $db->escape($table) . "'");
    if ($triggers && $triggers->num_rows > 0) {
        echo "<p>Found {$triggers->num_rows} trigger(s):</p>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Name</th><th>Timing</th><th>Event</th><th>Statement</th><th>Created</th></tr>";
        foreach ($triggers->rows as $trigger) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($trigger['Trigger']) . "</td>";
            echo "<td>" . htmlspecialchars($trigger['Timing']) . "</td>";
            echo "<td>" . htmlspecialchars($trigger['Event']) . "</td>";
            echo "<td><pre>" . htmlspecialchars($trigger['Statement']) . "</pre></td>";
            echo "<td>" . (isset($trigger['Created']) ? htmlspecialchars($trigger['Created']) : 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No triggers found for this table.</p>";
    }
}

echo "<hr>";
echo "<p><strong>Next:</strong> If you find unexpected triggers, they may be forcing manufacturer_id to 0. Remove or adjust them.</p>";


// Debug triggers related to manufacturer tables
// Access via: https://ruplexa1.master.com.bd/admin/debug_manufacturer_triggers.php

require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

$registry = new Registry();
$config = new Config();
$registry->set('config', $config);

$db = new \DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

if (!$db) {
    die('Database connection failed');
}

echo "<h1>Manufacturer Table Triggers</h1>";
echo "<style>body { font-family: Arial; margin: 20px; } pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }</style>";

$prefix = DB_PREFIX;
$tables = array(
    $prefix . 'manufacturer',
    $prefix . 'manufacturer_description',
    $prefix . 'manufacturer_to_store',
    $prefix . 'manufacturer_to_layout'
);

foreach ($tables as $table) {
    echo "<h2>Table: {$table}</h2>";
    $triggers = $db->query("SHOW TRIGGERS LIKE '" . $db->escape($table) . "'");
    if ($triggers && $triggers->num_rows > 0) {
        echo "<p>Found {$triggers->num_rows} trigger(s):</p>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Name</th><th>Timing</th><th>Event</th><th>Statement</th><th>Created</th></tr>";
        foreach ($triggers->rows as $trigger) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($trigger['Trigger']) . "</td>";
            echo "<td>" . htmlspecialchars($trigger['Timing']) . "</td>";
            echo "<td>" . htmlspecialchars($trigger['Event']) . "</td>";
            echo "<td><pre>" . htmlspecialchars($trigger['Statement']) . "</pre></td>";
            echo "<td>" . (isset($trigger['Created']) ? htmlspecialchars($trigger['Created']) : 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No triggers found for this table.</p>";
    }
}

echo "<hr>";
echo "<p><strong>Next:</strong> If you find unexpected triggers, they may be forcing manufacturer_id to 0. Remove or adjust them.</p>";

