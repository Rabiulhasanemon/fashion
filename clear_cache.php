<?php
/**
 * Clear Cache Script
 * This script clears OpenCart system cache files
 * 
 * Usage: Run via browser or command line
 * Browser: https://yourdomain.com/clear_cache.php
 * CLI: php clear_cache.php
 */

// Define directories
$cache_dirs = [
    'system/cache',
    'system/cache/html',
];

$cleared = [];
$errors = [];

echo "=== OpenCart Cache Clearing Script ===\n\n";

foreach ($cache_dirs as $dir) {
    if (!is_dir($dir)) {
        $errors[] = "Directory not found: $dir";
        continue;
    }
    
    echo "Clearing: $dir\n";
    
    // Get all files in cache directory
    $files = glob($dir . '/*');
    $count = 0;
    
    foreach ($files as $file) {
        if (is_file($file)) {
            if (@unlink($file)) {
                $count++;
            } else {
                $errors[] = "Failed to delete: $file";
            }
        } elseif (is_dir($file)) {
            // Recursively delete directory contents
            $subfiles = glob($file . '/*');
            foreach ($subfiles as $subfile) {
                if (is_file($subfile)) {
                    if (@unlink($subfile)) {
                        $count++;
                    }
                }
            }
        }
    }
    
    if ($count > 0) {
        $cleared[] = "$dir: $count files deleted";
        echo "  ✓ Deleted $count files\n";
    } else {
        echo "  ✓ Directory already empty\n";
    }
}

echo "\n=== Summary ===\n";
if (!empty($cleared)) {
    foreach ($cleared as $msg) {
        echo "✓ $msg\n";
    }
}

if (!empty($errors)) {
    echo "\n⚠ Errors:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
} else {
    echo "\n✅ Cache cleared successfully!\n";
}

echo "\n=== Done ===\n";


