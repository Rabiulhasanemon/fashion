# 🚀 Quick File Upload Guide - Featured Category Template

## ⚡ Fastest Way to Update Just This One File

### **Step 1: Download the File from GitHub**

1. Go to: https://github.com/Rabiulhasanemon/fashion/blob/main/catalog/view/theme/ranger_fashion/template/module/featured_category.tpl
2. Click the **Raw** button (top right of the file)
3. Right-click on the page → **Save As**
4. Save as: `featured_category.tpl`

---

### **Step 2: Upload to cPanel**

#### **Option A: Using cPanel File Manager**

1. **Login to cPanel**
   - URL: `https://bmsrv5.limda.net:2083` or your cPanel URL
   - Login with your credentials

2. **Open File Manager**
   - Find **File Manager** icon in cPanel
   - Or search for "File Manager" in the search bar

3. **Navigate to the File Location**
   - Go to: `public_html` or your domain folder
   - Navigate to: `catalog/view/theme/ranger_fashion/template/module/`
   - Or full path: `/home/masterco/cosmetics.master.com.bd/catalog/view/theme/ranger_fashion/template/module/`

4. **Upload the File**
   - Click **Upload** button (top menu)
   - Select the `featured_category.tpl` file you downloaded
   - Click **Upload**
   - **Overwrite** the existing file when prompted

5. **Done!** ✅

---

#### **Option B: Using FTP (FileZilla, WinSCP, etc.)**

1. **Get FTP Credentials**
   - In cPanel, go to **FTP Accounts**
   - Note your FTP host, username, and password

2. **Connect via FTP Client**
   - Host: Your domain or server IP
   - Username: Your FTP username
   - Password: Your FTP password
   - Port: 21 (or 22 for SFTP)

3. **Navigate to Folder**
   - Go to: `catalog/view/theme/ranger_fashion/template/module/`

4. **Upload File**
   - Drag and drop `featured_category.tpl`
   - Or right-click → Upload
   - Overwrite existing file

5. **Done!** ✅

---

### **Step 3: Verify the Update**

1. **Check File Date**
   - In File Manager, check the file's "Last Modified" date
   - Should be today's date/time

2. **Test Your Website**
   - Visit your website
   - Go to a page with featured categories
   - Check if the new style is showing

---

## 📋 File Details

**File Path:**
```
catalog/view/theme/ranger_fashion/template/module/featured_category.tpl
```

**Full Server Path:**
```
/home/masterco/cosmetics.master.com.bd/catalog/view/theme/ranger_fashion/template/module/featured_category.tpl
```

**What Changed:**
- Updated to "Shop By Category" style
- Vertical cards with image on top
- Responsive grid layout
- New class names (shop-cat-*) to avoid conflicts

---

## ⚠️ Important Notes

1. **Backup First** (Optional but Recommended)
   - In File Manager, right-click the old file
   - Click **Copy**
   - Rename to `featured_category.tpl.backup`
   - Then upload the new file

2. **File Permissions**
   - After uploading, set permissions to **644**
   - Right-click file → **Change Permissions** → `644`

3. **Clear Cache** (If Needed)
   - Clear browser cache
   - Clear any CMS cache if you have caching enabled

---

## ✅ Success!

Once uploaded, your featured categories will show the new "Shop By Category" style with:
- Vertical card layout
- Image on top, category name below
- Responsive grid (2-8 columns based on screen size)
- Orange underline on title
- Modern, clean design

