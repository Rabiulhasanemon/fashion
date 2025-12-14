# Fix cPanel Git Merge Conflict - Complete Instructions

## Problem
cPanel shows error: "Your local changes to the following files would be overwritten by merge: catalog/view/theme/ranger_fashion/template/module/featured_category.tpl"

## Solution Options

---

## ✅ OPTION 1: Use PHP Script (Easiest - No SSH Required)

### Step 1: Upload the Fix Script
1. Upload `fix_cpanel_git_conflict.php` to your website root directory
   - Usually: `public_html/` or `domains/yourdomain.com/public_html/`

### Step 2: Edit the Password
1. Open `fix_cpanel_git_conflict.php` in a text editor
2. Find this line: `$SECURITY_PASSWORD = 'CHANGE_THIS_PASSWORD_OR_DELETE_FILE_AFTER_USE';`
3. Change it to: `$SECURITY_PASSWORD = 'your_secure_password_here';`
4. Save and upload again

### Step 3: Run the Script
1. Open your browser
2. Go to: `https://yourdomain.com/fix_cpanel_git_conflict.php?password=your_secure_password_here`
3. The script will:
   - Stash uncommitted changes
   - Pull latest code from GitHub
   - Resolve the conflict

### Step 4: Delete the Script
**IMPORTANT:** Delete `fix_cpanel_git_conflict.php` after use for security!

### Step 5: Try Merge Again in cPanel
1. Go to cPanel → Git Version Control
2. Try the pull/merge operation again
3. It should work now!

---

## ✅ OPTION 2: Via SSH (Fastest)

### Step 1: Connect via SSH
```bash
ssh your_username@your_server.com
```

### Step 2: Navigate to Repository
```bash
cd ~/public_html/fashion
# OR
cd ~/domains/yourdomain.com/public_html/fashion
```

### Step 3: Fix the Conflict
```bash
# Stash uncommitted changes
git stash push -m "Resolving conflict - $(date)"

# Pull latest changes
git pull origin main
```

### Step 4: Verify
```bash
git status
```

### Step 5: Try Merge in cPanel
Go back to cPanel and try the merge again.

---

## ✅ OPTION 3: Via cPanel File Manager

### Step 1: Access File Manager
1. Log into cPanel
2. Open "File Manager"

### Step 2: Navigate to File
1. Go to: `catalog/view/theme/ranger_fashion/template/module/`
2. Find: `featured_category.tpl`

### Step 3: Backup and Delete
1. **Download a backup** (right-click → Download)
2. **Delete the file** (or rename to `featured_category.tpl.backup`)

### Step 4: Try Merge in cPanel
1. Go to Git Version Control
2. Try pull/merge again
3. Git will restore the file from the repository

---

## ✅ OPTION 4: Via cPanel Git Interface

### Step 1: Access Git Version Control
1. Log into cPanel
2. Go to "Git Version Control"

### Step 2: Find Uncommitted Changes
1. Look for "Uncommitted Changes" or "Status" section
2. Find `featured_category.tpl` in the list

### Step 3: Discard Changes
1. Click "Discard Changes" or "Revert" button
2. Confirm the action

### Step 4: Try Merge Again
1. Try the pull/merge operation
2. It should work now!

---

## Quick SSH Commands (Copy & Paste)

If you have SSH access, run these commands:

```bash
cd ~/public_html/fashion
git stash
git pull origin main
git status
```

---

## Troubleshooting

### If stash fails:
```bash
git reset --hard HEAD
git clean -fd
git pull origin main
```

### If pull fails:
```bash
git fetch origin
git reset --hard origin/main
```

### Check repository path:
```bash
pwd
git remote -v
```

---

## Security Note

**ALWAYS DELETE** `fix_cpanel_git_conflict.php` after use! It contains commands that modify your repository.

---

## Need More Help?

1. Check the error message in cPanel
2. Verify your repository path
3. Make sure you have write permissions
4. Contact your hosting provider if issues persist


