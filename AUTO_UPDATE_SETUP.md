# 🚀 Auto-Update Setup for Your Workflow

## Your Current Setup

✅ Git repository at: `/repositories/fashion/` (or `/home2/masterco/repositories/fashion/`)  
✅ Website files at: `/cosmetics.master.com.bd/` (your subdomain)  
✅ Want: Auto-update website when you push to GitHub

## Solution: Use deploy.php Webhook

The `deploy.php` script will automatically pull from GitHub directly to your website folder, so you don't need to manually copy files anymore!

---

## 📋 Setup Steps

### Step 1: Upload deploy.php to Your Website

1. **Upload `deploy.php`** to your website root:
   - Path: `/cosmetics.master.com.bd/deploy.php`
   - Or: `/home/masterco/cosmetics.master.com.bd/deploy.php`
   - Or: `/home2/masterco/cosmetics.master.com.bd/deploy.php`
   - (Use the exact path where your website files are)

2. **Edit `deploy.php`** on the server:
   - Open it in cPanel File Manager
   - Find this line (around line 20):
     ```php
     define('DEPLOY_PATH', '/home/masterco/cosmetics.master.com.bd');
     ```
   - Change it to your **exact website path**:
     - If it's `/home/masterco/cosmetics.master.com.bd` → keep it
     - If it's `/home2/masterco/cosmetics.master.com.bd` → change to that
     - Check in File Manager to see the exact path

3. **Change the secret token** (for security):
   - Find this line:
     ```php
     define('WEBHOOK_SECRET', 'your-secret-token-change-this-12345');
     ```
   - Change to a random string, like: `mySecret123xyz789`
   - **Remember this** - you'll need it for GitHub webhook

4. **Set file permissions:**
   - Right-click `deploy.php` → Change Permissions → `644`

---

### Step 2: Initialize Git in Website Folder (If Needed)

The website folder needs to be a Git repository. You have two options:

**Option A: If website folder is already a Git repo:**
- Skip this step, you're good!

**Option B: If website folder is NOT a Git repo:**
- You need to initialize it or clone there

**Quick way - Use cPanel Terminal or SSH:**
```bash
cd /home/masterco/cosmetics.master.com.bd
# OR
cd /home2/masterco/cosmetics.master.com.bd

# Initialize Git (if not already)
git init
git remote add origin https://github.com/Rabiulhasanemon/fashion.git
git pull origin main
```

**Or use cPanel Git Version Control:**
- Create repository pointing to: `/home/masterco/cosmetics.master.com.bd` (or `/home2/...`)
- Repository URL: `https://github.com/Rabiulhasanemon/fashion.git`

---

### Step 3: Set Up GitHub Webhook

1. **Go to GitHub:**
   - https://github.com/Rabiulhasanemon/fashion/settings/hooks

2. **Click "Add webhook"**

3. **Fill in:**
   - **Payload URL**: `https://cosmetics.master.com.bd/deploy.php`
   - **Content type**: `application/json`
   - **Secret**: (The same secret you set in deploy.php - e.g., `mySecret123xyz789`)
   - **Which events**: Select "Just the push event"
   - **Active**: ✓ Checked

4. **Click "Add webhook"**

---

### Step 4: Test It!

1. **Make a small change** to any file
2. **Commit and push:**
   ```bash
   git add .
   git commit -m "Test auto deploy"
   git push origin main
   ```
3. **Wait 10-30 seconds**
4. **Check your website** - it should be updated automatically!
5. **Check logs**: `/cosmetics.master.com.bd/deploy.log` to see what happened

---

## ✅ How It Works

1. You push code to GitHub
2. GitHub sends webhook to `deploy.php`
3. `deploy.php` runs `git pull` in your website folder
4. Website is automatically updated!
5. No more manual copying needed! 🎉

---

## 🔧 Troubleshooting

### Webhook not working?
- Check GitHub webhook delivery logs (Settings → Webhooks → Recent Deliveries)
- Check `deploy.log` on server
- Make sure `WEBHOOK_SECRET` matches in both places

### Git pull fails?
- Make sure website folder is a Git repository
- Check file permissions (folder should be writable)
- Check Git is installed on server

### Path issues?
- Verify the exact path in cPanel File Manager
- Update `DEPLOY_PATH` in `deploy.php` to match exactly

---

## 📝 Summary

**Before:** Push to GitHub → Manually copy files → Update website  
**After:** Push to GitHub → Website updates automatically! ✨

No more manual copying needed!

