# Alternative Methods to Fix cPanel Merge Conflict

## Problem
cPanel shows merge conflict error when trying to pull from GitHub.

## Method 1: Via cPanel File Manager + Browser (NO SSH NEEDED!)

**This is the easiest method if you don't have SSH/Terminal access.**

### Steps:
1. **Download** `fix_via_filemanager.php` from GitHub
2. **Login to cPanel**
3. **Go to**: File Manager
4. **Navigate to**: `/home2/masterco/ruplexa1.master.com.bd`
5. **Upload** the `fix_via_filemanager.php` file there
6. **Access via browser**: 
   ```
   https://ruplexa1.master.com.bd/fix_via_filemanager.php
   ```
7. **Click** the "Fix Merge Conflict Now" button
8. **Delete** the file after use for security

### Advantages:
- ✅ No SSH/Terminal access needed
- ✅ Works through browser
- ✅ Visual feedback
- ✅ Easy to use

---

## Method 2: Via cPanel Terminal (If Available)

1. **Go to**: cPanel → Advanced → Terminal
2. **Run**:
   ```bash
   cd /home2/masterco/ruplexa1.master.com.bd
   git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css
   git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl
   git pull origin main
   ```

---

## Method 3: Via SSH (If You Have Access)

1. **Connect** to your server via SSH
2. **Run**:
   ```bash
   cd /home2/masterco/ruplexa1.master.com.bd
   git checkout -- catalog/view/theme/ranger_fashion/stylesheet/new_header.css
   git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl
   git pull origin main
   ```

---

## Method 4: Manual File Replacement via File Manager

**If all else fails, you can manually replace the files:**

1. **Go to**: cPanel → File Manager
2. **Navigate to**: `/home2/masterco/ruplexa1.master.com.bd/catalog/view/theme/ranger_fashion/stylesheet/`
3. **Delete** `new_header.css` (or rename it to `new_header.css.backup`)
4. **Navigate to**: `/home2/masterco/ruplexa1.master.com.bd/catalog/view/theme/ranger_fashion/template/common/`
5. **Delete** `navigation.tpl` (or rename it to `navigation.tpl.backup`)
6. **Go to**: cPanel → Git Version Control
7. **Click**: "Pull or Deploy"
8. Git will restore the files from GitHub

---

## Method 5: Using cPanel Git Interface (If Available)

Some cPanel versions have a "Reset" or "Discard Changes" option:

1. **Go to**: cPanel → Git Version Control
2. **Find** your repository
3. **Look for**: "Discard Changes" or "Reset" button
4. **Select** the conflicting files
5. **Click** "Discard" or "Reset"
6. **Then** click "Pull or Deploy"

---

## Method 6: Create .cpanel.yml for Auto-Deploy (Future)

Create a `.cpanel.yml` file in your repository root to enable automatic deployments:

```yaml
---
deployment:
  tasks:
    - export DEPLOYPATH=/home2/masterco/ruplexa1.master.com.bd
    - /bin/cp -R * $DEPLOYPATH/
```

This allows cPanel to auto-deploy without conflicts.

---

## Recommended: Method 1 (File Manager + Browser)

**This is the easiest and doesn't require SSH access!**

Just upload `fix_via_filemanager.php` and run it through your browser.

---

## After Any Method

1. **Verify** it worked:
   - Go to cPanel → Git Version Control
   - Click "Pull or Deploy"
   - Should work without errors now!

2. **Check status** (if you have terminal access):
   ```bash
   cd /home2/masterco/ruplexa1.master.com.bd
   git status
   ```

You should see: `Your branch is up to date with 'origin/main'`

