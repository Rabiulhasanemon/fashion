<?php
// Debug page for manufacturer images
// Access: http://yoursite.com/catalog/debug_manufacturer_images.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
if (!ob_get_level()) {
	ob_start();
}

// Version
define('VERSION', '2.4.0');

// Bootstrap OpenCart - config.php is in parent directory
$config_path = dirname(__DIR__) . '/config.php';
if (!file_exists($config_path)) {
	die("Error: config.php not found at " . $config_path . "<br>Current directory: " . __DIR__);
}

require_once($config_path);

if (!defined('DIR_SYSTEM')) {
	die("Error: DIR_SYSTEM not defined after loading config.php");
}

if (!file_exists(DIR_SYSTEM . 'startup.php')) {
	die("Error: startup.php not found at " . DIR_SYSTEM . 'startup.php');
}

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

// Store
$config->set('config_store_id', 0);

// Settings
$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");

foreach ($query->rows as $result) {
	if (!$result['serialized']) {
		$config->set($result['key'], $result['value']);
	} else {
		$config->set($result['key'], unserialize($result['value']));
	}
}

$config->set('config_url', HTTP_SERVER);
$config->set('config_ssl', HTTPS_SERVER);

// URL
$url = new SiteUrl($config->get('config_url'), $config->get('config_secure') ? $config->get('config_ssl') : $config->get('config_url'));
$registry->set('url', $url);

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Session
$session = new Session();
$registry->set('session', $session);

// Language
$languages = array();
$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language`");
foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}
$config->set('config_language_id', $languages[$config->get('config_language')]['language_id']);
$language = new Language($languages[$config->get('config_language')]['directory']);
$registry->set('language', $language);

// Document
$registry->set('document', new Document());

// Load models using Loader
$loader->model('catalog/manufacturer');
$loader->model('tool/image');

// Get models from registry
$model_manufacturer = $registry->get('model_catalog_manufacturer');
$model_image = $registry->get('model_tool_image');

// Get all manufacturers
$manufacturers_data = $model_manufacturer->getManufacturers();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manufacturer Images Debug</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        .summary {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #007bff;
        }
        .summary h2 {
            color: #007bff;
            margin-bottom: 15px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .summary-item {
            background: white;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .summary-item strong {
            display: block;
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .summary-item span {
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }
        .manufacturer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .manufacturer-card {
            background: #fff;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s;
        }
        .manufacturer-card:hover {
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0,123,255,0.2);
        }
        .manufacturer-card.error {
            border-color: #dc3545;
            background: #fff5f5;
        }
        .manufacturer-card.success {
            border-color: #28a745;
            background: #f5fff5;
        }
        .manufacturer-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .manufacturer-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .manufacturer-id {
            background: #007bff;
            color: white;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 12px;
        }
        .image-container {
            text-align: center;
            margin: 15px 0;
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
        }
        .manufacturer-image {
            max-width: 100%;
            max-height: 150px;
            border: 2px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            background: white;
        }
        .manufacturer-image.error {
            border-color: #dc3545;
        }
        .manufacturer-image.success {
            border-color: #28a745;
        }
        .info-section {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
        }
        .info-label {
            color: #666;
            font-weight: bold;
        }
        .info-value {
            color: #333;
            word-break: break-all;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 10px;
        }
        .status-success {
            background: #28a745;
            color: white;
        }
        .status-error {
            background: #dc3545;
            color: white;
        }
        .status-warning {
            background: #ffc107;
            color: #333;
        }
        .status-info {
            background: #17a2b8;
            color: white;
        }
        .raw-data {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-family: monospace;
            font-size: 11px;
            max-height: 200px;
            overflow: auto;
        }
        .test-buttons {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Manufacturer Images Debug Page</h1>
        
        <div class="test-buttons">
            <button class="btn btn-primary" onclick="location.reload()">🔄 Refresh Page</button>
            <button class="btn btn-success" onclick="testAllImages()">✅ Test All Images</button>
            <button class="btn btn-danger" onclick="clearCache()">🗑️ Clear Image Cache</button>
        </div>

        <div class="summary">
            <h2>📊 Summary</h2>
            <div class="summary-grid">
                <div class="summary-item">
                    <strong>Total Manufacturers</strong>
                    <span id="total-count"><?php echo count($manufacturers_data); ?></span>
                </div>
                <div class="summary-item">
                    <strong>With Images</strong>
                    <span id="with-images">0</span>
                </div>
                <div class="summary-item">
                    <strong>Without Images</strong>
                    <span id="without-images">0</span>
                </div>
                <div class="summary-item">
                    <strong>Images Loaded</strong>
                    <span id="loaded-count" style="color: #28a745;">0</span>
                </div>
                <div class="summary-item">
                    <strong>Images Failed</strong>
                    <span id="failed-count" style="color: #dc3545;">0</span>
                </div>
            </div>
        </div>

        <div class="manufacturer-grid">
            <?php 
            $with_images = 0;
            $without_images = 0;
            
            foreach ($manufacturers_data as $manufacturer) {
                $manufacturer_id = $manufacturer['manufacturer_id'];
                $name = $manufacturer['name'];
                $thumb = isset($manufacturer['thumb']) ? $manufacturer['thumb'] : '';
                $image = isset($manufacturer['image']) ? $manufacturer['image'] : '';
                
                // Try to resize image
                $resized_thumb = null;
                $resized_image = null;
                
                if ($thumb) {
                    $resized_thumb = $model_image->resize($thumb, 200, 200);
                    if ($resized_thumb) $with_images++;
                } elseif ($image) {
                    $resized_image = $model_image->resize($image, 200, 200);
                    if ($resized_image) $with_images++;
                } else {
                    $without_images++;
                }
                
                $final_image = $resized_thumb ?: $resized_image;
                $has_image = !empty($final_image);
                
                if (!$has_image) {
                    // Generate SVG placeholder
                    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><rect width="100%" height="100%" fill="#f8f9fa"/><text x="50%" y="50%" font-family="Arial" font-size="14" fill="#adb5bd" text-anchor="middle" dy=".3em">' . htmlspecialchars($name) . '</text></svg>';
                    $final_image = 'data:image/svg+xml;base64,' . base64_encode($svg);
                }
            ?>
            <div class="manufacturer-card <?php echo $has_image ? 'success' : 'error'; ?>" data-manufacturer-id="<?php echo $manufacturer_id; ?>">
                <div class="manufacturer-header">
                    <div class="manufacturer-name"><?php echo htmlspecialchars($name); ?></div>
                    <div class="manufacturer-id">ID: <?php echo $manufacturer_id; ?></div>
                </div>
                
                <div class="image-container">
                    <img class="manufacturer-image <?php echo $has_image ? 'success' : 'error'; ?>" 
                         src="<?php echo htmlspecialchars($final_image); ?>" 
                         alt="<?php echo htmlspecialchars($name); ?>"
                         data-original-thumb="<?php echo htmlspecialchars($thumb); ?>"
                         data-original-image="<?php echo htmlspecialchars($image); ?>"
                         data-resized-url="<?php echo htmlspecialchars($final_image); ?>"
                         onload="imageLoaded(this)"
                         onerror="imageFailed(this)" />
                </div>
                
                <div class="info-section">
                    <div class="info-row">
                        <span class="info-label">Thumb Field:</span>
                        <span class="info-value"><?php echo $thumb ? htmlspecialchars($thumb) : '<em style="color:#999;">Empty</em>'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Image Field:</span>
                        <span class="info-value"><?php echo $image ? htmlspecialchars($image) : '<em style="color:#999;">Empty</em>'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Resized URL:</span>
                        <span class="info-value" style="font-size: 11px;"><?php echo $final_image ? substr(htmlspecialchars($final_image), 0, 80) . '...' : '<em style="color:#999;">N/A</em>'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <?php if ($has_image && ($resized_thumb || $resized_image)) { ?>
                                <span class="status-badge status-success">✅ Has Image</span>
                            <?php } elseif ($thumb || $image) { ?>
                                <span class="status-badge status-warning">⚠️ File Missing</span>
                            <?php } else { ?>
                                <span class="status-badge status-error">❌ No Image</span>
                            <?php } ?>
                        </span>
                    </div>
                </div>
                
                <div class="raw-data" style="display: none;">
                    <strong>Raw Data:</strong><br>
                    <?php echo htmlspecialchars(print_r($manufacturer, true)); ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <script>
        let loadedCount = 0;
        let failedCount = 0;
        let totalImages = <?php echo count($manufacturers_data); ?>;
        
        function imageLoaded(img) {
            loadedCount++;
            updateCounters();
            img.classList.add('success');
            img.classList.remove('error');
            img.closest('.manufacturer-card').classList.add('success');
            img.closest('.manufacturer-card').classList.remove('error');
            console.log('✅ Image loaded:', img.src.substring(0, 100));
        }
        
        function imageFailed(img) {
            failedCount++;
            updateCounters();
            img.classList.add('error');
            img.classList.remove('success');
            img.closest('.manufacturer-card').classList.add('error');
            img.closest('.manufacturer-card').classList.remove('success');
            console.error('❌ Image failed:', img.src.substring(0, 100));
        }
        
        function updateCounters() {
            document.getElementById('loaded-count').textContent = loadedCount;
            document.getElementById('failed-count').textContent = failedCount;
            document.getElementById('with-images').textContent = <?php echo $with_images; ?>;
            document.getElementById('without-images').textContent = <?php echo $without_images; ?>;
        }
        
        function testAllImages() {
            const images = document.querySelectorAll('.manufacturer-image');
            console.group('🧪 Testing All Images');
            images.forEach((img, index) => {
                const testImg = new Image();
                testImg.onload = function() {
                    console.log(`✅ Image #${index + 1} loaded:`, img.src.substring(0, 80));
                };
                testImg.onerror = function() {
                    console.error(`❌ Image #${index + 1} failed:`, img.src.substring(0, 80));
                };
                testImg.src = img.src;
            });
            console.groupEnd();
            alert('Check browser console for test results!');
        }
        
        function clearCache() {
            if (confirm('Clear image cache? This will force reload of all images.')) {
                const images = document.querySelectorAll('.manufacturer-image');
                images.forEach(img => {
                    const src = img.src;
                    img.src = '';
                    setTimeout(() => {
                        img.src = src + '?t=' + Date.now();
                    }, 100);
                });
            }
        }
        
        // Initialize counters
        updateCounters();
        
        // Log all manufacturer data
        console.group('📋 All Manufacturer Data');
        console.log('Total Manufacturers:', totalImages);
        console.log('With Images:', <?php echo $with_images; ?>);
        console.log('Without Images:', <?php echo $without_images; ?>);
        console.groupEnd();
    </script>
</body>
</html>

