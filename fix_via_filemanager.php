<?php
/**
 * Alternative Fix Method: Via File Manager
 * 
 * This script can be uploaded via cPanel File Manager and run through browser
 * No SSH/Terminal access needed!
 * 
 * INSTRUCTIONS:
 * 1. Download this file
 * 2. Go to cPanel → File Manager
 * 3. Navigate to: /home2/masterco/ruplexa1.master.com.bd
 * 4. Upload this file there
 * 5. Right-click the file → "Edit" → Change permissions to 755 (or just run it)
 * 6. Access via: https://ruplexa1.master.com.bd/fix_via_filemanager.php
 * 7. Delete the file after use for security
 */

// Security headers
header('Content-Type: text/html; charset=utf-8');

// Get repository path
$repo_path = '/home2/masterco/ruplexa1.master.com.bd';

// Check if directory exists
if (!is_dir($repo_path)) {
    die("ERROR: Repository directory not found: $repo_path");
}

// Change to repository directory
chdir($repo_path);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix cPanel Merge Conflict - File Manager Method</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            border: 1px solid #dee2e6;
            font-size: 12px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px;
        }
        button:hover {
            background: #0056b3;
        }
        .danger {
            background: #dc3545;
        }
        .danger:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix cPanel Merge Conflict</h1>
        <p><strong>Repository Path:</strong> <?php echo htmlspecialchars($repo_path); ?></p>
        <p><strong>Current Directory:</strong> <?php echo htmlspecialchars(getcwd()); ?></p>

        <?php
        // Check if .git exists
        if (!is_dir('.git')) {
            echo '<div class="error">ERROR: .git directory not found. Make sure this file is in your repository root.</div>';
            echo '</div></body></html>';
            exit;
        }

        // Check if action is requested
        if (isset($_GET['action']) && $_GET['action'] === 'fix') {
            echo '<div class="info"><h2>Executing Fix...</h2></div>';
            echo '<pre>';

            // Step 1: Discard local changes
            echo "Step 1: Discarding local changes to conflicting files...\n";
            echo str_repeat("=", 60) . "\n";
            
            $files = [
                'catalog/view/theme/ranger_fashion/stylesheet/new_header.css',
                'catalog/view/theme/ranger_fashion/template/common/navigation.tpl'
            ];

            $all_ok = true;
            foreach ($files as $file) {
                $result = shell_exec('git checkout -- ' . escapeshellarg($file) . ' 2>&1');
                if (file_exists($file)) {
                    echo "✅ Fixed: $file\n";
                } else {
                    echo "⚠️  File not found: $file\n";
                }
                if (!empty(trim($result))) {
                    echo "   Output: " . htmlspecialchars($result) . "\n";
                }
            }

            echo "\n" . str_repeat("=", 60) . "\n";
            echo "Step 2: Pulling latest from GitHub...\n";
            echo str_repeat("=", 60) . "\n";
            
            $pull_result = shell_exec('git pull origin main 2>&1');
            echo htmlspecialchars($pull_result);
            
            echo "\n" . str_repeat("=", 60) . "\n";
            echo "Step 3: Final Status...\n";
            echo str_repeat("=", 60) . "\n";
            
            $status = shell_exec('git status 2>&1');
            echo htmlspecialchars($status);
            
            echo '</pre>';

            // Check if successful
            if (strpos($pull_result, 'error') === false && strpos($pull_result, 'Aborting') === false) {
                echo '<div class="success"><h2>✅ SUCCESS!</h2>';
                echo '<p>The merge conflict has been resolved. Your cPanel repository is now synced with GitHub.</p>';
                echo '<p><strong>Next Steps:</strong></p>';
                echo '<ol>';
                echo '<li>Go to cPanel → Git Version Control</li>';
                echo '<li>Click "Pull or Deploy"</li>';
                echo '<li>It should now work without errors!</li>';
                echo '</ol>';
                echo '<p class="warning"><strong>⚠️ IMPORTANT:</strong> Delete this file (fix_via_filemanager.php) for security reasons after you\'re done.</p>';
                echo '</div>';
            } else {
                echo '<div class="error"><h2>⚠️ There may still be issues</h2>';
                echo '<p>Please check the output above. You may need to run these commands via SSH/Terminal.</p>';
                echo '</div>';
            }
        } else {
            // Show instructions
            echo '<div class="info">';
            echo '<h2>Instructions</h2>';
            echo '<p>This script will:</p>';
            echo '<ol>';
            echo '<li>Discard local changes to the conflicting files</li>';
            echo '<li>Pull the latest code from GitHub</li>';
            echo '<li>Sync your cPanel repository</li>';
            echo '</ol>';
            echo '<p><strong>Click the button below to execute the fix:</strong></p>';
            echo '</div>';
            
            echo '<div style="text-align: center; margin: 30px 0;">';
            echo '<a href="?action=fix"><button>🔧 Fix Merge Conflict Now</button></a>';
            echo '</div>';
            
            echo '<div class="warning">';
            echo '<h3>⚠️ Security Note</h3>';
            echo '<p>After using this script, please delete it from your server for security reasons.</p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>

