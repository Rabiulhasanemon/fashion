<?php
// Debug script to check what form data is being sent
error_reporting(E_ALL);
ini_set('display_errors', 1);

// OpenCart bootstrap
require_once('../config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

echo "<h1>Product Form Data Debug</h1>";
echo "<pre>";

$log_file = DIR_LOGS . 'product_form_debug.log';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "POST Data Received:\n";
    echo str_repeat("=", 60) . "\n\n";
    
    // Log all POST data
    file_put_contents($log_file, date('Y-m-d H:i:s') . ' ========== FORM SUBMISSION DEBUG ==========' . PHP_EOL, FILE_APPEND);
    file_put_contents($log_file, date('Y-m-d H:i:s') . ' - POST keys: ' . implode(', ', array_keys($_POST)) . PHP_EOL, FILE_APPEND);
    
    // Check product_filter
    if (isset($_POST['product_filter'])) {
        echo "product_filter found:\n";
        if (is_array($_POST['product_filter'])) {
            echo "  Type: Array\n";
            echo "  Count: " . count($_POST['product_filter']) . "\n";
            echo "  Values: " . implode(', ', $_POST['product_filter']) . "\n";
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - product_filter (array): ' . print_r($_POST['product_filter'], true) . PHP_EOL, FILE_APPEND);
        } else {
            echo "  Type: " . gettype($_POST['product_filter']) . "\n";
            echo "  Value: " . $_POST['product_filter'] . "\n";
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - product_filter (not array): ' . $_POST['product_filter'] . PHP_EOL, FILE_APPEND);
        }
    } else {
        echo "product_filter: NOT FOUND in POST data\n";
        file_put_contents($log_file, date('Y-m-d H:i:s') . ' - product_filter: NOT FOUND' . PHP_EOL, FILE_APPEND);
    }
    
    // Check product_attribute
    if (isset($_POST['product_attribute'])) {
        echo "\nproduct_attribute found:\n";
        if (is_array($_POST['product_attribute'])) {
            echo "  Type: Array\n";
            echo "  Count: " . count($_POST['product_attribute']) . "\n";
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - product_attribute (array): ' . print_r($_POST['product_attribute'], true) . PHP_EOL, FILE_APPEND);
            
            foreach ($_POST['product_attribute'] as $key => $attr) {
                echo "  Attribute[{$key}]:\n";
                if (is_array($attr)) {
                    echo "    - attribute_id: " . (isset($attr['attribute_id']) ? $attr['attribute_id'] : 'not set') . "\n";
                    if (isset($attr['product_attribute_description']) && is_array($attr['product_attribute_description'])) {
                        foreach ($attr['product_attribute_description'] as $lang_id => $desc) {
                            $text = isset($desc['text']) ? $desc['text'] : '';
                            echo "    - Language {$lang_id}: " . (strlen($text) > 0 ? substr($text, 0, 50) . '...' : '(empty)') . "\n";
                        }
                    }
                }
            }
        } else {
            echo "  Type: " . gettype($_POST['product_attribute']) . "\n";
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - product_attribute (not array): ' . $_POST['product_attribute'] . PHP_EOL, FILE_APPEND);
        }
    } else {
        echo "\nproduct_attribute: NOT FOUND in POST data\n";
        file_put_contents($log_file, date('Y-m-d H:i:s') . ' - product_attribute: NOT FOUND' . PHP_EOL, FILE_APPEND);
    }
    
    // Check all form fields
    echo "\nAll POST fields:\n";
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'product_') === 0 || strpos($key, 'filter') !== false || strpos($key, 'attribute') !== false) {
            if (is_array($value)) {
                echo "  {$key}: Array (" . count($value) . " items)\n";
            } else {
                echo "  {$key}: " . (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value) . "\n";
            }
        }
    }
    
    echo "\n";
    echo "Full POST data logged to: {$log_file}\n";
    
} else {
    echo "This script should be called via POST from the product form.\n";
    echo "\nTo test, add this JavaScript to your product form:\n";
    echo "<script>\n";
    echo "$('#form-product').on('submit', function(e) {\n";
    echo "    var formData = new FormData(this);\n";
    echo "    formData.append('debug', '1');\n";
    echo "    $.ajax({\n";
    echo "        url: 'debug_product_form_data.php',\n";
    echo "        type: 'POST',\n";
    echo "        data: formData,\n";
    echo "        processData: false,\n";
    echo "        contentType: false,\n";
    echo "        success: function(response) {\n";
    echo "            console.log('Form data:', response);\n";
    echo "        }\n";
    echo "    });\n";
    echo "});\n";
    echo "</script>\n";
}

echo "</pre>";

