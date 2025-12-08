# 🔐 Fix cPanel 401 Unauthorized Error - Complete Solution

## ❌ Error Message
```
Error: The API request failed with the following error: 401 - Unauthorized. 
Your session may have expired or you logged out of the system.
```

## ✅ Solution Steps

### **Step 1: Re-Login to cPanel**

1. **Clear Browser Cache & Cookies**
   - Press `Ctrl + Shift + Delete` (Windows) or `Cmd + Shift + Delete` (Mac)
   - Clear cookies and cached files
   - Close all browser tabs with cPanel

2. **Login Again**
   - Go to: `https://bmsrv5.limda.net:2083`
   - Or: `https://cosmetics.master.com.bd:2083`
   - Enter your **cPanel username** and **password**
   - Make sure you're using the correct credentials

3. **Check Session Timeout**
   - cPanel sessions expire after 15-30 minutes of inactivity
   - If you were idle, just login again

---

### **Step 2: Use Alternative Deployment Methods**

Since cPanel Git might have authentication issues, use these methods:

#### **Method A: Manual Pull via SSH (Recommended)**

1. **Access SSH Terminal in cPanel**
   - Go to **Terminal** or **SSH Access** in cPanel
   - Or use an SSH client (PuTTY, WinSCP, etc.)

2. **Navigate to Your Website Directory**
   ```bash
   cd /home/masterco/cosmetics.master.com.bd
   # OR (check which path exists)
   cd /home2/masterco/cosmetics.master.com.bd
   ```

3. **Pull Latest Code**
   ```bash
   git pull origin main
   ```

4. **If Git Not Initialized, Clone First**
   ```bash
   git init
   git remote add origin https://github.com/Rabiulhasanemon/fashion.git
   git pull origin main
   ```

---

#### **Method B: Use GitHub Personal Access Token**

If cPanel Git requires authentication:

1. **Create GitHub Personal Access Token**
   - Go to: https://github.com/settings/tokens
   - Click **Generate new token (classic)**
   - Name: `cPanel Deployment`
   - Select scopes: `repo` (full control)
   - Click **Generate token**
   - **COPY THE TOKEN** (you won't see it again!)

2. **Use Token in cPanel Git**
   - In cPanel Git Version Control
   - When creating/updating repository
   - Use this URL format:
   ```
   https://YOUR_TOKEN@github.com/Rabiulhasanemon/fashion.git
   ```
   - Replace `YOUR_TOKEN` with your actual token

3. **Or Update Existing Repository**
   - Click on your repository in cPanel
   - Click **Manage** or **Edit**
   - Update the Repository URL with token
   - Save and try **Pull or Deploy** again

---

#### **Method C: Manual File Upload (If Git Fails)**

1. **Download from GitHub**
   - Go to: https://github.com/Rabiulhasanemon/fashion
   - Click **Code** → **Download ZIP**
   - Extract the ZIP file

2. **Upload via cPanel File Manager**
   - Login to cPanel
   - Go to **File Manager**
   - Navigate to: `/home/masterco/cosmetics.master.com.bd/`
   - Upload the updated file:
     - `catalog/view/theme/ranger_fashion/template/module/featured_category.tpl`

3. **Or Use FTP**
   - Use FileZilla or WinSCP
   - Connect to your server
   - Upload the file to the correct location

---

#### **Method D: Use Auto-Deploy Script (Best for Future)**

1. **Upload `deploy.php` to Server**
   - Path: `/home/masterco/cosmetics.master.com.bd/deploy.php`
   - Via File Manager or FTP

2. **Set Up GitHub Webhook**
   - Go to: https://github.com/Rabiulhasanemon/fashion/settings/hooks
   - Click **Add webhook**
   - Payload URL: `https://cosmetics.master.com.bd/deploy.php`
   - Content type: `application/json`
   - Secret: (set a secret in deploy.php)
   - Events: **Just the push event**
   - Click **Add webhook**

3. **Future Updates**
   - Just push to GitHub
   - Webhook automatically deploys!

---

### **Step 3: Check cPanel Git Permissions**

1. **Verify Git is Enabled**
   - Contact your hosting provider
   - Ask if Git Version Control is enabled for your account

2. **Check File Permissions**
   - In File Manager, check:
     - `.git` folder exists (might be hidden)
     - Permissions are correct (755 for folders, 644 for files)

3. **Try Different Browser**
   - Sometimes browser extensions cause issues
   - Try Chrome, Firefox, or Edge in incognito mode

---

### **Step 4: Contact Hosting Support**

If nothing works:

1. **Contact Your Hosting Provider**
   - Ask them to:
     - Verify Git Version Control is enabled
     - Check if there are any restrictions
     - Reset your cPanel session
     - Provide SSH access if needed

2. **Provide This Information**
   - Your domain: `cosmetics.master.com.bd`
   - Error: `401 Unauthorized in Git Version Control`
   - Repository: `https://github.com/Rabiulhasanemon/fashion.git`

---

## 🚀 Quick Fix (Recommended Right Now)

**Use SSH Terminal in cPanel:**

1. Login to cPanel
2. Find **Terminal** or **SSH Access**
3. Run these commands:
   ```bash
   cd /home/masterco/cosmetics.master.com.bd
   git pull origin main
   ```

If that doesn't work, use **Method C (Manual Upload)** to update just the one file.

---

## 📝 File to Update

The file that needs to be updated on your server:
```
catalog/view/theme/ranger_fashion/template/module/featured_category.tpl
```

You can download it from GitHub and upload it manually via File Manager.

---

## ✅ Success Checklist

- [ ] Re-logged into cPanel
- [ ] Tried SSH method
- [ ] Or uploaded file manually
- [ ] Verified file is updated on server
- [ ] Tested website to confirm changes

---

## 🔄 For Future Updates

Set up the auto-deploy webhook (Method D) so you never have to deal with cPanel Git again!

