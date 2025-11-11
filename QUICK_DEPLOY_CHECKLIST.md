# Quick Deployment Checklist

## Before You Start
- [ ] Have cPanel login credentials ready
- [ ] Have database credentials from cPanel
- [ ] Know your domain name
- [ ] Have FTP credentials (if using FTP method)

## Step-by-Step Deployment

### 1. Get Information from cPanel
- [ ] Login to cPanel: `https://yourdomain.com:2083`
- [ ] Note your file path (usually `/home/username/public_html/`)
- [ ] Create database in **MySQL Databases**
- [ ] Note database name, username, and password
- [ ] Import your local database via **phpMyAdmin**

### 2. Upload Files
Choose ONE method:

**Option A: Git in cPanel (Recommended)**
- [ ] Go to **Git Version Control** in cPanel
- [ ] Click **Create**
- [ ] Repository: `https://github.com/Rabiulhasanemon/fashion.git`
- [ ] Path: `/home/yourusername/public_html/fashion`
- [ ] Click **Create**

**Option B: File Manager**
- [ ] Go to **File Manager** in cPanel
- [ ] Navigate to `public_html`
- [ ] Upload all project files

**Option C: FTP**
- [ ] Use FTP client (FileZilla, WinSCP)
- [ ] Connect with FTP credentials
- [ ] Upload all files to `public_html/fashion/`

### 3. Update Configuration
- [ ] Edit `config.php` in File Manager
- [ ] Update `HTTP_SERVER` and `HTTPS_SERVER` with your domain
- [ ] Update all `DIR_*` paths with cPanel file paths
- [ ] Update database credentials (DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE)
- [ ] Edit `admin/config.php` with same updates

### 4. Set Permissions
- [ ] Folders: **755**
- [ ] Files: **644**
- [ ] Config files: **644**

### 5. Test
- [ ] Visit: `https://yourdomain.com/fashion/`
- [ ] Visit admin: `https://yourdomain.com/fashion/admin/`
- [ ] Check for errors
- [ ] Test login functionality

### 6. Security
- [ ] Enable SSL/HTTPS in cPanel
- [ ] Verify `.gitignore` excludes config files
- [ ] Change default passwords
- [ ] Clear cache: Delete `system/cache/*` files

## For Future Updates

### Using Git in cPanel:
1. Go to **Git Version Control**
2. Click on your repository
3. Click **Pull or Deploy**
4. Select branch: `main`
5. Click **Update from Remote**

### Manual Update:
1. Make changes locally
2. Push to GitHub: `git push origin main`
3. Upload changed files via FTP/File Manager

## Common Paths in cPanel

- **File Path**: `/home/yourusername/public_html/`
- **Database Host**: Usually `localhost` (check with hosting)
- **cPanel URL**: `https://yourdomain.com:2083` or `https://cpanel.yourdomain.com`
- **phpMyAdmin**: Found in cPanel → **phpMyAdmin**

## Need Help?

- Check `DEPLOYMENT_GUIDE.md` for detailed instructions
- Contact your hosting provider
- Check error logs in `system/logs/error.log`

