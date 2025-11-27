<?php
// Clear manufacturer error log
// Access via: https://ruplexa1.master.com.bd/admin/clear_manufacturer_log.php

require_once('config.php');

$log_file = DIR_LOGS . 'manufacturer_error.log';

echo "<h1>Clear Manufacturer Error Log</h1>";
echo "<style>body { font-family: Arial; margin: 20px; } .success { color: green; font-weight: bold; } .info { color: blue; }</style>";

if (file_exists($log_file)) {
    // Clear the log
    file_put_contents($log_file, '');
    echo "<p class='success'>✓ Log file cleared successfully!</p>";
    echo "<p class='info'>File: " . htmlspecialchars($log_file) . "</p>";
    echo "<p class='info'>File size: " . filesize($log_file) . " bytes</p>";
} else {
    echo "<p class='info'>Log file does not exist yet. It will be created when you try to add a manufacturer.</p>";
    echo "<p class='info'>Expected location: " . htmlspecialchars($log_file) . "</p>";
}

echo "<hr>";
echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li><strong>Try adding a manufacturer</strong> through the admin panel:<br>";
echo "Go to: <strong>Catalog > Manufacturers > Add</strong><br>";
echo "Fill in the manufacturer name and click <strong>Save</strong></li>";
echo "<li><strong>Check the error log</strong> immediately after:<br>";
echo "<a href='view_manufacturer_log.php' target='_blank'>View Error Log</a></li>";
echo "<li><strong>Or test the model directly:</strong><br>";
echo "<a href='test_manufacturer_add_real.php' target='_blank'>Test Manufacturer Add</a></li>";
echo "</ol>";

echo "<p><strong>Log cleared! Ready for testing.</strong></p>";

