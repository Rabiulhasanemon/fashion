-- SQL Script to Create Footer Information Pages for Ruplexa
-- Database Prefix: sr_
-- Language ID: 1 (English) - Change if needed
-- Store ID: 0 (Default) - Change if needed

SET @language_id = 1;
SET @store_id = 0;
SET @sort_order = 1;

-- ============================================
-- ABOUT RUPLEXA SECTION
-- ============================================
-- About Us
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'About Us', '<p>Learn more about Ruplexa and our mission to provide quality beauty products.</p>', 'About Us - Ruplexa', 'Learn more about Ruplexa', 'about us, ruplexa');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Blog
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Blog', '<p>Read our latest beauty tips, trends, and product reviews.</p>', 'Blog - Ruplexa', 'Beauty blog and tips', 'blog, beauty tips');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Careers
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Careers', '<p>Join the Ruplexa team and help us bring beauty to everyone.</p>', 'Careers - Ruplexa', 'Career opportunities at Ruplexa', 'careers, jobs');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Gift cards
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Gift cards', '<p>Purchase gift cards for your loved ones.</p>', 'Gift Cards - Ruplexa', 'Buy gift cards', 'gift cards');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Beauty With Heart
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Beauty With Heart', '<p>Our commitment to social responsibility and giving back.</p>', 'Beauty With Heart - Ruplexa', 'Social responsibility', 'beauty with heart');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- ============================================
-- MY RUPLEXA SECTION
-- ============================================
-- Beauty Insider
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Beauty Insider', '<p>Join our Beauty Insider program for exclusive benefits.</p>', 'Beauty Insider - Ruplexa', 'Beauty Insider program', 'beauty insider');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Beauty Offer
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Beauty Offer', '<p>Check out our current beauty offers and promotions.</p>', 'Beauty Offer - Ruplexa', 'Beauty offers and promotions', 'beauty offer');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Buying Guides
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Buying Guides', '<p>Expert guides to help you choose the right beauty products.</p>', 'Buying Guides - Ruplexa', 'Product buying guides', 'buying guides');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Reward Point
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Reward Point', '<p>Learn about our reward points program and how to earn points.</p>', 'Reward Points - Ruplexa', 'Reward points program', 'reward points');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Specials (Already exists in system, but adding as info page if needed)
-- Note: This might link to product/special page instead

-- Wish List (Account feature - might not need separate page)
-- Order History (Account feature - might not need separate page)
-- My Account (Account feature - might not need separate page)

-- ============================================
-- HELP SECTION
-- ============================================
-- Customer Service
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Customer Service', '<p>Get help with your orders and account.</p>', 'Customer Service - Ruplexa', 'Customer service support', 'customer service');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Return and exchanges
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Return and exchanges', '<p>Learn about our return and exchange policy.</p>', 'Returns and Exchanges - Ruplexa', 'Return policy', 'returns, exchanges');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Delivery and Pickup Options
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Delivery and Pickup Options', '<p>Information about delivery and pickup options.</p>', 'Delivery Options - Ruplexa', 'Delivery and pickup', 'delivery, pickup');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Shipping
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Shipping', '<p>Shipping information and rates.</p>', 'Shipping - Ruplexa', 'Shipping information', 'shipping');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Billing
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Billing', '<p>Billing and payment information.</p>', 'Billing - Ruplexa', 'Billing information', 'billing');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Privacy Policy
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Privacy Policy', '<p>Our privacy policy and how we protect your data.</p>', 'Privacy Policy - Ruplexa', 'Privacy policy', 'privacy policy');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Terms and Condition
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Terms and Condition', '<p>Terms and conditions of use.</p>', 'Terms and Conditions - Ruplexa', 'Terms and conditions', 'terms and conditions');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Beauty Service FAQ
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Beauty Service FAQ', '<p>Frequently asked questions about our beauty services.</p>', 'Beauty Service FAQ - Ruplexa', 'Beauty service FAQ', 'faq, beauty service');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
SET @sort_order = @sort_order + 1;

-- Contacts Us (Contact Us)
INSERT INTO sr_information (sort_order, bottom, status) VALUES (@sort_order, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Contact Us', '<p>Get in touch with us. We are here to help.</p>', 'Contact Us - Ruplexa', 'Contact Ruplexa', 'contact us');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

