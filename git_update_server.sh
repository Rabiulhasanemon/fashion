#!/bin/bash
# Git Update Script for Server
# This script handles local changes before pulling from GitHub

echo "Starting Git update process..."

# Navigate to the project directory
cd "$(dirname "$0")"

# Check if we're in a git repository
if [ ! -d .git ]; then
    echo "Error: Not a git repository"
    exit 1
fi

# Fetch latest changes from remote
echo "Fetching latest changes from GitHub..."
git fetch origin main

# Check if there are local changes
if [ -n "$(git status --porcelain)" ]; then
    echo "Local changes detected. Stashing them..."
    git stash save "Local changes before pull - $(date '+%Y-%m-%d %H:%M:%S')"
fi

# Pull latest changes
echo "Pulling latest changes from GitHub..."
git pull origin main

# Check if pull was successful
if [ $? -eq 0 ]; then
    echo "Successfully updated from GitHub!"
    
    # Try to apply stashed changes if any
    if git stash list | grep -q "Local changes before pull"; then
        echo "Attempting to apply stashed changes..."
        git stash pop
        
        if [ $? -ne 0 ]; then
            echo "Warning: There were conflicts when applying stashed changes."
            echo "You may need to resolve conflicts manually."
        fi
    fi
else
    echo "Error: Failed to pull from GitHub"
    exit 1
fi

echo "Git update process completed!"


