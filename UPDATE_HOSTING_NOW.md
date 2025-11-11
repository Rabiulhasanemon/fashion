# 🚀 Update Your Hosting Right Now

## ✅ Step 1: Code is Already on GitHub!
Your code has been pushed to: `https://github.com/Rabiulhasanemon/fashion`

## 📥 Step 2: Update on Your Hosting

### Method A: cPanel Git Version Control (Easiest)

1. **Login to cPanel**
   - URL: `https://cosmetics.master.com.bd:2083` or your cPanel URL

2. **Go to Git Version Control**
   - Find it in the cPanel dashboard
   - Or search for "Git" in the search bar

3. **If Repository Doesn't Exist Yet:**
   - Click **Create**
   - Repository URL: `https://github.com/Rabiulhasanemon/fashion.git`
   - Repository Path: `/home/masterco/cosmetics.master.com.bd`
   - Branch: `main`
   - Click **Create**

4. **If Repository Already Exists:**
   - Click on your repository
   - Click **Pull or Deploy**
   - Select branch: `main`
   - Click **Update from Remote**

5. **Done!** Your site is updated!

---

### Method B: Manual Upload (If Git Not Available)

1. **Download from GitHub:**
   - Go to: https://github.com/Rabiulhasanemon/fashion
   - Click **Code** → **Download ZIP**
   - Extract the ZIP file

2. **Upload to cPanel:**
   - Login to cPanel
   - Go to **File Manager**
   - Navigate to `/home/masterco/cosmetics.master.com.bd/`
   - Upload the new/changed files:
     - `deploy.php`
     - `deploy.sh`
     - `.gitignore`
     - `.htaccess` (updated)
     - All `.md` guide files
     - `config.production.php.example`
     - `admin/config.production.php.example`

3. **Important:** Don't overwrite `config.php` and `admin/config.php` on server!
   - These have your production database credentials
   - Only update them manually if needed

---

### Method C: Set Up Auto-Deploy (For Future)

After this update, set up auto-deploy so future updates happen automatically:

1. **Upload `deploy.php` to server** (if not already there)
   - Path: `/home/masterco/cosmetics.master.com.bd/deploy.php`

2. **Edit `deploy.php`** and change the secret:
   ```php
   define('WEBHOOK_SECRET', 'your-random-secret-here');
   ```

3. **Add GitHub Webhook:**
   - Go to: https://github.com/Rabiulhasanemon/fashion/settings/hooks
   - Click **Add webhook**
   - Payload URL: `https://cosmetics.master.com.bd/deploy.php`
   - Secret: (same as in deploy.php)
   - Events: "Just the push event"
   - Click **Add webhook**

4. **Test it:**
   - Make a small change
   - Push to GitHub
   - Site updates automatically!

---

## ⚠️ Important Reminders

1. **Config Files:** 
   - `config.php` and `admin/config.php` on server have production credentials
   - Don't overwrite them with local versions
   - They're already in `.gitignore` so they won't be pushed

2. **File Permissions:**
   - After upload, set permissions:
     - Folders: **755**
     - Files: **644**

3. **Test After Update:**
   - Visit: `https://cosmetics.master.com.bd/`
   - Check admin: `https://cosmetics.master.com.bd/admin/`
   - Make sure everything works

---

## 📋 Quick Checklist

- [ ] Code pushed to GitHub ✅ (Already done!)
- [ ] Updated on hosting via cPanel Git or manual upload
- [ ] Tested website - everything works
- [ ] (Optional) Set up auto-deploy for future updates

---

## 🆘 Need Help?

- Check `HOW_TO_PUSH_UPDATES.md` for detailed instructions
- Check `AUTO_DEPLOY_SETUP.md` for auto-deploy setup
- Check deployment logs: `/home/masterco/cosmetics.master.com.bd/deploy.log`

