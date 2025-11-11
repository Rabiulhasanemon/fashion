# 📍 Where to Enter "/home/masterco/cosmetics.master.com.bd" in cPanel

## Step-by-Step Guide

### Step 1: Login to cPanel
1. Go to: `https://cosmetics.master.com.bd:2083`
   - Or: `https://cpanel.cosmetics.master.com.bd`
   - Or: Your hosting provider's cPanel URL
2. Enter your cPanel username and password

### Step 2: Find Git Version Control
1. In cPanel dashboard, look for **"Git Version Control"** icon
2. If you don't see it, use the **Search** bar at the top
3. Type: `git` and click on **Git Version Control**

### Step 3: Create New Repository
1. Click the **"Create"** button (usually green button at top)
2. You'll see a form with these fields:

---

## 📝 Here's Where to Enter the Path:

### Field 1: **Repository URL**
```
https://github.com/Rabiulhasanemon/fashion.git
```
- This is your GitHub repository URL
- Copy from: https://github.com/Rabiulhasanemon/fashion

### Field 2: **Repository Path** ⬅️ **ENTER PATH HERE!**
```
/home/masterco/cosmetics.master.com.bd
```
- **This is where you enter:** `/home/masterco/cosmetics.master.com.bd`
- This is the absolute path to your website folder on the server
- This tells cPanel where to clone/put your files

### Field 3: **Repository Name** (Optional)
```
fashion
```
- You can name it anything, like "fashion" or "my-site"
- This is just a label in cPanel

### Field 4: **Branch** (Optional)
```
main
```
- Your GitHub branch name (usually `main` or `master`)

---

## 🖼️ What the Form Looks Like:

```
┌─────────────────────────────────────────────────┐
│  Create Git Repository                          │
├─────────────────────────────────────────────────┤
│                                                 │
│  Repository URL:                                │
│  [https://github.com/Rabiulhasanemon/fashion.git]│
│                                                 │
│  Repository Path:                               │
│  [/home/masterco/cosmetics.master.com.bd]      │  ← ENTER HERE!
│                                                 │
│  Repository Name: (optional)                   │
│  [fashion]                                      │
│                                                 │
│  Branch: (optional)                            │
│  [main]                                         │
│                                                 │
│  [Cancel]  [Create]                             │
└─────────────────────────────────────────────────┘
```

---

## ✅ After Clicking "Create"

1. cPanel will clone your GitHub repository to that path
2. All your files will be in: `/home/masterco/cosmetics.master.com.bd/`
3. You can then use **"Pull or Deploy"** to update files

---

## 🔍 How to Find Your Exact Path

If you're not sure about the path, check in cPanel File Manager:

1. Go to **File Manager** in cPanel
2. Navigate to your website's root folder
3. Look at the **path shown at the top** of File Manager
4. It will show something like: `/home/masterco/cosmetics.master.com.bd`
5. Copy that exact path

---

## 📋 Quick Reference

**Repository URL:**
```
https://github.com/Rabiulhasanemon/fashion.git
```

**Repository Path:**
```
/home/masterco/cosmetics.master.com.bd
```

**Branch:**
```
main
```

---

## 🆘 If You Don't See Git Version Control

Some hosting providers don't have Git Version Control. Alternatives:

1. **Use SSH** (if available):
   ```bash
   cd /home/masterco/cosmetics.master.com.bd
   git clone https://github.com/Rabiulhasanemon/fashion.git .
   ```

2. **Use Auto-Deploy** (`deploy.php`):
   - Upload `deploy.php` to your server
   - Set up GitHub webhook
   - It will auto-update when you push

3. **Manual Upload**:
   - Download ZIP from GitHub
   - Upload via File Manager or FTP

---

## ✅ After Setup

Once the repository is created:
1. Click on your repository name
2. Click **"Pull or Deploy"**
3. Select branch: `main`
4. Click **"Update from Remote"**
5. Your site is updated!

