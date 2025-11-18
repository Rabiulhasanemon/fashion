# Fix cPanel Merge Conflict

## Problem
cPanel is showing this error when trying to pull from GitHub:
```
error: Your local changes to the following files would be overwritten by merge:
  catalog/view/theme/ranger_fashion/stylesheet/new_header.css
  catalog/view/theme/ranger_fashion/template/common/navigation.tpl
Please commit your changes or stash them before you merge.
```

## Solution Options

### Option 1: Discard Local Changes (Recommended if local changes are not needed)

**Via cPanel Git Version Control:**
1. Go to cPanel → Git Version Control
2. Navigate to your repository
3. Click on "Terminal" or "SSH Access"
4. Run these commands:

```bash
git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css
git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl
git pull origin main
```

**Or via SSH:**
```bash
cd /path/to/your/repository
git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css
git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl
git pull origin main
```

### Option 2: Stash Local Changes (Keep local changes for later)

```bash
git stash save "Local cPanel changes - $(date)"
git pull origin main
git stash pop
```

If conflicts occur after `git stash pop`, resolve them manually.

### Option 3: Commit Local Changes First

```bash
git add catalog/view/theme/ranger_fashion/stylesheet/new_header.css
git add catalog/view/theme/ranger_fashion/template/common/navigation.tpl
git commit -m "Local cPanel changes before merge"
git pull origin main
```

## Quick Fix (One-Line Command)

If you want to discard local changes and pull immediately:

```bash
git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css catalog/view/theme/ranger_fashion/template/common/navigation.tpl && git pull origin main
```

## After Fixing

After resolving the conflict, your cPanel repository will be in sync with GitHub, and you should be able to pull updates without conflicts.

