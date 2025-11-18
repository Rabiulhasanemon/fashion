#!/bin/bash
# Script to resolve cPanel merge conflict
# Run this script on your cPanel server via SSH or Terminal

echo "=========================================="
echo "cPanel Merge Conflict Resolution Script"
echo "=========================================="
echo ""
echo "This script will help resolve the merge conflict on cPanel."
echo "Files with conflicts:"
echo "  - catalog/view/theme/ranger_fashion/stylesheet/new_header.css"
echo "  - catalog/view/theme/ranger_fashion/template/common/navigation.tpl"
echo ""
echo "Choose an option:"
echo "1. Stash local changes, pull, then reapply (recommended if you want to keep local changes)"
echo "2. Discard local changes and pull latest from GitHub (recommended if local changes are not needed)"
echo "3. Commit local changes first, then pull and merge"
echo ""
read -p "Enter option (1, 2, or 3): " option

case $option in
    1)
        echo ""
        echo "Stashing local changes..."
        git stash save "Local changes before pull - $(date)"
        echo "Pulling latest changes from GitHub..."
        git pull origin main
        echo "Reapplying stashed changes..."
        git stash pop
        echo ""
        echo "Done! If there are conflicts, you'll need to resolve them manually."
        ;;
    2)
        echo ""
        echo "Discarding local changes..."
        git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css
        git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl
        echo "Pulling latest changes from GitHub..."
        git pull origin main
        echo ""
        echo "Done! Local changes have been discarded and latest code pulled."
        ;;
    3)
        echo ""
        echo "Staging files..."
        git add catalog/view/theme/ranger_fashion/stylesheet/new_header.css
        git add catalog/view/theme/ranger_fashion/template/common/navigation.tpl
        echo "Committing local changes..."
        git commit -m "Commit local cPanel changes before merge - $(date)"
        echo "Pulling and merging latest changes..."
        git pull origin main
        echo ""
        echo "Done! If there are merge conflicts, resolve them and commit."
        ;;
    *)
        echo "Invalid option. Exiting."
        exit 1
        ;;
esac

echo ""
echo "Current git status:"
git status

