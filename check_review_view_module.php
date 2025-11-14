<?php
// Diagnostic script to check Review View module
// This will check if all files exist and if module is registered

// Database configuration
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'masterco_rup');
define('DB_PASSWORD', 'masterco_new1');
define('DB_DATABASE', 'masterco_rup');
define('DB_PREFIX', 'sr_');

// Paths
define('DIR_APPLICATION', '/home/masterco/ruplexa1.master.com.bd/admin/');
define('DIR_LANGUAGE', '/home/masterco/ruplexa1.master.com.bd/admin/language/');

echo "<h2>Review View Module Diagnostic</h2>\n";
echo "<style>body{font-family:Arial;margin:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;} pre{background:#f5f5f5;padding:10px;border:1px solid #ddd;}</style>\n";

// Check 1: Controller file
echo "<h3>1. Checking Controller File</h3>\n";
$controller_file = DIR_APPLICATION . 'controller/module/review_view.php';
if (file_exists($controller_file)) {
    echo "<p class='ok'>✓ Controller file exists: <code>" . htmlspecialchars($controller_file) . "</code></p>\n";
    $controller_content = file_get_contents($controller_file);
    if (strpos($controller_content, 'ControllerModuleReviewView') !== false) {
        echo "<p class='ok'>✓ Controller class name is correct</p>\n";
    } else {
        echo "<p class='error'>✗ Controller class name might be wrong</p>\n";
    }
} else {
    echo "<p class='error'>✗ Controller file NOT found: <code>" . htmlspecialchars($controller_file) . "</code></p>\n";
}

// Check 2: Language file
echo "<h3>2. Checking Language File</h3>\n";
$language_file = DIR_LANGUAGE . 'english/module/review_view.php';
if (file_exists($language_file)) {
    echo "<p class='ok'>✓ Language file exists: <code>" . htmlspecialchars($language_file) . "</code></p>\n";
    
    // Try to include and check for errors
    ob_start();
    $lang_loaded = false;
    try {
        include $language_file;
        $lang_loaded = true;
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error loading language file: " . $e->getMessage() . "</p>\n";
    }
    $output = ob_get_clean();
    
    if ($lang_loaded) {
        if (isset($_['heading_title'])) {
            echo "<p class='ok'>✓ Language file loaded successfully. Heading: " . htmlspecialchars($_['heading_title']) . "</p>\n";
        } else {
            echo "<p class='warning'>⚠ Language file loaded but heading_title not found</p>\n";
        }
    }
    
    if ($output) {
        echo "<p class='warning'>⚠ Language file output: <pre>" . htmlspecialchars($output) . "</pre></p>\n";
    }
} else {
    echo "<p class='error'>✗ Language file NOT found: <code>" . htmlspecialchars($language_file) . "</code></p>\n";
}

// Check 3: Template file
echo "<h3>3. Checking Template File</h3>\n";
$template_file = DIR_APPLICATION . 'view/template/module/review_view.tpl';
if (file_exists($template_file)) {
    echo "<p class='ok'>✓ Template file exists: <code>" . htmlspecialchars($template_file) . "</code></p>\n";
} else {
    echo "<p class='error'>✗ Template file NOT found: <code>" . htmlspecialchars($template_file) . "</code></p>\n";
}

// Check 4: Extension table registration
echo "<h3>4. Checking Extension Table</h3>\n";
$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($mysqli->connect_error) {
    echo "<p class='error'>✗ Database connection failed: " . $mysqli->connect_error . "</p>\n";
} else {
    // Check if extension table exists
    $table_check = $mysqli->query("SHOW TABLES LIKE '" . DB_PREFIX . "extension'");
    if ($table_check && $table_check->num_rows > 0) {
        echo "<p class='ok'>✓ Extension table exists</p>\n";
        
        // Check if review_view is registered
        $check_query = "SELECT * FROM " . DB_PREFIX . "extension WHERE type = 'module' AND code = 'review_view'";
        $result = $mysqli->query($check_query);
        
        if ($result && $result->num_rows > 0) {
            echo "<p class='ok'>✓ Module 'review_view' is registered in extension table</p>\n";
            $row = $result->fetch_assoc();
            echo "<pre>Extension ID: " . $row['extension_id'] . "\nType: " . $row['type'] . "\nCode: " . $row['code'] . "</pre>\n";
        } else {
            echo "<p class='warning'>⚠ Module 'review_view' is NOT registered in extension table</p>\n";
            echo "<p>This is OK - modules can appear even if not registered (they'll show with Install button)</p>\n";
        }
        
        // Show all installed modules
        echo "<h4>All Installed Modules:</h4>\n";
        $all_modules = $mysqli->query("SELECT * FROM " . DB_PREFIX . "extension WHERE type = 'module' ORDER BY code");
        if ($all_modules && $all_modules->num_rows > 0) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>\n";
            echo "<tr><th>Extension ID</th><th>Type</th><th>Code</th></tr>\n";
            while ($row = $all_modules->fetch_assoc()) {
                $highlight = ($row['code'] == 'review_view') ? " style='background:yellow;'" : "";
                echo "<tr$highlight>";
                echo "<td>" . htmlspecialchars($row['extension_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                echo "<td>" . htmlspecialchars($row['code']) . "</td>";
                echo "</tr>\n";
            }
            echo "</table>\n";
        } else {
            echo "<p>No modules installed in extension table</p>\n";
        }
    } else {
        echo "<p class='error'>✗ Extension table does not exist!</p>\n";
    }
    
    $mysqli->close();
}

// Check 5: File permissions
echo "<h3>5. Checking File Permissions</h3>\n";
if (file_exists($controller_file)) {
    $perms = fileperms($controller_file);
    echo "<p>Controller file permissions: " . substr(sprintf('%o', $perms), -4) . "</p>\n";
}
if (file_exists($language_file)) {
    $perms = fileperms($language_file);
    echo "<p>Language file permissions: " . substr(sprintf('%o', $perms), -4) . "</p>\n";
}

// Check 6: Scan for all module files
echo "<h3>6. Scanning for Module Files</h3>\n";
$module_dir = DIR_APPLICATION . 'controller/module/';
if (is_dir($module_dir)) {
    $files = glob($module_dir . '*.php');
    echo "<p>Found " . count($files) . " module files:</p>\n";
    echo "<ul>\n";
    foreach ($files as $file) {
        $basename = basename($file, '.php');
        $highlight = ($basename == 'review_view') ? " style='color:green;font-weight:bold;'" : "";
        echo "<li$highlight>" . htmlspecialchars($basename) . "</li>\n";
    }
    echo "</ul>\n";
} else {
    echo "<p class='error'>✗ Module directory not found: <code>" . htmlspecialchars($module_dir) . "</code></p>\n";
}

// Recommendation
echo "<hr>\n";
echo "<h3>Recommendation</h3>\n";
echo "<p>If the module is still not showing:</p>\n";
echo "<ol>\n";
echo "<li>Make sure all files exist (controller, language, template)</li>\n";
echo "<li>Run the install script: <a href='install_review_view_module.php'>install_review_view_module.php</a></li>\n";
echo "<li>Clear OpenCart cache (system/cache/*)</li>\n";
echo "<li>Clear browser cache and refresh admin panel</li>\n";
echo "<li>Check admin panel error logs</li>\n";
echo "</ol>\n";
?>

