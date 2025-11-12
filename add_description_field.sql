-- =====================================================
-- Add Description Field to Category Module Table
-- =====================================================
-- Run this SQL to add description field to category_module table
-- 
-- IMPORTANT: Replace 'sr_' with your actual database prefix if different
-- You can find your prefix in config.php: DB_PREFIX
-- =====================================================

ALTER TABLE `sr_category_module` 
ADD COLUMN `description` TEXT NULL AFTER `setting`;

-- =====================================================
-- Update Complete!
-- =====================================================

