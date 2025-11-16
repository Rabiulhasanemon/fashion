-- Fix AUTO_INCREMENT for product_image table
-- Run these commands one by one in phpMyAdmin

-- Step 1: Check current max ID
SELECT MAX(product_image_id) as max_id FROM sr_product_image;

-- Step 2: Delete any product_image_id = 0 records
DELETE FROM sr_product_image WHERE product_image_id = 0;

-- Step 3: Set AUTO_INCREMENT to MAX + 1
-- Replace 1 with the actual max_id + 1 from Step 1
-- For example, if max_id is 10, use: ALTER TABLE sr_product_image AUTO_INCREMENT = 11;
ALTER TABLE sr_product_image AUTO_INCREMENT = 1;

