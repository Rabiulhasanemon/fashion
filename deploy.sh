#!/bin/bash
# Automatic Deployment Script for GitHub to cPanel
# This script can be run manually or via cron job

# Configuration
DEPLOY_PATH="/home/masterco/cosmetics.master.com.bd"
GIT_BRANCH="main"
LOG_FILE="$DEPLOY_PATH/deploy.log"

# Logging function
log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

log_message "=== Deployment Started ==="

# Change to project directory
cd "$DEPLOY_PATH" || {
    log_message "ERROR: Cannot change to directory: $DEPLOY_PATH"
    exit 1
}

# Get current status
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
CURRENT_COMMIT=$(git rev-parse HEAD)
log_message "Current branch: $CURRENT_BRANCH"
log_message "Current commit: $CURRENT_COMMIT"

# Fetch latest changes
log_message "Fetching latest changes from GitHub..."
git fetch origin "$GIT_BRANCH" 2>&1 | tee -a "$LOG_FILE"

# Checkout the branch
log_message "Checking out branch: $GIT_BRANCH"
git checkout "$GIT_BRANCH" 2>&1 | tee -a "$LOG_FILE"

# Pull latest changes
log_message "Pulling latest changes..."
git pull origin "$GIT_BRANCH" 2>&1 | tee -a "$LOG_FILE"

# Get new commit hash
NEW_COMMIT=$(git rev-parse HEAD)
log_message "New commit: $NEW_COMMIT"

# Clear cache
if [ -d "$DEPLOY_PATH/system/cache" ]; then
    log_message "Clearing cache..."
    find "$DEPLOY_PATH/system/cache" -type f -delete
    log_message "Cache cleared"
fi

# Set proper permissions (optional)
# chmod -R 755 "$DEPLOY_PATH"
# find "$DEPLOY_PATH" -type f -exec chmod 644 {} \;

log_message "=== Deployment Completed Successfully ==="

