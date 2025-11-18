#!/bin/bash
# cPanel Merge Conflict Fix Script
# Run this script on your cPanel server via SSH or Terminal

cd /home2/masterco/ruplexa1.master.com.bd

echo "=========================================="
echo "Fixing cPanel Merge Conflict"
echo "=========================================="
echo ""

echo "Step 1: Discarding local changes..."
git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css
git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl

echo ""
echo "Step 2: Pulling latest from GitHub..."
git pull origin main

echo ""
echo "Step 3: Checking status..."
git status

echo ""
echo "=========================================="
echo "Done! Your repository should now be synced."
echo "=========================================="

