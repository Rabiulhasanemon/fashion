<?php
/**
 * Automatic Deployment Script for GitHub to cPanel
 * 
 * This script can be called by GitHub webhooks to automatically
 * pull the latest code from your repository.
 * 
 * SECURITY: Change the secret token below!
 */

// ============================================
// CONFIGURATION - UPDATE THESE VALUES
// ============================================

// Secret token for GitHub webhook (change this to a random string)
// Generate a secure random string and update this!
define('WEBHOOK_SECRET', 'your-secret-token-change-this-12345');

// Path to your project root (absolute path on server)
define('DEPLOY_PATH', '/home/masterco/cosmetics.master.com.bd');

// Git branch to deploy (usually 'main' or 'master')
define('GIT_BRANCH', 'main');

// Log file path
define('LOG_FILE', DEPLOY_PATH . '/deploy.log');

// ============================================
// SECURITY CHECK
// ============================================

// Only allow POST requests (GitHub webhooks use POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed. Use POST.');
}

// Verify GitHub webhook secret (if provided)
if (isset($_SERVER['HTTP_X_HUB_SIGNATURE_256'])) {
    $signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'];
    $payload = file_get_contents('php://input');
    $expected_signature = 'sha256=' . hash_hmac('sha256', $payload, WEBHOOK_SECRET);
    
    if (!hash_equals($expected_signature, $signature)) {
        http_response_code(403);
        die('Invalid signature');
    }
}

// Optional: Restrict by IP (GitHub webhook IPs)
// Uncomment and add GitHub IP ranges if needed
/*
$allowed_ips = ['192.30.252.0/22', '185.199.108.0/22', '140.82.112.0/20'];
$client_ip = $_SERVER['REMOTE_ADDR'];
if (!in_array($client_ip, $allowed_ips)) {
    http_response_code(403);
    die('IP not allowed');
}
*/

// ============================================
// LOGGING FUNCTION
// ============================================

function logMessage($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message\n";
    file_put_contents(LOG_FILE, $logEntry, FILE_APPEND);
    echo $logEntry; // Also output for webhook response
}

// ============================================
// DEPLOYMENT FUNCTION
// ============================================

function deploy() {
    logMessage("=== Deployment Started ===");
    
    // Change to project directory
    if (!chdir(DEPLOY_PATH)) {
        logMessage("ERROR: Cannot change to directory: " . DEPLOY_PATH);
        return false;
    }
    
    // Get current branch and commit
    $current_branch = trim(shell_exec('git rev-parse --abbrev-ref HEAD 2>&1'));
    $current_commit = trim(shell_exec('git rev-parse HEAD 2>&1'));
    logMessage("Current branch: $current_branch");
    logMessage("Current commit: $current_commit");
    
    // Fetch latest changes
    logMessage("Fetching latest changes from GitHub...");
    $fetch_output = shell_exec('git fetch origin ' . GIT_BRANCH . ' 2>&1');
    logMessage("Fetch output: $fetch_output");
    
    // Checkout the branch
    logMessage("Checking out branch: " . GIT_BRANCH);
    $checkout_output = shell_exec('git checkout ' . GIT_BRANCH . ' 2>&1');
    logMessage("Checkout output: $checkout_output");
    
    // Pull latest changes
    logMessage("Pulling latest changes...");
    $pull_output = shell_exec('git pull origin ' . GIT_BRANCH . ' 2>&1');
    logMessage("Pull output: $pull_output");
    
    // Get new commit hash
    $new_commit = trim(shell_exec('git rev-parse HEAD 2>&1'));
    logMessage("New commit: $new_commit");
    
    // Clear cache (if needed)
    if (is_dir(DEPLOY_PATH . '/system/cache')) {
        logMessage("Clearing cache...");
        $cache_files = glob(DEPLOY_PATH . '/system/cache/*');
        foreach ($cache_files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        logMessage("Cache cleared");
    }
    
    // Set proper permissions (optional)
    // shell_exec('chmod -R 755 ' . DEPLOY_PATH . ' 2>&1');
    // shell_exec('find ' . DEPLOY_PATH . ' -type f -exec chmod 644 {} \; 2>&1');
    
    logMessage("=== Deployment Completed Successfully ===");
    return true;
}

// ============================================
// MAIN EXECUTION
// ============================================

try {
    // Parse GitHub webhook payload (optional, for logging)
    $payload = json_decode(file_get_contents('php://input'), true);
    if ($payload) {
        $repo = isset($payload['repository']['full_name']) ? $payload['repository']['full_name'] : 'unknown';
        $ref = isset($payload['ref']) ? $payload['ref'] : 'unknown';
        $pusher = isset($payload['pusher']['name']) ? $payload['pusher']['name'] : 'unknown';
        logMessage("Webhook received from: $repo, ref: $ref, pushed by: $pusher");
    }
    
    // Execute deployment
    $success = deploy();
    
    if ($success) {
        http_response_code(200);
        echo "Deployment successful!";
    } else {
        http_response_code(500);
        echo "Deployment failed. Check logs.";
    }
    
} catch (Exception $e) {
    logMessage("ERROR: " . $e->getMessage());
    http_response_code(500);
    echo "Deployment error: " . $e->getMessage();
}

