-- =====================================================
-- Category Modules System - Database Table Creation
-- =====================================================
-- This SQL script creates the table needed for the dynamic module system
-- Run this in your database (phpMyAdmin or MySQL command line)
-- 
-- IMPORTANT: Replace 'sr_' with your actual database prefix if different
-- You can find your prefix in config.php: DB_PREFIX
-- =====================================================

CREATE TABLE IF NOT EXISTS `sr_category_module` (
  `category_module_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(64) NOT NULL,
  `setting` text NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_module_id`),
  KEY `category_id` (`category_id`),
  KEY `code` (`code`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- =====================================================
-- Installation Complete!
-- =====================================================
-- After running this script, you can start using the 
-- module assignment feature in the admin panel.
-- =====================================================

