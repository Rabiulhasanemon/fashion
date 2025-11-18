<?php
/**
 * Clear Cache Script for OpenCart
 * 
 * This script clears cache files from:
 * - system/cache/
 * - system/cache/html/
 * 
 * It keeps the folders but deletes all cache files inside them.
 * 
 * Usage:
 * - Via browser: https://yourdomain.com/clear_cache.php
 * - Via command line: php clear_cache.php
 */

// Security: Add a simple password check (optional - remove if not needed)
// Uncomment the lines below and set a password if you want protection
/*
$password = 'your-secret-password';
if (isset($_GET['key']) && $_GET['key'] === $password) {
    // Continue
} else {
    die('Access denied. Use: clear_cache.php?key=your-secret-password');
}
*/

// Define cache directories
$cache_dirs = [
    'system/cache',
    'system/cache/html',
];

$cleared = [];
$errors = [];
$total_deleted = 0;

// Set headers for browser output
if (php_sapi_name() !== 'cli') {
    header('Content-Type: text/html; charset=utf-8');
    echo "<!DOCTYPE html><html><head><title>Clear Cache</title>";
    echo "<style>body{font-family:Arial;max-width:800px;margin:50px auto;padding:20px;background:#f5f5f5;}";
    echo ".success{background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:15px;border-radius:5px;margin:10px 0;}";
    echo ".error{background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:15px;border-radius:5px;margin:10px 0;}";
    echo ".info{background:#d1ecf1;border:1px solid #bee5eb;color:#0c5460;padding:15px;border-radius:5px;margin:10px 0;}";
    echo "pre{background:#fff;padding:15px;border-radius:5px;overflow-x:auto;border:1px solid #ddd;}</style></head><body>";
    echo "<h1>🧹 Cache Clearing Script</h1>";
}

echo (php_sapi_name() === 'cli' ? "\n=== OpenCart Cache Clearing Script ===\n\n" : "<div class='info'>");

// Change to repository directory
$repo_path = __DIR__;
chdir($repo_path);
$current_dir = getcwd();

echo (php_sapi_name() === 'cli' ? "Working directory: $current_dir\n\n" : "<strong>Working Directory:</strong> $current_dir<br><br>");

echo (php_sapi_name() === 'cli' ? "" : "</div>");

// Function to delete files in directory (but keep the directory)
function clearCacheDirectory($dir) {
    $deleted = 0;
    $errors = [];
    
    if (!is_dir($dir)) {
        return ['deleted' => 0, 'errors' => ["Directory not found: $dir"]];
    }
    
    // Get all files and subdirectories
    $items = glob($dir . '/*');
    
    foreach ($items as $item) {
        if (is_file($item)) {
            // Delete file
            if (@unlink($item)) {
                $deleted++;
            } else {
                $errors[] = "Failed to delete file: $item";
            }
        } elseif (is_dir($item)) {
            // Recursively clear subdirectory
            $subdir = clearCacheDirectory($item);
            $deleted += $subdir['deleted'];
            $errors = array_merge($errors, $subdir['errors']);
            
            // Try to remove empty subdirectory (optional - comment out if you want to keep subdirs)
            // @rmdir($item);
        }
    }
    
    return ['deleted' => $deleted, 'errors' => $errors];
}

// Clear each cache directory
foreach ($cache_dirs as $dir) {
    $dir_path = $repo_path . '/' . $dir;
    
    if (!is_dir($dir_path)) {
        $errors[] = "Directory not found: $dir";
        continue;
    }
    
    echo (php_sapi_name() === 'cli' ? "Clearing: $dir\n" : "<div class='info'><strong>Clearing:</strong> $dir</div>");
    
    $result = clearCacheDirectory($dir_path);
    $count = $result['deleted'];
    $dir_errors = $result['errors'];
    
    if ($count > 0) {
        $cleared[] = "$dir: $count files deleted";
        $total_deleted += $count;
        echo (php_sapi_name() === 'cli' ? "  ✓ Deleted $count files\n" : "<div class='success'>✓ Deleted $count files</div>");
    } else {
        echo (php_sapi_name() === 'cli' ? "  ✓ Directory already empty\n" : "<div class='info'>✓ Directory already empty</div>");
    }
    
    if (!empty($dir_errors)) {
        $errors = array_merge($errors, $dir_errors);
    }
}

// Summary
echo (php_sapi_name() === 'cli' ? "\n=== Summary ===\n" : "<div class='info'><h2>Summary</h2>");

if (!empty($cleared)) {
    foreach ($cleared as $msg) {
        echo (php_sapi_name() === 'cli' ? "✓ $msg\n" : "<p>✓ $msg</p>");
    }
}

if (!empty($errors)) {
    echo (php_sapi_name() === 'cli' ? "\n⚠ Errors:\n" : "<div class='error'><h3>⚠ Errors:</h3>");
    foreach ($errors as $error) {
        echo (php_sapi_name() === 'cli' ? "  - $error\n" : "<p>- $error</p>");
    }
    echo (php_sapi_name() === 'cli' ? "" : "</div>");
} else {
    echo (php_sapi_name() === 'cli' ? "\n✅ Cache cleared successfully!\n" : "<div class='success'><h3>✅ Cache cleared successfully!</h3>");
    echo (php_sapi_name() === 'cli' ? "Total files deleted: $total_deleted\n" : "<p><strong>Total files deleted:</strong> $total_deleted</p></div>");
}

echo (php_sapi_name() === 'cli' ? "\n=== Done ===\n" : "<div class='info'><p><strong>⚠️ IMPORTANT:</strong> Delete this file (clear_cache.php) after use for security reasons.</p></div>");

if (php_sapi_name() !== 'cli') {
    echo "</body></html>";
}
?>
