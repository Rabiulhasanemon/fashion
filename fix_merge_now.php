<?php
/**
 * QUICK FIX for cPanel Merge Conflict
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to your cPanel root directory (same level as your .git folder)
 * 2. Access it via browser: https://yourdomain.com/fix_merge_now.php
 * 3. It will automatically fix the merge conflict
 * 4. Delete this file after use for security
 */

// Security: Only allow if accessed directly
if (php_sapi_name() !== 'cli' && !isset($_SERVER['HTTP_HOST'])) {
    die('Access denied');
}

// Set execution time
set_time_limit(60);

// Get the directory where this script is located
$repo_path = __DIR__;

echo "<!DOCTYPE html><html><head><title>Fix cPanel Merge Conflict</title>";
echo "<style>body{font-family:Arial;max-width:800px;margin:50px auto;padding:20px;background:#f5f5f5;}";
echo ".success{background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:15px;border-radius:5px;margin:10px 0;}";
echo ".error{background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:15px;border-radius:5px;margin:10px 0;}";
echo ".info{background:#d1ecf1;border:1px solid #bee5eb;color:#0c5460;padding:15px;border-radius:5px;margin:10px 0;}";
echo "pre{background:#fff;padding:15px;border-radius:5px;overflow-x:auto;border:1px solid #ddd;}</style></head><body>";
echo "<h1>🔧 cPanel Merge Conflict Fixer</h1>";

// Change to repository directory
if (!chdir($repo_path)) {
    die("<div class='error'>ERROR: Cannot change to directory: $repo_path</div></body></html>");
}

$current_dir = getcwd();
echo "<div class='info'><strong>Working Directory:</strong> $current_dir</div>";

// Check if .git exists
if (!is_dir('.git')) {
    echo "<div class='error'>ERROR: .git directory not found. Make sure this file is in your repository root.</div>";
    echo "<div class='info'>Current directory contents: " . implode(', ', array_slice(scandir('.'), 0, 10)) . "</div>";
    echo "</body></html>";
    exit;
}

// Files to fix
$files = [
    'catalog/view/theme/ranger_fashion/stylesheet/new_header.css',
    'catalog/view/theme/ranger_fashion/template/common/navigation.tpl'
];

echo "<div class='info'><strong>Step 1:</strong> Checking git status...</div>";
echo "<pre>";
$status = shell_exec('git status 2>&1');
echo htmlspecialchars($status);
echo "</pre>";

echo "<div class='info'><strong>Step 2:</strong> Discarding local changes to conflicting files...</div>";
echo "<pre>";

$all_success = true;
foreach ($files as $file) {
    if (file_exists($file)) {
        $result = shell_exec('git checkout -- ' . escapeshellarg($file) . ' 2>&1');
        if (empty(trim($result))) {
            echo "✅ Fixed: $file\n";
        } else {
            echo "⚠️  $file: " . htmlspecialchars($result) . "\n";
        }
    } else {
        echo "⚠️  File not found: $file (may have been deleted)\n";
    }
}

echo "</pre>";

echo "<div class='info'><strong>Step 3:</strong> Pulling latest changes from GitHub...</div>";
echo "<pre>";
$pull_result = shell_exec('git pull origin main 2>&1');
echo htmlspecialchars($pull_result);
echo "</pre>";

// Check if pull was successful
if (strpos($pull_result, 'error') !== false || strpos($pull_result, 'Aborting') !== false) {
    echo "<div class='error'><strong>❌ Pull failed. There may still be conflicts.</strong></div>";
    $all_success = false;
} else {
    echo "<div class='success'><strong>✅ Pull completed successfully!</strong></div>";
}

echo "<div class='info'><strong>Step 4:</strong> Final git status...</div>";
echo "<pre>";
$final_status = shell_exec('git status 2>&1');
echo htmlspecialchars($final_status);
echo "</pre>";

if ($all_success && strpos($final_status, 'Your branch is up to date') !== false) {
    echo "<div class='success'><h2>✅ SUCCESS! Merge conflict has been resolved.</h2>";
    echo "<p>Your cPanel repository is now in sync with GitHub.</p>";
    echo "<p><strong>⚠️ IMPORTANT:</strong> Please delete this file (fix_merge_now.php) for security reasons.</p></div>";
} else {
    echo "<div class='error'><h2>⚠️ Please check the output above.</h2>";
    echo "<p>If there are still errors, you may need to run these commands via SSH:</p>";
    echo "<pre style='background:#fff;padding:10px;'>";
    echo "git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css\n";
    echo "git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl\n";
    echo "git pull origin main\n";
    echo "</pre></div>";
}

echo "</body></html>";
?>

