-- ============================================
-- 1. FIX TABLE STRUCTURE (RUN THIS FIRST)
-- ============================================
-- Ensure information_id is auto-incrementing to prevent #1364 error
ALTER TABLE sr_information MODIFY information_id INT(11) NOT NULL AUTO_INCREMENT;

-- ============================================
-- 2. DISABLE OLD FOOTER PAGES (RUN THIS)
-- ============================================
-- Disable all existing footer pages so they don't show
UPDATE sr_information SET bottom = 0 WHERE bottom = 1;

-- ============================================
-- 3. INSERT FOOTER PAGES FOR RUPLEXA
-- ============================================
SET @language_id = 1;
SET @store_id = 0;

-- ABOUT RUPLEXA SECTION (sort_order 1-5)
-- Use INSERT IGNORE or check if exists first to avoid duplicates
INSERT INTO sr_information (sort_order, bottom, status) 
SELECT 1, 1, 1 
WHERE NOT EXISTS (SELECT 1 FROM sr_information_description WHERE title = 'About Us' AND language_id = @language_id);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'About Us', '<div class=\"ruplexa-info-content\"><h3>About Ruplexa</h3><p>Welcome to Ruplexa, your premier destination for heart-centered beauty. We believe that true beauty comes from the inside out.</p></div>', 'About Us - Ruplexa', 'Learn more about Ruplexa', 'about us, ruplexa');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (2, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Blog', '<div class=\"ruplexa-info-content\"><h3>Our Blog</h3><p>Stay updated with the latest beauty tips, product launches, and community stories.</p></div>', 'Blog - Ruplexa', 'Ruplexa Beauty Blog', 'blog, beauty');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (3, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Careers', '<div class=\"ruplexa-info-content\"><h3>Join Our Team</h3><p>Explore exciting career opportunities at Ruplexa and grow with us.</p></div>', 'Careers - Ruplexa', 'Work at Ruplexa', 'careers, jobs');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (4, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Gift cards', '<div class=\"ruplexa-info-content\"><h3>Ruplexa Gift Cards</h3><p>Give the gift of choice with our flexible gift cards.</p></div>', 'Gift Cards - Ruplexa', 'Buy Gift Cards', 'gift cards');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (5, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Beauty With Heart', '<div class=\"ruplexa-info-content\"><h3>Beauty With Heart</h3><p>Our commitment to ethically sourced products and sustainable beauty.</p></div>', 'Beauty With Heart - Ruplexa', 'Sustainable beauty', 'ethical beauty');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

-- MY RUPLEXA SECTION (sort_order 6-9)
INSERT INTO sr_information (sort_order, bottom, status) VALUES (6, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Beauty Insider', '<div class=\"ruplexa-info-content\"><h3>Beauty Insider</h3><p>Join our rewards program and get exclusive access to events and offers.</p></div>', 'Beauty Insider - Ruplexa', 'Rewards program', 'insider, rewards');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (7, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Beauty Offer', '<div class=\"ruplexa-info-content\"><h3>Beauty Offers</h3><p>Check out our latest deals and limited-time promotions.</p></div>', 'Beauty Offers - Ruplexa', 'Deals and offers', 'offers, discounts');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (8, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Buying Guides', '<div class=\"ruplexa-info-content\"><h3>Buying Guides</h3><p>Expert advice to help you find the perfect products for your skin type.</p></div>', 'Buying Guides - Ruplexa', 'Product advice', 'guides, beauty advice');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (9, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Reward Point', '<div class=\"ruplexa-info-content\"><h3>Reward Points</h3><p>Learn how to earn and redeem points on every purchase.</p></div>', 'Reward Points - Ruplexa', 'Earn points', 'reward points');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

-- HELP SECTION (sort_order 10-18)
INSERT INTO sr_information (sort_order, bottom, status) VALUES (10, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Customer Service', '<div class=\"ruplexa-info-content\"><h3>Customer Service</h3><p>We are here to help you 24/7 with any questions.</p></div>', 'Customer Service - Ruplexa', 'Support', 'customer care');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (11, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Return and exchanges', '<div class=\"ruplexa-info-content\"><h3>Returns & Exchanges</h3><p>Our easy 30-day return policy for your peace of mind.</p></div>', 'Returns - Ruplexa', 'Return policy', 'returns');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (12, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Delivery and Pickup Options', '<div class=\"ruplexa-info-content\"><h3>Delivery & Pickup</h3><p>Fast home delivery and convenient store pickup options.</p></div>', 'Delivery - Ruplexa', 'Shipping options', 'delivery');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (13, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Shipping', '<div class=\"ruplexa-info-content\"><h3>Shipping Information</h3><p>Standard and express shipping rates and timelines.</p></div>', 'Shipping - Ruplexa', 'Shipping info', 'shipping');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (14, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Billing', '<div class=\"ruplexa-info-content\"><h3>Billing Info</h3><p>Secure payment methods and billing information.</p></div>', 'Billing - Ruplexa', 'Payment info', 'billing');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (15, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Privacy Policy', '<div class=\"ruplexa-info-content\"><h3>Privacy Policy</h3><p>How we protect and manage your personal data.</p></div>', 'Privacy - Ruplexa', 'Privacy policy', 'privacy');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (16, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Terms and Condition', '<div class=\"ruplexa-info-content\"><h3>Terms & Conditions</h3><p>Rules and guidelines for using our website and services.</p></div>', 'Terms - Ruplexa', 'Terms of use', 'terms');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (17, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Beauty Service FAQ', '<div class=\"ruplexa-info-content\"><h3>Service FAQ</h3><p>Find answers to common questions about our beauty services.</p></div>', 'FAQ - Ruplexa', 'Frequently asked questions', 'faq');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);

INSERT INTO sr_information (sort_order, bottom, status) VALUES (18, 1, 1);
SET @info_id = LAST_INSERT_ID();
INSERT INTO sr_information_description (information_id, language_id, title, description, meta_title, meta_description, meta_keyword) 
VALUES (@info_id, @language_id, 'Contact Us', '<div class=\"ruplexa-info-content\"><h3>Contact Us</h3><p>Reach out to us via email, phone, or live chat.</p></div>', 'Contact - Ruplexa', 'Contact details', 'contact');
INSERT INTO sr_information_to_store (information_id, store_id) VALUES (@info_id, @store_id);
