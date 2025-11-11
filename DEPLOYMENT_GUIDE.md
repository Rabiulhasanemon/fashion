# cPanel Deployment Guide for Fashion E-commerce

## Overview
This guide will help you deploy your Fashion e-commerce site from GitHub to your cPanel hosting.

## Prerequisites
- cPanel access credentials
- FTP/SFTP credentials (or use cPanel File Manager)
- Database credentials from your hosting provider
- Your domain name

---

## Step 1: Get Your Hosting Information

### From cPanel Dashboard, you need:
1. **Domain URL**: `https://yourdomain.com` or `https://www.yourdomain.com`
2. **Database Details**:
   - Database Host: Usually `localhost` (or provided by hosting)
   - Database Name: `yourusername_dbname`
   - Database Username: `yourusername_dbuser`
   - Database Password: (from cPanel → MySQL Databases)
3. **File Path**: Usually `/home/yourusername/public_html/` or `/home/yourusername/public_html/fashion/`

---

## Step 2: Update Configuration Files for Production

### Update `config.php` (Root)
Replace these values with your hosting details:

```php
// HTTP
define('HTTP_SERVER', 'https://yourdomain.com/');
// HTTPS
define('HTTPS_SERVER', 'https://yourdomain.com/');

// DIR - Use absolute paths from cPanel
define('DIR_APPLICATION', '/home/yourusername/public_html/catalog/');
define('DIR_SYSTEM', '/home/yourusername/public_html/system/');
// ... (update all DIR paths)

// DB - Use your cPanel database credentials
define('DB_HOSTNAME', 'localhost'); // or provided by hosting
define('DB_USERNAME', 'your_db_username');
define('DB_PASSWORD', 'your_db_password');
define('DB_DATABASE', 'your_db_name');
```

### Update `admin/config.php`
Same updates as above, but for admin panel.

---

## Step 3: Deploy to cPanel

### Method 1: Using cPanel File Manager (Easiest)

1. **Login to cPanel**
   - Go to: `https://yourdomain.com:2083` or `https://cpanel.yourdomain.com`
   - Enter your cPanel username and password

2. **Upload Files via File Manager**
   - Navigate to **File Manager** in cPanel
   - Go to `public_html` folder (or your domain folder)
   - If you want the site in a subfolder, create `fashion` folder
   - Upload all files from your local project

3. **Or Use Git in cPanel** (Recommended)
   - In cPanel, go to **Git Version Control**
   - Click **Create**
   - Repository URL: `https://github.com/Rabiulhasanemon/fashion.git`
   - Repository Path: `/home/yourusername/public_html/fashion`
   - Click **Create**

### Method 2: Using FTP/SFTP

1. **Get FTP Credentials from cPanel**
   - Go to **FTP Accounts** in cPanel
   - Note your FTP host, username, and password

2. **Use FTP Client** (FileZilla, WinSCP, etc.)
   - Connect to your server
   - Upload all files to `public_html/fashion/` folder

### Method 3: Using SSH (If Available)

```bash
# SSH into your server
ssh yourusername@yourdomain.com

# Navigate to public_html
cd ~/public_html

# Clone your repository
git clone https://github.com/Rabiulhasanemon/fashion.git

# Or if folder exists, pull updates
cd fashion
git pull origin main
```

---

## Step 4: Set File Permissions

In cPanel File Manager:
- Set folders to **755**
- Set files to **644**
- Set `config.php` and `admin/config.php` to **644** (readable but secure)

---

## Step 5: Create Database in cPanel

1. Go to **MySQL Databases** in cPanel
2. Create a new database (e.g., `yourusername_fashion`)
3. Create a database user
4. Add user to database with **ALL PRIVILEGES**
5. Import your local database:
   - Go to **phpMyAdmin** in cPanel
   - Select your database
   - Click **Import**
   - Upload your `.sql` file from local database

---

## Step 6: Update Configuration Files on Server

**IMPORTANT**: After uploading files, edit `config.php` and `admin/config.php` directly in cPanel File Manager with your production values.

---

## Step 7: Test Your Site

1. Visit: `https://yourdomain.com/fashion/`
2. Visit admin: `https://yourdomain.com/fashion/admin/`
3. Check for any errors
4. Test database connection

---

## Quick Update Process (For Future Updates)

### Option 1: Using Git in cPanel
1. Go to **Git Version Control** in cPanel
2. Click on your repository
3. Click **Pull or Deploy**
4. Select branch: `main`
5. Click **Update from Remote**

### Option 2: Manual Upload
1. Make changes locally
2. Commit and push to GitHub:
   ```bash
   git add .
   git commit -m "Update description"
   git push origin main
   ```
3. Upload changed files via FTP or File Manager

---

## Important Notes

⚠️ **Security Checklist:**
- Never commit `config.php` with production credentials to GitHub
- Use `.gitignore` to exclude config files
- Keep database credentials secure
- Enable SSL/HTTPS in cPanel

⚠️ **Before Going Live:**
- Update all URLs from `localhost` to your domain
- Update database credentials
- Test all functionality
- Clear cache: Delete files in `system/cache/`
- Check error logs in `system/logs/`

---

## Troubleshooting

### Common Issues:

1. **500 Internal Server Error**
   - Check file permissions
   - Check `.htaccess` file exists
   - Check error logs in cPanel

2. **Database Connection Error**
   - Verify database credentials
   - Check database host (might not be `localhost`)
   - Ensure database user has proper permissions

3. **File Not Found Errors**
   - Verify all DIR paths in config.php
   - Check file permissions
   - Ensure files uploaded completely

4. **Permission Denied**
   - Set correct file/folder permissions (755 for folders, 644 for files)

---

## Need Help?

- Check cPanel documentation
- Contact your hosting provider support
- Review error logs in `system/logs/error.log`

