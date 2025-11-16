#!/bin/bash
# Script to fix Git merge conflict on server
# Run this on your server via SSH or cPanel Terminal

echo "=== Fixing Git Merge Conflict ==="
echo ""

# Navigate to project directory (adjust path as needed)
# cd /home/yourusername/public_html
# OR
# cd /home/yourusername/ruplexa1.master.com.bd

echo "Step 1: Stashing local changes..."
git stash

echo ""
echo "Step 2: Pulling latest changes from repository..."
git pull origin main

echo ""
echo "Step 3: Checking status..."
git status

echo ""
echo "=== Done! ==="
echo "If you need to recover stashed changes, run: git stash pop"

