# Fix Git Merge Conflict on Server

## The Problem
Your server has local changes to these files:
- `catalog/view/theme/ranger_fashion/stylesheet/new_header.css`
- `catalog/view/theme/ranger_fashion/template/common/navigation.tpl`

These changes conflict with the repository version.

## Solution Options

### Option 1: Stash Changes (Recommended - Saves changes for later)
Run these commands on your server via SSH or cPanel Terminal:

```bash
cd /path/to/your/project  # Replace with your actual path
git stash
git pull origin main
```

If you need the stashed changes later:
```bash
git stash pop
```

### Option 2: Discard Server Changes (Use if server changes are not needed)
```bash
cd /path/to/your/project
git reset --hard origin/main
git pull origin main
```

⚠️ **Warning**: This will permanently delete any local changes on the server.

### Option 3: Commit Server Changes First
If the server changes are important:
```bash
cd /path/to/your/project
git add catalog/view/theme/ranger_fashion/stylesheet/new_header.css
git add catalog/view/theme/ranger_fashion/template/common/navigation.tpl
git commit -m "Server-side changes before merge"
git pull origin main
```

## Using cPanel Git Interface

1. Go to **cPanel → Git Version Control**
2. Find your repository
3. Click on the repository name
4. Look for options like:
   - **"Stash"** - Saves changes temporarily
   - **"Commit"** - Commits local changes
   - **"Reset"** - Discards local changes
5. After stashing/committing, try pulling again

## Quick Fix Script

I've created a script file `fix_server_git_conflict.sh` that you can upload to your server and run:

```bash
chmod +x fix_server_git_conflict.sh
./fix_server_git_conflict.sh
```

## Current Repository Status

✅ All changes are committed and pushed to GitHub
✅ Latest commit: `d4368a3e` - "Replace all #FF6A00 with #A68A6A throughout project"
✅ Repository is clean and up to date

The conflict is only on the server side, not in the repository.

