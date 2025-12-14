# How to Resolve Git Merge Conflict on Server (cPanel)

## Problem
cPanel is showing an error because there are uncommitted changes to `featured_category.tpl` on the server that conflict with the merge.

## Solution Options

### Option 1: Via SSH (Recommended - Fastest)

1. **Connect to your server via SSH**
   ```bash
   ssh your_username@your_server.com
   ```

2. **Navigate to your repository directory**
   ```bash
   cd /path/to/your/repository
   # Usually something like: cd ~/public_html or cd ~/domains/yourdomain.com/public_html
   ```

3. **Check current status**
   ```bash
   git status
   ```

4. **Stash the uncommitted changes**
   ```bash
   git stash push -m "Stashing server changes before merge"
   ```

5. **Pull the latest changes**
   ```bash
   git pull origin main
   ```

6. **Done!** The conflict is resolved.

---

### Option 2: Via cPanel File Manager

1. **Log into cPanel**
2. **Open File Manager**
3. **Navigate to**: `catalog/view/theme/ranger_fashion/template/module/`
4. **Find**: `featured_category.tpl`
5. **Download a backup** (right-click → Download)
6. **Delete the file** (or rename it to `featured_category.tpl.backup`)
7. **Go back to Git Version Control in cPanel**
8. **Try the pull/merge again**

---

### Option 3: Via cPanel Git Interface

1. **Log into cPanel**
2. **Go to Git Version Control**
3. **Find your repository**
4. **Look for "Uncommitted Changes" or "Status"**
5. **Find `featured_category.tpl`**
6. **Click "Discard Changes" or "Revert"**
7. **Try the pull/merge again**

---

### Option 4: Force Reset (Use with Caution)

**⚠️ WARNING: This will discard all uncommitted changes on the server!**

If you're sure you don't need the server changes, run via SSH:

```bash
cd /path/to/your/repository
git reset --hard HEAD
git clean -fd
git pull origin main
```

---

## Quick SSH Commands (Copy & Paste)

If you have SSH access, copy and paste these commands:

```bash
# Navigate to your repo (adjust path)
cd ~/public_html/fashion

# Stash changes and pull
git stash push -m "Resolving conflict"
git pull origin main

# Check status
git status
```

---

## Need Help?

If you don't have SSH access, use **Option 2** or **Option 3** via cPanel's web interface.


