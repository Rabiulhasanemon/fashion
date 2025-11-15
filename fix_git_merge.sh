#!/bin/bash
# Script to fix Git merge conflict in cPanel
# This script will discard local changes and pull the latest from GitHub

echo "Fixing Git merge conflict..."
echo "Current directory: $(pwd)"

# Navigate to the website root (adjust path if needed)
cd "$(dirname "$0")" || cd /home/*/public_html || cd /home/*/domains/*/public_html

echo "Working directory: $(pwd)"

# Check if we're in a git repository
if [ ! -d ".git" ]; then
    echo "Error: Not a git repository. Please run this script from your website root."
    exit 1
fi

# Show current status
echo "Current Git status:"
git status --short

# Discard local changes to navigation.tpl
echo "Discarding local changes to navigation.tpl..."
git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl

# Pull latest changes from GitHub
echo "Pulling latest changes from GitHub..."
git pull origin main

echo "Done! The merge conflict has been resolved."

