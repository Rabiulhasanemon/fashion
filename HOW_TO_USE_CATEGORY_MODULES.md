# 📦 How to Use Category Modules System - Complete Guide

## 🎯 Overview
The Category Modules System allows you to dynamically assign and display any installed module (like Product Showcase Tabs, Flash Deals, Featured Products, etc.) on specific category pages. You can configure each module with custom settings, control display order, and enable/disable them individually.

---

## 📋 Step 1: Install Database Table

### Option A: Using phpMyAdmin (Recommended)
1. Log in to your cPanel
2. Open **phpMyAdmin**
3. Select your database (usually `masterco_rup` based on your config)
4. Click on the **SQL** tab
5. Copy and paste the entire content from `install_category_modules.sql`
6. **IMPORTANT:** Replace `sr_` with your actual database prefix
   - Check your `config.php` file: look for `DB_PREFIX`
   - If it's different from `sr_`, replace all `sr_` in the SQL with your prefix
7. Click **Go** to execute

### Option B: Using MySQL Command Line
```bash
mysql -u masterco_rup -p masterco_rup < install_category_modules.sql
```

### Verify Installation
After running the SQL, verify the table was created:
```sql
SHOW TABLES LIKE 'sr_category_module';
```
(Replace `sr_` with your prefix)

---

## 🚀 Step 2: Using the Module System in Admin Panel

### Access the Feature
1. **Log in to Admin Panel**
   - Go to: `http://ruplexa1.master.com.bd/admin/`
   - Enter your admin credentials

2. **Navigate to Categories**
   - Click: **Catalog** → **Categories**
   - Find the category you want to add modules to
   - Click the **Edit** button (pencil icon)

3. **Open Modules Tab**
   - You'll see tabs: General, Data, Design, **Modules**
   - Click on the **Modules** tab

### Adding a Module

1. **Click "Add Module" Button**
   - Look for the green **+** (plus) button at the bottom of the modules table
   - Click it to add a new module row

2. **Select Module**
   - In the **Module** dropdown, select the module you want to use
   - Available modules include:
     - `product_showcase_tabs` - Product Showcase with Tabs
     - `flash_deal` - Flash Deal Module
     - `bestseller` - Best Seller Products
     - `featured` - Featured Products
     - `latest` - Latest Products
     - `special` - Special Offers
     - `popular` - Popular Products
     - `tabbed_category` - Tabbed Category Products
     - And any other installed modules

3. **Configure Settings (JSON Format)**
   - In the **Settings** textarea, enter module configuration in JSON format
   - Example formats below

4. **Set Sort Order**
   - Enter a number (lower = appears first)
   - Example: `0` = first, `10` = second, `20` = third

5. **Set Status**
   - Choose **Enabled** to show the module
   - Choose **Disabled** to hide it (but keep the configuration)

6. **Save Category**
   - Click the **Save** button (floppy disk icon) at the top right
   - Your modules are now assigned to this category!

### Removing a Module
- Click the red **-** (minus) button next to the module row
- The module will be removed when you save the category

---

## ⚙️ Module Settings Examples

### Product Showcase Tabs
```json
{
  "name": "Beauty Products",
  "limit": 12,
  "width": 500,
  "height": 500
}
```

### Flash Deal
```json
{
  "name": "Special Offers",
  "limit": 6
}
```

### Best Seller
```json
{
  "name": "Best Sellers",
  "limit": 8,
  "width": 200,
  "height": 200
}
```

### Featured Products
```json
{
  "name": "Featured",
  "limit": 10
}
```

### Latest Products
```json
{
  "name": "New Arrivals",
  "limit": 8
}
```

### Popular Products
```json
{
  "name": "Popular Items",
  "limit": 12
}
```

### Tabbed Category
```json
{
  "name": "Category Products",
  "limit": 8,
  "tabs": [
    {
      "category_id": 20,
      "title": "Beauty Products"
    },
    {
      "category_id": 19,
      "title": "Makeup Products"
    }
  ]
}
```

---

## 🎨 Frontend Display

### Where Modules Appear
Modules assigned to a category will automatically appear on the category page:
- **Location:** After the product listing and content bottom section
- **Order:** Sorted by Sort Order (ascending)
- **Display:** Only enabled modules are shown

### Example Category Page Structure:
```
[Header]
[Category Title]
[Product Filters]
[Product Listing]
[Pagination]
[Content Bottom]
→ [Your Assigned Modules Appear Here] ←
[Footer]
```

---

## 📝 Complete Usage Example

### Scenario: Add Flash Deal and Best Seller to "Beauty" Category

1. **Go to:** Catalog → Categories → Edit "Beauty" category

2. **Click:** Modules tab

3. **Add First Module:**
   - Click **Add Module** (+)
   - Select: `flash_deal`
   - Settings: `{"name":"Beauty Flash Deals","limit":6}`
   - Sort Order: `10`
   - Status: **Enabled**
   
4. **Add Second Module:**
   - Click **Add Module** (+)
   - Select: `bestseller`
   - Settings: `{"name":"Best Sellers","limit":8}`
   - Sort Order: `20`
   - Status: **Enabled**

5. **Save Category**

6. **Result:**
   - Visit the Beauty category page on your website
   - You'll see Flash Deal module first (sort order 10)
   - Then Best Seller module (sort order 20)

---

## 🔧 Troubleshooting

### Modules Not Showing on Frontend?

1. **Check Database Table**
   ```sql
   SELECT * FROM sr_category_module WHERE category_id = YOUR_CATEGORY_ID;
   ```
   (Replace `sr_` with your prefix)

2. **Check Module Status**
   - In admin panel, ensure module status is **Enabled**

3. **Check Module Code**
   - Verify the module code matches an installed module
   - Go to: Extensions → Extensions → Modules
   - Check available module codes

4. **Check Settings Format**
   - Ensure settings are valid JSON
   - Use online JSON validator if needed
   - Remove any trailing commas

5. **Check Browser Console**
   - Open browser Developer Tools (F12)
   - Check Console tab for JavaScript errors
   - Check Network tab for failed requests

### Settings Not Working?

1. **Validate JSON Format**
   - Use: https://jsonlint.com/
   - Ensure proper quotes (double quotes, not single)
   - No trailing commas

2. **Check Module Requirements**
   - Some modules require specific settings
   - Check module documentation or controller file

3. **Test with Simple Settings**
   - Start with: `{"limit":8}`
   - Add more settings gradually

### Database Errors?

1. **Check Table Exists**
   ```sql
   SHOW TABLES LIKE 'sr_category_module';
   ```

2. **Check Database Prefix**
   - Verify in `config.php`: `DB_PREFIX`
   - Ensure SQL script used correct prefix

3. **Check Permissions**
   - Database user needs INSERT, UPDATE, DELETE, SELECT permissions

---

## 💡 Tips & Best Practices

1. **Sort Order Planning**
   - Use increments of 10 (0, 10, 20, 30) for easy reordering
   - Leave gaps for future modules

2. **Module Settings**
   - Keep a backup of working JSON settings
   - Test settings on a test category first

3. **Performance**
   - Don't add too many modules (3-5 per category is recommended)
   - Disable unused modules instead of deleting them

4. **Testing**
   - Always test on a test category first
   - Clear browser cache after changes
   - Check on mobile devices too

---

## 📚 Available Module Codes

To find all available modules:
1. Go to: **Extensions → Extensions → Modules**
2. Look at the module codes listed
3. Common codes include:
   - `product_showcase_tabs`
   - `flash_deal`
   - `bestseller`
   - `featured`
   - `latest`
   - `special`
   - `popular`
   - `tabbed_category`
   - `featured_flash_sale`
   - `ebay_listing`
   - `topseller`
   - `big_offer`

---

## ✅ Quick Checklist

- [ ] Database table created (`sr_category_module`)
- [ ] Database prefix verified in SQL script
- [ ] Category edited in admin panel
- [ ] Modules tab visible
- [ ] Module added with valid JSON settings
- [ ] Sort order set
- [ ] Status set to Enabled
- [ ] Category saved
- [ ] Frontend category page checked
- [ ] Modules displaying correctly

---

## 🆘 Need Help?

If modules still don't work:
1. Check error logs: `system/logs/error.log`
2. Verify module controller files exist
3. Check PHP error reporting is enabled
4. Verify file permissions on module controller files

---

**🎉 You're all set! Start adding modules to your category pages and customize your website!**

