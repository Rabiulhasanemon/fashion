-- =====================================================
-- Add video_url column to product_description
-- =====================================================
-- Run this SQL on your store database. Replace `sr_` with
-- your actual DB prefix if it differs (see config.php).
-- =====================================================

ALTER TABLE `sr_product_description`
ADD COLUMN `video_url` VARCHAR(255) DEFAULT NULL AFTER `short_description`;

-- =====================================================
-- Update complete.
-- =====================================================









