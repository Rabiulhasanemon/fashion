<?php
/**
 * cPanel Merge Conflict Fixer
 * 
 * Upload this file to your cPanel root directory and access it via browser
 * OR run it via SSH: php fix_cpanel_merge.php
 * 
 * This script will:
 * 1. Discard local changes to the conflicting files
 * 2. Pull the latest changes from GitHub
 */

// Set the repository path (adjust if needed)
$repo_path = __DIR__; // Current directory
// Or specify full path: $repo_path = '/home/username/public_html';

// Files with conflicts
$conflict_files = [
    'catalog/view/theme/ranger_fashion/stylesheet/new_header.css',
    'catalog/view/theme/ranger_fashion/template/common/navigation.tpl'
];

echo "<h2>cPanel Merge Conflict Fixer</h2>";
echo "<pre>";

// Change to repository directory
chdir($repo_path);
echo "Working directory: " . getcwd() . "\n\n";

// Check if git is available
$git_check = shell_exec('which git 2>&1');
if (empty($git_check)) {
    die("ERROR: Git is not found. Please run this via SSH instead.\n");
}

echo "Step 1: Checking git status...\n";
$status = shell_exec('git status 2>&1');
echo $status . "\n";

echo "\nStep 2: Discarding local changes to conflicting files...\n";
foreach ($conflict_files as $file) {
    $file_path = $repo_path . '/' . $file;
    if (file_exists($file_path)) {
        $result = shell_exec("git checkout -- " . escapeshellarg($file) . " 2>&1");
        echo "  - Fixed: $file\n";
        if (!empty($result)) {
            echo "    Output: $result\n";
        }
    } else {
        echo "  - Warning: File not found: $file\n";
    }
}

echo "\nStep 3: Pulling latest changes from GitHub...\n";
$pull_result = shell_exec('git pull origin main 2>&1');
echo $pull_result . "\n";

echo "\nStep 4: Final git status...\n";
$final_status = shell_exec('git status 2>&1');
echo $final_status . "\n";

echo "\n✅ Done! The merge conflict should now be resolved.\n";
echo "</pre>";

// If running from command line, also output there
if (php_sapi_name() === 'cli') {
    echo "\nScript completed. Check the output above for any errors.\n";
}
?>

