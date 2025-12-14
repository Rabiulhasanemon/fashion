<?php
/**
 * Fix cPanel Git Merge Conflict
 * Upload this file to your server root and access via browser
 * Example: https://yourdomain.com/fix_cpanel_git_conflict.php
 */

// Security: Add a password or remove this file after use
$SECURITY_PASSWORD = 'CHANGE_THIS_PASSWORD_OR_DELETE_FILE_AFTER_USE';

// Check password if provided
if (isset($_GET['password']) && $_GET['password'] === $SECURITY_PASSWORD) {
    $runFix = true;
} else {
    $runFix = false;
    echo "<h2>Git Conflict Fixer</h2>";
    echo "<p>Add ?password=YOUR_PASSWORD to the URL to run the fix.</p>";
    echo "<p><strong>WARNING:</strong> Change the password in this file or delete it after use!</p>";
    exit;
}

// Get repository path (adjust if needed)
$repoPath = __DIR__; // Current directory
// Alternative: $repoPath = '/home/username/public_html/fashion';

echo "<!DOCTYPE html><html><head><title>Git Conflict Fixer</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;}";
echo ".success{color:green;background:#d4edda;padding:10px;border-radius:5px;margin:10px 0;}";
echo ".error{color:red;background:#f8d7da;padding:10px;border-radius:5px;margin:10px 0;}";
echo ".info{color:blue;background:#d1ecf1;padding:10px;border-radius:5px;margin:10px 0;}";
echo "pre{background:#fff;padding:10px;border-radius:5px;overflow:auto;}</style></head><body>";

echo "<h1>Git Conflict Fixer</h1>";
echo "<div class='info'>Fixing Git merge conflict for featured_category.tpl...</div>";

// Change to repository directory
chdir($repoPath);

// Step 1: Check current status
echo "<h3>Step 1: Checking Git Status</h3>";
$status = shell_exec('cd ' . escapeshellarg($repoPath) . ' && git status 2>&1');
echo "<pre>" . htmlspecialchars($status) . "</pre>";

// Step 2: Stash uncommitted changes
echo "<h3>Step 2: Stashing Uncommitted Changes</h3>";
$stash = shell_exec('cd ' . escapeshellarg($repoPath) . ' && git stash push -m "Auto-stash before merge - ' . date('Y-m-d H:i:s') . '" 2>&1');
echo "<pre>" . htmlspecialchars($stash) . "</pre>";

if (strpos($stash, 'No local changes') !== false) {
    echo "<div class='info'>No changes to stash.</div>";
} else {
    echo "<div class='success'>Changes stashed successfully!</div>";
}

// Step 3: Pull latest changes
echo "<h3>Step 3: Pulling Latest Changes from GitHub</h3>";
$pull = shell_exec('cd ' . escapeshellarg($repoPath) . ' && git pull origin main 2>&1');
echo "<pre>" . htmlspecialchars($pull) . "</pre>";

if (strpos($pull, 'Already up to date') !== false) {
    echo "<div class='info'>Repository is already up to date.</div>";
} elseif (strpos($pull, 'error') !== false || strpos($pull, 'fatal') !== false) {
    echo "<div class='error'>Error during pull. Check the output above.</div>";
} else {
    echo "<div class='success'>Successfully pulled latest changes!</div>";
}

// Step 4: Final status
echo "<h3>Step 4: Final Git Status</h3>";
$finalStatus = shell_exec('cd ' . escapeshellarg($repoPath) . ' && git status 2>&1');
echo "<pre>" . htmlspecialchars($finalStatus) . "</pre>";

// Check if conflict is resolved
if (strpos($finalStatus, 'featured_category.tpl') === false || strpos($finalStatus, 'modified') === false) {
    echo "<div class='success'><strong>SUCCESS!</strong> Conflict resolved! You can now try the merge in cPanel again.</div>";
} else {
    echo "<div class='error'>There may still be issues. Check the status above.</div>";
}

echo "<hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Go back to cPanel → Git Version Control</li>";
echo "<li>Try the pull/merge operation again</li>";
echo "<li><strong>DELETE THIS FILE</strong> after use for security!</li>";
echo "</ol>";

echo "</body></html>";
?>


