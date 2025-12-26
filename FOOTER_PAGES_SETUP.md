# Footer Pages Setup Guide for Ruplexa

This guide explains how to set up the footer information pages for your Ruplexa website.

## Overview

The footer is organized into three sections:
1. **About Ruplexa** - Company information
2. **My Ruplexa** - Customer account and rewards
3. **Help** - Support and policies

## Installation Methods

### Method 1: Using PHP Script (Recommended)

1. **Update Database Configuration**
   - Open `install_footer_pages.php`
   - Update these constants at the top:
     ```php
     define('DB_HOSTNAME', 'localhost');
     define('DB_USERNAME', 'your_username');
     define('DB_PASSWORD', 'your_password');
     define('DB_DATABASE', 'your_database_name');
     define('DB_PREFIX', 'sr_');
     ```

2. **Run the Script**
   - Via browser: Navigate to `http://yourdomain.com/install_footer_pages.php`
   - Via command line: `php install_footer_pages.php`

3. **Verify Installation**
   - Go to Admin Panel > Catalog > Information
   - You should see all the new pages listed

### Method 2: Using SQL File

1. **Update the SQL File**
   - Open `create_footer_pages.sql`
   - Replace `sr_` with your actual database prefix if different
   - Update `@language_id` and `@store_id` if needed

2. **Import via phpMyAdmin**
   - Log into phpMyAdmin
   - Select your database
   - Click "Import" tab
   - Choose `create_footer_pages.sql`
   - Click "Go"

3. **Import via Command Line**
   ```bash
   mysql -u username -p database_name < create_footer_pages.sql
   ```

## Pages Created

### About Ruplexa Section (sort_order 1-5)
- About Us
- Blog
- Careers
- Gift cards
- Beauty With Heart

### My Ruplexa Section (sort_order 6-9)
- Beauty Insider
- Beauty Offer
- Buying Guides
- Reward Point
- Specials (system link)
- Wish List (account link)
- Order History (account link)
- My Account (account link)

### Help Section (sort_order 10+)
- Customer Service
- Return and exchanges
- Delivery and Pickup Options
- Shipping
- Billing
- Privacy Policy
- Terms and Condition
- Beauty Service FAQ
- Contact Us

## Customizing Pages

1. **Edit Content**
   - Go to Admin Panel > Catalog > Information
   - Click "Edit" on any page
   - Update the title, description, and meta information
   - Click "Save"

2. **Change Order**
   - The pages are organized by `sort_order` in the database
   - About Ruplexa: 1-5
   - My Ruplexa: 6-9
   - Help: 10+
   - You can change the sort_order in Admin Panel to reorder pages

3. **Enable/Disable**
   - Set `Status` to "Enabled" to show in footer
   - Set `Status` to "Disabled" to hide from footer
   - The `bottom` field must be set to "Yes" for pages to appear in footer

## How It Works

The footer controller (`catalog/controller/common/footer.php`) automatically:
- Loads all information pages where `bottom = 1` and `status = 1`
- Groups them by `sort_order` ranges:
  - 1-5: About Ruplexa
  - 6-9: My Ruplexa
  - 10+: Help
- Displays them in the footer template

## Troubleshooting

### Pages Not Showing in Footer

1. Check that `bottom = 1` in the database:
   ```sql
   SELECT * FROM sr_information WHERE bottom = 1 AND status = 1;
   ```

2. Check that pages are linked to your store:
   ```sql
   SELECT * FROM sr_information_to_store WHERE store_id = 0;
   ```

3. Clear OpenCart cache:
   - Admin Panel > System > Settings > Refresh

### Pages in Wrong Section

- Check the `sort_order` value in the database
- Update it to match the desired section:
  - About Ruplexa: 1-5
  - My Ruplexa: 6-9
  - Help: 10+

## Database Structure

The pages are stored in these tables:
- `sr_information` - Main page data (sort_order, bottom, status)
- `sr_information_description` - Page content (title, description, meta tags)
- `sr_information_to_store` - Links pages to stores

## Security Note

**IMPORTANT**: Delete `install_footer_pages.php` after installation for security reasons!

```bash
rm install_footer_pages.php
```

Or rename it to prevent accidental execution.



