<?php
// View manufacturer error log
// Access via: https://ruplexa1.master.com.bd/admin/view_manufacturer_log.php

require_once('config.php');

$log_file = DIR_LOGS . 'manufacturer_error.log';

echo "<h1>Manufacturer Error Log</h1>";
echo "<style>
body { font-family: Arial; margin: 20px; } 
pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; white-space: pre-wrap; word-wrap: break-word; }
.error-line { color: red; }
.success-line { color: green; }
.warning-line { color: orange; }
</style>";

if (file_exists($log_file)) {
    $log_content = file_get_contents($log_file);
    $lines = explode("\n", $log_content);
    
    echo "<p><strong>Log file:</strong> " . htmlspecialchars($log_file) . "</p>";
    echo "<p><strong>File size:</strong> " . filesize($log_file) . " bytes</p>";
    echo "<p><strong>Last modified:</strong> " . date('Y-m-d H:i:s', filemtime($log_file)) . "</p>";
    
    echo "<h2>Log Contents (Last 100 lines):</h2>";
    echo "<pre>";
    
    $recent_lines = array_slice($lines, -100);
    foreach ($recent_lines as $line) {
        $line_class = '';
        if (stripos($line, 'error') !== false || stripos($line, 'failed') !== false || stripos($line, 'critical') !== false) {
            $line_class = 'error-line';
        } elseif (stripos($line, 'success') !== false || stripos($line, '✓') !== false) {
            $line_class = 'success-line';
        } elseif (stripos($line, 'warning') !== false || stripos($line, '⚠') !== false) {
            $line_class = 'warning-line';
        }
        echo "<span class='{$line_class}'>" . htmlspecialchars($line) . "</span>\n";
    }
    
    echo "</pre>";
    
    echo "<h2>Full Log:</h2>";
    echo "<pre>" . htmlspecialchars($log_content) . "</pre>";
    
    echo "<p><a href='?clear=1'>Clear Log</a></p>";
    
    if (isset($_GET['clear']) && $_GET['clear'] == 1) {
        file_put_contents($log_file, '');
        echo "<p style='color: green;'>Log cleared!</p>";
        echo "<script>setTimeout(function(){ window.location.href = 'view_manufacturer_log.php'; }, 1000);</script>";
    }
} else {
    echo "<p style='color: orange;'>Log file does not exist yet. Try adding a manufacturer first.</p>";
    echo "<p><strong>Expected location:</strong> " . htmlspecialchars($log_file) . "</p>";
}


// View manufacturer error log
// Access via: https://ruplexa1.master.com.bd/admin/view_manufacturer_log.php

require_once('config.php');

$log_file = DIR_LOGS . 'manufacturer_error.log';

echo "<h1>Manufacturer Error Log</h1>";
echo "<style>
body { font-family: Arial; margin: 20px; } 
pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; white-space: pre-wrap; word-wrap: break-word; }
.error-line { color: red; }
.success-line { color: green; }
.warning-line { color: orange; }
</style>";

if (file_exists($log_file)) {
    $log_content = file_get_contents($log_file);
    $lines = explode("\n", $log_content);
    
    echo "<p><strong>Log file:</strong> " . htmlspecialchars($log_file) . "</p>";
    echo "<p><strong>File size:</strong> " . filesize($log_file) . " bytes</p>";
    echo "<p><strong>Last modified:</strong> " . date('Y-m-d H:i:s', filemtime($log_file)) . "</p>";
    
    echo "<h2>Log Contents (Last 100 lines):</h2>";
    echo "<pre>";
    
    $recent_lines = array_slice($lines, -100);
    foreach ($recent_lines as $line) {
        $line_class = '';
        if (stripos($line, 'error') !== false || stripos($line, 'failed') !== false || stripos($line, 'critical') !== false) {
            $line_class = 'error-line';
        } elseif (stripos($line, 'success') !== false || stripos($line, '✓') !== false) {
            $line_class = 'success-line';
        } elseif (stripos($line, 'warning') !== false || stripos($line, '⚠') !== false) {
            $line_class = 'warning-line';
        }
        echo "<span class='{$line_class}'>" . htmlspecialchars($line) . "</span>\n";
    }
    
    echo "</pre>";
    
    echo "<h2>Full Log:</h2>";
    echo "<pre>" . htmlspecialchars($log_content) . "</pre>";
    
    echo "<p><a href='?clear=1'>Clear Log</a></p>";
    
    if (isset($_GET['clear']) && $_GET['clear'] == 1) {
        file_put_contents($log_file, '');
        echo "<p style='color: green;'>Log cleared!</p>";
        echo "<script>setTimeout(function(){ window.location.href = 'view_manufacturer_log.php'; }, 1000);</script>";
    }
} else {
    echo "<p style='color: orange;'>Log file does not exist yet. Try adding a manufacturer first.</p>";
    echo "<p><strong>Expected location:</strong> " . htmlspecialchars($log_file) . "</p>";
}

