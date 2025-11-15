# Fix Git Merge Conflict in cPanel

## Problem
cPanel shows error: "Your local changes to the following files would be overwritten by merge: catalog/view/theme/ranger_fashion/template/common/navigation.tpl"

## Solution Options

### Option 1: Use the PHP Script (Easiest)
1. Upload `fix_git_merge.php` to your website root directory
2. Access it via browser: `https://yourdomain.com/fix_git_merge.php`
3. Or run via SSH: `php fix_git_merge.php`

### Option 2: Use the Shell Script (If you have SSH access)
1. Upload `fix_git_merge.sh` to your website root
2. Make it executable: `chmod +x fix_git_merge.sh`
3. Run it: `./fix_git_merge.sh`

### Option 3: Manual Fix via cPanel Terminal
If cPanel has Terminal/SSH access, run these commands:

```bash
cd /home/yourusername/public_html
git checkout -- catalog/view/theme/ranger_fashion/template/common/navigation.tpl
git pull origin main
```

### Option 4: Manual Fix via cPanel File Manager
1. Go to cPanel → File Manager
2. Navigate to: `catalog/view/theme/ranger_fashion/template/common/`
3. Delete or rename `navigation.tpl` (backup first!)
4. Go to Git Version Control in cPanel
5. Click "Pull" to get the latest version from GitHub

### Option 5: Force Reset (Use with caution - overwrites all local changes)
```bash
cd /home/yourusername/public_html
git fetch origin
git reset --hard origin/main
```

## What the script does:
1. Discards local changes to `navigation.tpl`
2. Pulls the latest version from GitHub
3. Resolves the merge conflict

## After running the fix:
- The header bottom navigation will be hidden in mobile/tablet views
- Desktop/laptop views remain unchanged
- All latest changes from GitHub will be applied

