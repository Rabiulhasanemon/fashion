# How to Push Code Updates to Your Hosting

## 🚀 Method 1: Automatic (Recommended - After Setup)

Once you set up the auto-deploy webhook, just push to GitHub and it updates automatically!

### Steps:
1. **Make your code changes** locally
2. **Commit changes**:
   ```bash
   git add .
   git commit -m "Your update description"
   ```
3. **Push to GitHub**:
   ```bash
   git push origin main
   ```
4. **Done!** Your hosting will update automatically within seconds

---

## 📤 Method 2: Manual Push (Right Now)

### Step 1: Commit Your Changes

**IMPORTANT**: Don't commit `config.php` and `admin/config.php` with production credentials!

```bash
# Add files (excluding sensitive config files)
git add .gitignore
git add deploy.php
git add deploy.sh
git add .htaccess
git add AUTO_DEPLOY_SETUP.md
git add DEPLOYMENT_GUIDE.md
git add QUICK_START_AUTO_DEPLOY.md
git add QUICK_DEPLOY_CHECKLIST.md
git add config.production.php.example
git add admin/config.production.php.example
git add .htaccess.deploy-security

# Commit
git commit -m "Add auto-deployment system and update production configs"
```

### Step 2: Push to GitHub

```bash
git push origin main
```

### Step 3: Update on Hosting

**Option A: Use Auto-Deploy (if set up)**
- If you've set up the webhook, it will update automatically!

**Option B: Manual Update via cPanel**
1. Login to cPanel
2. Go to **Git Version Control**
3. Find your repository
4. Click **Pull or Deploy**
5. Select branch: `main`
6. Click **Update from Remote**

**Option C: Manual Update via SSH**
```bash
cd /home/masterco/cosmetics.master.com.bd
git pull origin main
```

**Option D: Manual Upload**
1. Download changed files from GitHub
2. Upload via cPanel File Manager or FTP
3. Replace files on server

---

## ⚠️ Important Notes

### Never Commit These Files:
- `config.php` (contains production database password)
- `admin/config.php` (contains production database password)
- `deploy.log` (deployment logs)

These are already in `.gitignore` so they won't be committed accidentally.

### Always Update Config Files on Server Separately:
After pushing code, manually update `config.php` and `admin/config.php` on your server with production credentials (if they changed).

---

## 📋 Quick Command Reference

```bash
# Check what files changed
git status

# See what will be committed
git diff

# Add all files (respects .gitignore)
git add .

# Commit changes
git commit -m "Description of changes"

# Push to GitHub
git push origin main

# Check if push was successful
git status
```

---

## 🔄 Complete Workflow Example

```bash
# 1. Make your code changes
# (edit files in your editor)

# 2. Check what changed
git status

# 3. Add changes
git add .

# 4. Commit
git commit -m "Fix product display issue"

# 5. Push to GitHub
git push origin main

# 6. If auto-deploy is set up, wait 10-30 seconds
# 7. Check your website - it should be updated!
```

---

## 🆘 Troubleshooting

### "Permission denied" when pushing?
- Make sure you're authenticated with GitHub
- Check: `git remote -v` to see your repository URL

### "Config files not updating on server?"
- Config files are in `.gitignore` - they won't be pushed
- Manually update `config.php` on server if needed

### "Auto-deploy not working?"
- Check `deploy.log` on server
- Check GitHub webhook delivery status
- See `AUTO_DEPLOY_SETUP.md` for troubleshooting

---

## 💡 Pro Tips

1. **Always test locally first** before pushing
2. **Write clear commit messages** - describe what changed
3. **Push frequently** - don't wait for many changes
4. **Keep config files separate** - never commit production passwords
5. **Check deployment logs** after pushing

