# 🎯 Your Auto-Deploy Setup (Step-by-Step)

## Your Goal
✅ Push to GitHub → `/cosmetics.master.com.bd/` updates automatically  
✅ No more manual copying!

---

## 📋 Setup Steps

### Step 1: Make Sure Website Folder is a Git Repository

Your website folder (`/cosmetics.master.com.bd/`) needs to be connected to GitHub.

**Check if it's already a Git repo:**
- In cPanel File Manager, go to `/cosmetics.master.com.bd/`
- Look for a `.git` folder (hidden, might not show)
- Or check if you can run `git status` there

**If NOT a Git repo, initialize it:**

**Option A: Using cPanel Git Version Control**
1. Go to **Git Version Control** in cPanel
2. Click **"Create"**
3. Enter:
   - **Repository URL**: `https://github.com/Rabiulhasanemon/fashion.git`
   - **Repository Path**: `/home/masterco/cosmetics.master.com.bd` (or `/home2/masterco/cosmetics.master.com.bd` - check your exact path)
   - **Branch**: `main`
4. Click **"Create"**

**Option B: Using SSH/Terminal**
```bash
cd /home/masterco/cosmetics.master.com.bd
# OR (check which one is correct)
cd /home2/masterco/cosmetics.master.com.bd

git init
git remote add origin https://github.com/Rabiulhasanemon/fashion.git
git pull origin main
```

---

### Step 2: Upload deploy.php to Website Folder

1. **Upload `deploy.php`** to:
   - `/cosmetics.master.com.bd/deploy.php`
   - Or the full path: `/home/masterco/cosmetics.master.com.bd/deploy.php`

2. **Edit `deploy.php`** in cPanel File Manager:
   - Right-click → **Edit**
   - Find line 20:
     ```php
     define('DEPLOY_PATH', '/home/masterco/cosmetics.master.com.bd');
     ```
   - **Change to your exact path:**
     - Check in File Manager what the exact path is
     - It might be `/home/masterco/` or `/home2/masterco/`
     - Update the path to match exactly

3. **Change the secret** (line 17):
   ```php
   define('WEBHOOK_SECRET', 'change-this-to-random-string-12345');
   ```
   - Change to something like: `mySecretKey2024xyz`
   - **Remember this** - you'll need it for GitHub

4. **Save the file**

---

### Step 3: Set Up GitHub Webhook

1. **Go to GitHub:**
   - https://github.com/Rabiulhasanemon/fashion/settings/hooks

2. **Click "Add webhook"** (green button)

3. **Fill in the form:**
   ```
   Payload URL: https://cosmetics.master.com.bd/deploy.php
   Content type: application/json
   Secret: mySecretKey2024xyz  (same as in deploy.php)
   Which events: Just the push event
   Active: ✓ (checked)
   ```

4. **Click "Add webhook"**

---

### Step 4: Test It! 🚀

1. **Make a small test change:**
   - Edit any file (add a comment)
   - Or create a test file

2. **Commit and push:**
   ```bash
   git add .
   git commit -m "Test auto deploy"
   git push origin main
   ```

3. **Wait 10-30 seconds**

4. **Check your website** - files should be updated!

5. **Check the log:**
   - File: `/cosmetics.master.com.bd/deploy.log`
   - Or: `/home/masterco/cosmetics.master.com.bd/deploy.log`
   - This shows what happened during deployment

---

## ✅ How It Works Now

**Before:**
```
Push to GitHub → Go to /repositories/fashion/ → Copy files → Paste to /cosmetics.master.com.bd/
```

**After:**
```
Push to GitHub → /cosmetics.master.com.bd/ updates automatically! ✨
```

---

## 🔍 Verify Your Path

**To find your exact path:**
1. Go to cPanel **File Manager**
2. Navigate to your website folder
3. Look at the **path shown at the top** of File Manager
4. Copy that exact path
5. Use it in `deploy.php` (line 20)

**Common paths:**
- `/home/masterco/cosmetics.master.com.bd`
- `/home2/masterco/cosmetics.master.com.bd`
- `/home/masterco/public_html/cosmetics.master.com.bd`

---

## ⚠️ Important Notes

1. **Config files are safe:**
   - `config.php` and `admin/config.php` are in `.gitignore`
   - They won't be overwritten by Git
   - Your production credentials stay safe

2. **First time setup:**
   - Make sure your website folder has the latest code
   - You can do a manual pull first:
     - In cPanel Git: Click "Pull or Deploy" → "Update from Remote"

3. **If webhook fails:**
   - Check `deploy.log` for errors
   - Check GitHub webhook delivery status
   - Verify the secret matches in both places

---

## 🎉 You're Done!

Now every time you:
```bash
git push origin main
```

Your website at `/cosmetics.master.com.bd/` will automatically update!

No more manual copying needed! 🚀

