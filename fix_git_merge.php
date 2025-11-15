<?php
/**
 * PHP Script to fix Git merge conflict in cPanel
 * Run this file via browser or command line
 * 
 * Usage: php fix_git_merge.php
 */

// Get the current directory
$currentDir = __DIR__;
echo "Current directory: $currentDir\n";

// Change to website root if needed
$possibleRoots = [
    $currentDir,
    dirname($currentDir),
    '/home/' . get_current_user() . '/public_html',
];

$gitRoot = null;
foreach ($possibleRoots as $root) {
    if (is_dir($root . '/.git')) {
        $gitRoot = $root;
        break;
    }
}

if (!$gitRoot) {
    die("Error: Git repository not found. Please run this script from your website root.\n");
}

chdir($gitRoot);
echo "Working directory: " . getcwd() . "\n";

// Execute git commands
$commands = [
    'git status --short',
    'git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl',
    'git pull origin main'
];

foreach ($commands as $command) {
    echo "\nExecuting: $command\n";
    echo "----------------------------------------\n";
    $output = [];
    $returnVar = 0;
    exec($command . ' 2>&1', $output, $returnVar);
    echo implode("\n", $output) . "\n";
    
    if ($returnVar !== 0 && strpos($command, 'status') === false) {
        echo "Warning: Command returned error code $returnVar\n";
    }
}

echo "\n========================================\n";
echo "Done! The merge conflict should be resolved.\n";
echo "Please check your cPanel Git interface to confirm.\n";
?>

