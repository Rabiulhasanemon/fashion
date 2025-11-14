<?php
// Test Review View Module Frontend Loading
// This simulates how the module is loaded on the frontend

// Start OpenCart
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Settings
$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0'");
foreach ($query->rows as $result) {
	if (!$result['serialized']) {
		$config->set($result['key'], $result['value']);
	} else {
		$config->set($result['key'], unserialize($result['value']));
	}
}

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// URL
$url = new Url($config->get('config_url'));
$registry->set('url', $url);

// Log
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

// Language
$language = new Language($config->get('config_language'));
$registry->set('language', $language);

// Document
$document = new Document();
$registry->set('document', $document);

// Session
$session = new Session();
$registry->set('session', $session);

// Customer
$customer = new Customer($registry);
$registry->set('customer', $customer);

// Currency
$currency = new Currency($registry);
$registry->set('currency', $currency);

// Tax
$tax = new Tax($registry);
$registry->set('tax', $tax);

// Cart
$cart = new Cart($registry);
$registry->set('cart', $cart);

// Event
$event = new Event($registry);
$registry->set('event', $event);

// Model Extension Module
$loader->model('extension/module');
$model_extension_module = $registry->get('model_extension_module');

echo "<h2>Review View Module Frontend Test</h2>\n";
echo "<style>body{font-family:Arial;margin:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;} pre{background:#f5f5f5;padding:10px;border:1px solid #ddd;overflow:auto;}</style>\n";

// Get all Review View module instances
$modules_query = "SELECT module_id, name, code, setting FROM " . DB_PREFIX . "module WHERE code = 'review_view'";
$modules_result = $db->query($modules_query);

if ($modules_result->num_rows > 0) {
    echo "<h3>Testing Module Instances</h3>\n";
    
    while ($module_row = $modules_result->fetch_assoc()) {
        $module_id = $module_row['module_id'];
        $module_name = $module_row['name'];
        $setting = unserialize($module_row['setting']);
        
        echo "<div style='border:2px solid #ddd;padding:20px;margin:20px 0;'>\n";
        echo "<h4>Module: " . htmlspecialchars($module_name) . " (ID: $module_id)</h4>\n";
        
        // Check if enabled
        $status = isset($setting['status']) ? $setting['status'] : 0;
        echo "<p><strong>Status:</strong> " . ($status ? '<span class="ok">Enabled</span>' : '<span class="error">Disabled</span>') . "</p>\n";
        
        if (!$status) {
            echo "<p class='error'>⚠ Module is disabled. Enable it in admin panel.</p>\n";
            echo "</div>\n";
            continue;
        }
        
        // Check layout assignments
        $layout_query = "SELECT l.name as layout_name, lm.position, lm.sort_order 
                         FROM " . DB_PREFIX . "layout_module lm 
                         LEFT JOIN " . DB_PREFIX . "layout l ON lm.layout_id = l.layout_id 
                         WHERE lm.code = 'review_view.$module_id'";
        $layout_result = $db->query($layout_query);
        
        if ($layout_result->num_rows > 0) {
            echo "<p class='ok'><strong>Layout Assignments:</strong></p>\n";
            echo "<ul>\n";
            while ($layout_row = $layout_result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($layout_row['layout_name']) . " → " . htmlspecialchars($layout_row['position']) . " (Sort: " . $layout_row['sort_order'] . ")</li>\n";
            }
            echo "</ul>\n";
        } else {
            echo "<p class='error'>✗ Module is NOT assigned to any layout!</p>\n";
            echo "<p>Go to Admin Panel → Extensions → Modules → Edit this module → Assign to a layout position</p>\n";
        }
        
        // Check reviews
        if (isset($setting['review_ids']) && is_array($setting['review_ids']) && !empty($setting['review_ids'])) {
            echo "<p class='ok'><strong>Selected Reviews:</strong> " . count($setting['review_ids']) . "</p>\n";
            
            // Try to load the module controller
            echo "<h5>Testing Module Controller</h5>\n";
            
            try {
                // Load the controller
                $controller_path = DIR_APPLICATION . '../catalog/controller/module/review_view.php';
                if (file_exists($controller_path)) {
                    require_once($controller_path);
                    
                    // Create controller instance
                    $controller = new ControllerModuleReviewView($registry);
                    
                    // Call index method with settings
                    ob_start();
                    $output = $controller->index($setting);
                    $errors = ob_get_clean();
                    
                    if (!empty($errors)) {
                        echo "<p class='error'>Errors/Warnings:</p>\n";
                        echo "<pre>" . htmlspecialchars($errors) . "</pre>\n";
                    }
                    
                    if (!empty($output)) {
                        echo "<p class='ok'>✓ Module output generated (" . strlen($output) . " bytes)</p>\n";
                        echo "<div style='border:1px solid #0f0;padding:10px;margin:10px 0;background:#f0fff0;'>\n";
                        echo "<strong>Preview:</strong>\n";
                        echo "<div style='max-height:300px;overflow:auto;'>" . $output . "</div>\n";
                        echo "</div>\n";
                    } else {
                        echo "<p class='warning'>⚠ Module returned empty output</p>\n";
                        echo "<p>Possible reasons:</p>\n";
                        echo "<ul>\n";
                        echo "<li>No reviews found (check if reviews are approved)</li>\n";
                        echo "<li>Reviews array is empty</li>\n";
                        echo "<li>Template condition failed</li>\n";
                        echo "</ul>\n";
                    }
                } else {
                    echo "<p class='error'>✗ Controller file not found: $controller_path</p>\n";
                }
            } catch (Exception $e) {
                echo "<p class='error'>✗ Error loading module: " . htmlspecialchars($e->getMessage()) . "</p>\n";
                echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>\n";
            }
            
        } else {
            echo "<p class='error'>✗ No reviews selected for this module</p>\n";
            echo "<p>Go to Admin Panel → Extensions → Modules → Edit this module → Select at least one review</p>\n";
        }
        
        echo "</div>\n";
    }
} else {
    echo "<p class='error'>✗ No Review View module instances found</p>\n";
    echo "<p>You need to create a module instance first:</p>\n";
    echo "<ol>\n";
    echo "<li>Go to Admin Panel → Extensions → Modules</li>\n";
    echo "<li>Find 'Review View' in the list</li>\n";
    echo "<li>Click the green + button to add a new instance</li>\n";
    echo "<li>Configure and save</li>\n";
    echo "</ol>\n";
}

echo "<hr>\n";
echo "<h3>Summary</h3>\n";
echo "<p>If the module is still not showing, check:</p>\n";
echo "<ol>\n";
echo "<li>Module is enabled (Status = Enabled)</li>\n";
echo "<li>Module is assigned to a layout position</li>\n";
echo "<li>At least one review is selected</li>\n";
echo "<li>Selected reviews are approved (Status = 1)</li>\n";
echo "<li>Clear cache (system/cache/*)</li>\n";
echo "</ol>\n";
?>

