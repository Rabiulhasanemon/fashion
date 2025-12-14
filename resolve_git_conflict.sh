#!/bin/bash
# Script to resolve Git merge conflict on server
# Run this script via SSH on your server in the repository directory

echo "Resolving Git merge conflict for featured_category.tpl..."

# Navigate to repository directory (adjust path as needed)
# cd /path/to/your/repository

# Check current status
echo "Current Git status:"
git status

# Stash any uncommitted changes
echo "Stashing uncommitted changes..."
git stash push -m "Stashing changes before merge - $(date)"

# Pull latest changes
echo "Pulling latest changes from remote..."
git pull origin main

# If you want to apply stashed changes back, uncomment the next line:
# git stash pop

echo "Conflict resolved! Repository is now up to date."


