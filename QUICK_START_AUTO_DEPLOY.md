# Quick Start: Automatic Deployment

## 🚀 Fast Setup (5 Minutes)

### Step 1: Upload Files to Server
Upload these files to your cPanel hosting:
- `deploy.php` → `/home/masterco/cosmetics.master.com.bd/deploy.php`
- `deploy.sh` → `/home/masterco/cosmetics.master.com.bd/deploy.sh` (optional, for cron)

### Step 2: Change Secret Token
1. Open `deploy.php` on your server
2. Find this line:
   ```php
   define('WEBHOOK_SECRET', 'your-secret-token-change-this-12345');
   ```
3. Change it to a random string (e.g., `abc123xyz789secret456`)

### Step 3: Set Up GitHub Webhook
1. Go to: https://github.com/Rabiulhasanemon/fashion/settings/hooks
2. Click **Add webhook**
3. Fill in:
   - **Payload URL**: `https://cosmetics.master.com.bd/deploy.php`
   - **Content type**: `application/json`
   - **Secret**: (The same secret you set in deploy.php)
   - **Events**: Select "Just the push event"
4. Click **Add webhook**

### Step 4: Test It!
1. Make a small change (add a comment to any file)
2. Commit and push:
   ```bash
   git add .
   git commit -m "Test auto deploy"
   git push origin main
   ```
3. Wait 10-30 seconds
4. Check your site - it should be updated automatically!

---

## ✅ That's It!

Now every time you push to GitHub, your cPanel site will automatically update!

---

## 📋 Check Deployment Status

View logs on your server:
- File: `/home/masterco/cosmetics.master.com.bd/deploy.log`
- Or check in cPanel File Manager

---

## 🔧 Alternative: Cron Job Method

If webhook doesn't work, use cron job:

1. In cPanel, go to **Cron Jobs**
2. Add:
   - **Command**: `/bin/bash /home/masterco/cosmetics.master.com.bd/deploy.sh`
   - **Schedule**: `*/15 * * * *` (every 15 minutes)
3. Save

---

## ❓ Troubleshooting

**Webhook not working?**
- Check GitHub webhook delivery logs (Settings → Webhooks → Recent Deliveries)
- Check `deploy.log` on server
- Make sure `WEBHOOK_SECRET` matches in both places

**Need more help?**
- Read `AUTO_DEPLOY_SETUP.md` for detailed instructions

