# Solve cPanel Merge Conflict - Step by Step

## The Problem
cPanel shows this error when trying to pull from GitHub:
```
error: Your local changes to the following files would be overwritten by merge:
  catalog/view/theme/ranger_fashion/stylesheet/new_header.css
  catalog/view/theme/ranger_fashion/template/common/navigation.tpl
```

## Solution: Run These Commands on cPanel

### Method 1: Via cPanel Terminal (Easiest)

1. **Login to cPanel**
2. **Go to**: Advanced → Terminal (or search for "Terminal" in cPanel)
3. **Navigate to your repository**:
   ```bash
   cd ~/public_html
   # OR wherever your repository is located
   ```
4. **Run these commands** (copy and paste):
   ```bash
   git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css
   git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl
   git pull origin main
   ```

### Method 2: Via SSH

1. **Connect to your server via SSH**
2. **Navigate to repository**:
   ```bash
   cd /path/to/your/repository
   ```
3. **Run the fix commands**:
   ```bash
   git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css
   git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl
   git pull origin main
   ```

### Method 3: Via cPanel Git Version Control

1. **Go to**: cPanel → Git Version Control
2. **Find your repository**
3. **Click**: "Manage" or "Terminal" button
4. **Run**:
   ```bash
   git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css catalog/view/theme/ranger_fashion/template/common/navigation.tpl
   git pull origin main
   ```

### Method 4: One-Line Command

Copy and paste this single command:
```bash
git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css catalog/view/theme/ranger_fashion/template/common/navigation.tpl && git pull origin main
```

## What These Commands Do

1. `git checkout -- filename` - Discards local changes to the file, restoring it to the last committed version
2. `git pull origin main` - Pulls the latest changes from GitHub

## After Running

After executing these commands, your cPanel repository will:
- ✅ Discard any local changes to those two files
- ✅ Pull the latest version from GitHub
- ✅ Be in sync with your GitHub repository

## Verify It Worked

Run this to check:
```bash
git status
```

You should see: `Your branch is up to date with 'origin/main'` and `nothing to commit, working tree clean`

## Need Help?

If you still get errors:
1. Make sure you're in the correct directory (where your `.git` folder is)
2. Check that git is installed: `which git`
3. Verify your remote: `git remote -v`

