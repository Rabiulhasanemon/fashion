<?php
/**
 * Git Update Script for Server (PHP version)
 * Run this script via browser or CLI to update from GitHub
 * 
 * Usage: 
 * - Via browser: https://yourdomain.com/git_update_server.php
 * - Via CLI: php git_update_server.php
 */

// Security: Only allow execution from CLI or with proper authentication
$is_cli = (php_sapi_name() === 'cli');
$is_authorized = false;

// For browser access, you can add authentication here
if (!$is_cli) {
    // Uncomment and set a password to secure browser access
    // $password = 'your-secure-password-here';
    // if (!isset($_GET['key']) || $_GET['key'] !== $password) {
    //     die('Unauthorized access');
    // }
    
    // Or check for specific IP
    // $allowed_ips = ['your.server.ip'];
    // if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
    //     die('Unauthorized access');
    // }
    
    $is_authorized = true; // Remove this if you add authentication
}

if (!$is_cli && !$is_authorized) {
    die('Unauthorized access. This script can only be run from CLI or with proper authentication.');
}

// Get the project root directory
$project_root = dirname(__FILE__);
chdir($project_root);

echo "Starting Git update process...\n";
echo "Project root: $project_root\n\n";

// Check if git is available
exec('which git', $git_path, $git_check);
if ($git_check !== 0) {
    die("Error: Git is not installed or not in PATH\n");
}

// Check if we're in a git repository
if (!is_dir('.git')) {
    die("Error: Not a git repository\n");
}

// Fetch latest changes
echo "Fetching latest changes from GitHub...\n";
exec('git fetch origin main 2>&1', $fetch_output, $fetch_status);
if ($fetch_status !== 0) {
    echo "Warning: Fetch failed\n";
    echo implode("\n", $fetch_output) . "\n";
}

// Check for local changes
exec('git status --porcelain', $status_output, $status_status);
$has_changes = !empty($status_output);

if ($has_changes) {
    echo "Local changes detected. Stashing them...\n";
    $stash_message = "Local changes before pull - " . date('Y-m-d H:i:s');
    exec("git stash save \"$stash_message\" 2>&1", $stash_output, $stash_status);
    echo implode("\n", $stash_output) . "\n";
    
    if ($stash_status !== 0) {
        echo "Warning: Stash failed, but continuing...\n";
    }
}

// Reset any uncommitted changes to specific files that are causing issues
$problematic_files = [
    'admin/controller/catalog/filter.php',
    'admin/model/catalog/filter.php',
    'catalog/view/theme/ranger_fashion/stylesheet/main.css',
    'catalog/view/theme/ranger_fashion/template/common/header-dev.tpl',
    'catalog/view/theme/ranger_fashion/template/common/navigation.tpl',
    'catalog/view/theme/ranger_fashion/template/common/navigation_backup.tpl',
    'catalog/view/theme/ranger_fashion/template/module/featured_category.tpl'
];

echo "\nResetting problematic files to match remote...\n";
foreach ($problematic_files as $file) {
    if (file_exists($file)) {
        exec("git checkout -- \"$file\" 2>&1", $checkout_output, $checkout_status);
        if ($checkout_status === 0) {
            echo "Reset: $file\n";
        }
    }
}

// Pull latest changes
echo "\nPulling latest changes from GitHub...\n";
exec('git pull origin main 2>&1', $pull_output, $pull_status);
echo implode("\n", $pull_output) . "\n";

if ($pull_status === 0) {
    echo "\n✓ Successfully updated from GitHub!\n";
    
    // Try to apply stashed changes if any
    if ($has_changes) {
        echo "\nAttempting to apply stashed changes...\n";
        exec('git stash pop 2>&1', $pop_output, $pop_status);
        echo implode("\n", $pop_output) . "\n";
        
        if ($pop_status !== 0) {
            echo "\nWarning: There were conflicts when applying stashed changes.\n";
            echo "You may need to resolve conflicts manually.\n";
            echo "To view stashed changes: git stash list\n";
            echo "To apply manually: git stash pop\n";
        } else {
            echo "\n✓ Stashed changes applied successfully!\n";
        }
    }
} else {
    echo "\n✗ Error: Failed to pull from GitHub\n";
    echo "You may need to resolve conflicts manually.\n";
    exit(1);
}

echo "\nGit update process completed!\n";

