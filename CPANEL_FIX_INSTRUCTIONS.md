# Fix cPanel Merge Conflict - Step by Step

## Problem
cPanel shows error when trying to pull:
```
error: Your local changes to the following files would be overwritten by merge:
  catalog/view/theme/ranger_fashion/stylesheet/new_header.css
  catalog/view/theme/ranger_fashion/template/common/navigation.tpl
```

## Solution: Fix via cPanel Terminal

### Method 1: Using cPanel Terminal (Recommended)

1. **Login to cPanel**
2. **Go to**: Advanced → Terminal (or search "Terminal")
3. **Run these commands** (copy and paste):

```bash
cd /home2/masterco/ruplexa1.master.com.bd
git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css
git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl
git pull origin main
```

### Method 2: One-Line Command

```bash
cd /home2/masterco/ruplexa1.master.com.bd && git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css catalog/view/theme/ranger_fashion/template/common/navigation.tpl && git pull origin main
```

### Method 3: Using the Script

1. **Upload** `cpanel_fix.sh` to your cPanel root directory
2. **Make it executable**:
   ```bash
   chmod +x cpanel_fix.sh
   ```
3. **Run it**:
   ```bash
   ./cpanel_fix.sh
   ```

## After Running

1. Go back to cPanel → Git Version Control
2. Click "Pull or Deploy"
3. It should now work without errors!

## Verify It Worked

Run this to check:
```bash
cd /home2/masterco/ruplexa1.master.com.bd
git status
```

You should see: `Your branch is up to date with 'origin/main'`

## What This Does

- **Discards** local changes on cPanel to those two files
- **Pulls** the latest version from GitHub (commit 784a729c and newer)
- **Syncs** your cPanel repository with GitHub

Your cPanel repository will be updated with all the latest changes including:
- Banner image size fix
- All recent commits
- Latest code from GitHub

