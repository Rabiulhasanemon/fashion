# Automatic Deployment Setup Guide

This guide will help you set up automatic deployment from GitHub to your cPanel hosting.

## Method 1: GitHub Webhook (Recommended - Automatic)

This method automatically deploys when you push code to GitHub.

### Step 1: Upload deploy.php to Your Server

1. Upload `deploy.php` to your server root:
   - Path: `/home/masterco/cosmetics.master.com.bd/deploy.php`

2. **IMPORTANT**: Edit `deploy.php` and change the `WEBHOOK_SECRET`:
   ```php
   define('WEBHOOK_SECRET', 'your-secret-token-change-this-12345');
   ```
   Generate a random string (you can use: https://randomkeygen.com/)

### Step 2: Set File Permissions

In cPanel File Manager:
- Set `deploy.php` permissions to **644**
- Make sure the file is executable (or set to **755**)

### Step 3: Configure GitHub Webhook

1. Go to your GitHub repository: `https://github.com/Rabiulhasanemon/fashion`
2. Click **Settings** → **Webhooks** → **Add webhook**
3. Configure the webhook:
   - **Payload URL**: `https://cosmetics.master.com.bd/deploy.php`
   - **Content type**: `application/json`
   - **Secret**: (The same secret you set in deploy.php)
   - **Which events**: Select "Just the push event"
   - **Active**: ✓ Checked
4. Click **Add webhook**

### Step 4: Test the Webhook

1. Make a small change to your code
2. Commit and push to GitHub:
   ```bash
   git add .
   git commit -m "Test deployment"
   git push origin main
   ```
3. Check the webhook delivery in GitHub (Settings → Webhooks → Recent Deliveries)
4. Check `deploy.log` on your server to see if deployment worked

### Step 5: Secure the Webhook (Optional but Recommended)

1. **Restrict by IP**: Uncomment the IP restriction section in `deploy.php` and add GitHub IP ranges
2. **Use HTTPS**: Make sure your site uses SSL (which you already have)
3. **Change Secret**: Use a strong, random secret token

---

## Method 2: Cron Job (Scheduled Automatic Pull)

This method checks for updates every X minutes and pulls if there are changes.

### Step 1: Upload deploy.sh to Your Server

1. Upload `deploy.sh` to your server:
   - Path: `/home/masterco/cosmetics.master.com.bd/deploy.sh`

2. Set permissions:
   ```bash
   chmod +x /home/masterco/cosmetics.master.com.bd/deploy.sh
   ```

### Step 2: Set Up Cron Job in cPanel

1. Login to cPanel
2. Go to **Cron Jobs**
3. Add a new cron job:
   - **Minute**: `*/5` (every 5 minutes) or `*/15` (every 15 minutes)
   - **Hour**: `*`
   - **Day**: `*`
   - **Month**: `*`
   - **Weekday**: `*`
   - **Command**: 
     ```bash
     /bin/bash /home/masterco/cosmetics.master.com.bd/deploy.sh
     ```
4. Click **Add New Cron Job**

### Step 3: Test the Cron Job

1. Make a change and push to GitHub
2. Wait for the cron job to run (5-15 minutes depending on your setting)
3. Check `deploy.log` to see if it pulled the changes

---

## Method 3: Manual Deployment Script

You can also run the deployment script manually via SSH or cPanel Terminal:

```bash
cd /home/masterco/cosmetics.master.com.bd
bash deploy.sh
```

Or via PHP:
```bash
php deploy.php
```

---

## Troubleshooting

### Webhook Not Working?

1. **Check webhook delivery in GitHub**:
   - Go to Settings → Webhooks → Recent Deliveries
   - Check if the request was successful (200 status)
   - View the response to see any errors

2. **Check file permissions**:
   - `deploy.php` should be readable (644)
   - Project directory should be writable

3. **Check PHP error logs**:
   - Location: `/home/masterco/cosmetics.master.com.bd/system/logs/error.log`
   - Or check cPanel error logs

4. **Test manually**:
   - Visit: `https://cosmetics.master.com.bd/deploy.php` in browser
   - Should show "Method not allowed" (this is correct for GET requests)
   - Or use curl:
     ```bash
     curl -X POST https://cosmetics.master.com.bd/deploy.php
     ```

### Git Pull Fails?

1. **Check Git is installed on server**:
   ```bash
   which git
   git --version
   ```

2. **Check Git credentials**:
   - Make sure the repository is accessible
   - You may need to set up SSH keys or use HTTPS with credentials

3. **Check file permissions**:
   - Make sure the web server user can write to the directory

### Permission Denied Errors?

1. Set proper permissions:
   ```bash
   chmod 755 /home/masterco/cosmetics.master.com.bd
   chmod 644 /home/masterco/cosmetics.master.com.bd/deploy.php
   ```

2. Check ownership:
   ```bash
   ls -la /home/masterco/cosmetics.master.com.bd
   ```

---

## Security Best Practices

1. ✅ **Change WEBHOOK_SECRET** to a strong random string
2. ✅ **Use HTTPS** (you already have this)
3. ✅ **Restrict webhook by IP** (optional but recommended)
4. ✅ **Don't commit sensitive files** (config.php is in .gitignore)
5. ✅ **Monitor deploy.log** for unauthorized access attempts
6. ✅ **Keep deploy.php outside public access** (or restrict access via .htaccess)

### Optional: Restrict deploy.php Access

Add to `.htaccess`:
```apache
<Files "deploy.php">
    Order Deny,Allow
    Deny from all
    Allow from 192.30.252.0/22
    Allow from 185.199.108.0/22
    Allow from 140.82.112.0/20
</Files>
```

---

## How It Works

1. **GitHub Webhook Method**:
   - You push code to GitHub
   - GitHub sends a POST request to `deploy.php`
   - `deploy.php` verifies the request
   - Script runs `git pull` to update files
   - Cache is cleared
   - Logs are written

2. **Cron Job Method**:
   - Cron runs `deploy.sh` every X minutes
   - Script checks for updates
   - If updates exist, pulls them
   - Cache is cleared
   - Logs are written

---

## Monitoring

Check deployment logs:
```bash
tail -f /home/masterco/cosmetics.master.com.bd/deploy.log
```

Or view in cPanel File Manager.

---

## Need Help?

- Check `deploy.log` for detailed error messages
- Check GitHub webhook delivery logs
- Check cPanel error logs
- Contact your hosting provider if Git is not available

